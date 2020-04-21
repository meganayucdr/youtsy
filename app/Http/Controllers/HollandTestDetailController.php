<?php

namespace App\Http\Controllers;

use App\HollandTest;
use App\HollandTestDetail;
use App\Option;
use App\Question;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * HollandTestDetailController
 */
class HollandTestDetailController extends Controller
{
    public function storeToDatabase(HollandTest $hollandTest, Request $request, $data_session)   {
        $details = array();

        foreach ($data_session as $data)    {
//            dd($data['questions']);
            $details = $this->detailSetter($details, $hollandTest, $data['questions'], $data['options']);
        }

        $options = $request->input('options_id');
        $questions = $request->input('questions_id');
        $last_page = $request->input('last_page');

        foreach ( $questions[$last_page] as $question_id ) {
//            dd($question_id);
            $details = $this->detailSetter($details, $hollandTest, $question_id, $options[$question_id]);
        }
//        dd($details);
        HollandTestDetail::insert($details);
    }

    private function detailSetter($details, $hollandTest, $question_data, $option_data)  {
        $hollandTestDetail = new HollandTestDetail();
        $hollandTestDetail->hollandTest()->associate($hollandTest);

        $question = Question::find($question_data);
        $hollandTestDetail->question()->associate($question);

        $option = Option::find($option_data);
        $hollandTestDetail->option()->associate($option);
        array_push($details, [
            'holland_test_id' => $hollandTestDetail->holland_test_id,
            'option_id' => $hollandTestDetail->option_id,
            'question_id' => $hollandTestDetail->question_id,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        return $details;
    }
}
