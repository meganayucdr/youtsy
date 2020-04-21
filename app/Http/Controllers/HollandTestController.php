<?php

namespace App\Http\Controllers;

use App\Career;
use App\Charts\HollandChart;
use App\HollandCode;
use App\HollandTest;
use App\UserScoreDetail;
use Dotenv\Validator;
use function GuzzleHttp\Promise\unwrap;
use Illuminate\Http\Request;
use App\Question;
use App\Option;
use App\User;
use Illuminate\Support\Facades\Auth;
use App\HollandTestDetail;
use App\Http\Controllers\HollandTestDetailController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

/**
 * HollandTestController
 */
class HollandTestController extends Controller
{
    public function showTest(Request $request)  {
//        $request->session()->forget('data');
        $questions = Question::paginate(6);
        $options = Option::all();

        if ($request->data_test != null && ($request->page > $request->current_page)) {
            $this->storeToSession($request);
        }

        if($request->ajax())    {
            //dd($data);
            //$this->storeToSession($request);
            return view('holland_tests.data_test', [
                'questions' => $questions,
                'options' => $options
            ]);
        }

        return response()->view('holland_tests.show_test', [
            'questions' => $questions,
            'options' => $options
        ]);
    }

    private function storeToSession (Request $request)   {
        $request->session()->push('data', $request->data_test);
    }

    public function storeUserTest(Request $request)   {
        $holland_test = new HollandTest();

        $data_session = $this->getSessionData($request);
//        dd($data_session);

        $user = User::find(Auth::id());
        $holland_test->user()->associate($user);

        $holland_test->save();

        $detail_controller = new HollandTestDetailController();
        $detail_controller->storeToDatabase($holland_test, $request, $data_session);
        $request->session()->forget('data');
        $this->generateUserScore($holland_test);

        return Redirect::route('holland_test.show_report', array($holland_test->id)); // redirect dari sini, lalu laporan kelihatannya musti pake ID report di routenya.
    }

//    public function storeToDatabase(HollandTest $hollandTest)   {
//        $hollandTest->save();
//    }

    private function getSessionData(Request $request)    {
        $data_session = [];
        foreach ($request->session()->get('data') as $data_tests)    {
            foreach ($data_tests as $data_test) {
                array_push($data_session, [
                    'questions' => $data_test['questions_id'],
                    'options' => $data_test['options_id']
                ]);
            }
        }
        return $data_session;
    }

    public function generateUserScore(HollandTest $hollandTest) {
        $user_score_controller = new UserScoreController();
        $user_score_controller->storeScore($hollandTest);
    }

    private function sortId($result)    {
        $data = array();
        $i = 0;
        foreach ($result as $r) {
            $i++;
            array_push($data, [
                'id' => $i,
                'eigen' => $r
            ]);
        }

        $data_sort = array_column($data, 'eigen');
        array_multisort($data_sort, SORT_DESC, $data);
        $data_id = array();

        foreach ($data as $d)   {
            array_push($data_id, $d['id']);
        }
        return $data_id;
    }

    public function showReport($id) {
        $hollandTest = HollandTest::find($id);
//        dd($hollandTest);
        $result = $this->reportANP($id);
        $data_id = $this->sortId($result);
        $data_id = collect($data_id)->chunk(3)->first();
        $data_id = $data_id->unwrap($data_id);


//        $totals = UserScoreDetail::select('total', 'holland_code_id')
//            ->whereHas('userScore.hollandTest' , function ($q) use ($hollandTest)   {
//                $q->where('id', $hollandTest->id);
//            })
//            ->whereIn('holland_code_id', $data_id)->get();
        $holland_code_information = HollandCode::whereIn('id', $data_id)->get();
//
//        $sum = UserScoreDetail::whereHas('userScore.hollandTest' , function ($q) use ($hollandTest)   {
//                $q->where('id', $hollandTest->id);
//            })
//            ->whereIn('holland_code_id', $data_id)
//            ->sum('total');
//        $result = array();
//        foreach ($totals as $total)    {
//            $decimal = $total->total/$sum;
//            array_push($result, [
//                'id' => $total->holland_code_id,
//                'percentage' => round((float) $decimal*100 )
//            ]);
//        }
        $careers = DB::select("SELECT DISTINCT(careers.name) FROM careers
                                    JOIN career_holland_code ON career_holland_code.career_id = careers.id
                                    JOIN holland_codes ON holland_codes.id = career_holland_code.holland_code_id
                                    WHERE career_holland_code.career_id IN 
		                            (SELECT career_id FROM career_holland_code WHERE holland_code_id = ". $holland_code_information['0']['id'] .") AND 
		                            career_holland_code.career_id IN (SELECT career_id FROM career_holland_code 
		                            WHERE holland_code_id = ". $holland_code_information['1']['id'] .")");
        $d = [];
        foreach ($careers as $career)    {
            $d = [
                'name'=> $career->name
            ];
        }

//        dd($d);

        return view( 'holland_tests.show_report', [
            'id' => $hollandTest->id,
            'careers' => $d,
            'holland_code_information' => $holland_code_information] );
    }

    public function getResult($id)  {
        $hollandTest = HollandTest::find($id);

        $result = $this->reportANP($id);
        $data_id = $this->sortId($result);

        $totals = UserScoreDetail::whereHas('userScore.hollandTest' , function ($q) use ($hollandTest)   {
                $q->where('id', $hollandTest->id);
            })->get();
        $holland_code_data = HollandCode::select('name')->whereIn('id', $data_id)->get();

        $sum = UserScoreDetail::whereHas('userScore.hollandTest' , function ($q) use ($hollandTest)   {
            $q->where('id', $hollandTest->id);
        })->sum('total');
        $result = array();
        foreach ($totals as $total) {
            $decimal = $total->total / $sum;
            array_push($result, [
                'id' => $total->holland_code_id,
                'percentage' => round((float)$decimal * 100)
            ]);
        }
        $data = [$holland_code_data, $result];
        //dd($data);
        return response()->json($data);
    }

    public function reportANP($holland_test_id) {
        $details = UserScoreDetail::whereHas('userScore.hollandTest', function ($q) use ($holland_test_id) {
            $q->where('id', $holland_test_id);
        })->orderBy('total', 'desc')->get();
        $data = $details;

//        dd($details);

        $pair_wise = $this->initPairWiseArray($details);
//        dd($pair_wise);
        foreach ($details as $detail)   {
//            dd($detail->holland_code_id);
            $pair_wise = $this->setPairWise($data, $detail, $pair_wise);
        }
//        dd($pair_wise);
        $result = $this->executePythonScript($pair_wise);
//        dd($result);
        return $result;
    }

    private function executePythonScript($pair_wise)    {
        $json_str = json_encode($pair_wise);
//        dd($json_str);
        $process = new Process("python\\venv\Scripts\activate.bat && python python\HollandANP.py {$json_str}");
        $process->run();

        if (!$process->isSuccessful())  {
            throw new ProcessFailedException($process);
        }

        $result = json_decode($process->getOutput());
        return $result;
    }

    private function setPairWise($details, $detail, $pair_wise)  {
        $j = 1;
        foreach ($details as $d)    {
            if($detail->total == $d->total || $d->holland_code_id == $detail->holland_code_id)
                $pair_wise[$detail->holland_code_id - 1][$d->holland_code_id - 1] = 1;
            else    {
                if ($detail->total < $d->total){
                    $prev_value = $pair_wise[$d->holland_code_id - 1][$detail->holland_code_id - 1];
                    $pair_wise[$detail->holland_code_id - 1][$d->holland_code_id - 1] = 1/$prev_value;
                }
                else {
                    $pair_wise[$detail->holland_code_id - 1][$d->holland_code_id - 1] = $j + 1;
                    $j++;
                }
            }
        }
        return $pair_wise;
    }

//    private function getPrevData($pair_wise, $index, $details) {
//        for ($i = 0 ; $i < count($details) ; $i++)   {
//            if ($pair_wise[$i][$index] != 0)
//                return $pair_wise[$i][$index];
//        }
//    }

    private function initPairWiseArray($details)  {
        $pair_wise = array();
        for ($i = 0 ; $i < count($details) ; $i++) {
            for ($j = 0 ; $j < count($details) ; $j++)    {
                $pair_wise[$i][$j] = 0;
            }
        }
        return $pair_wise;
    }

    public function showResultUser($user_id)    {
        $result = HollandTest::select('id', 'created_at')->where('user_id', $user_id)
            ->orderBy('created_at', 'desc')->paginate(6);

        return view('holland_tests.user_results', [
            'holland_test' => $result
        ]);
    }
}
