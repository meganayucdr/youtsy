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
    /**
     * Relations
     * @param  \Illuminate\Http\Request|null $request
     * @param HollandTestDetail $holland_test_detail
     * @return array
     */
    public static function relations(Request $request = null, HollandTestDetail $holland_test_detail = null)
    {
        return [
            'holland_test_detail' => [
                'belongsToMany' => [], // also for morphToMany
                'hasMany' => [
                    //[ 'name' => 'children', 'label' => ucwords(__('holland_test_details.children')) ],
                ], // also for morphMany, hasManyThrough
                'hasOne' => [
                    //[ 'name' => 'child', 'label' => ucwords(__('holland_test_details.child')) ],
                ], // also for morphOne
            ]
        ];
    }

    /**
     * Visibles
     * @param  \Illuminate\Http\Request|null $request
     * @param HollandTestDetail $holland_test_detail
     * @return array
     */
    public static function visibles(Request $request = null, HollandTestDetail $holland_test_detail = null)
    {
        return [
            'index' => [
                'holland_test_detail' => [
                    //[ 'name' => 'parent', 'label' => ucwords(__('holland_test_details.parent')), 'column' => 'name' ], // Only support belongsTo, hasOne
                    [ 'name' => 'name', 'label' => ucwords(__('holland_test_details.name')) ],
                ]
            ],
            'show' => [
                'holland_test_detail' => [
                    //[ 'name' => 'parent', 'label' => ucwords(__('holland_test_details.parent')), 'column' => 'name' ], // Only support belongsTo, hasOne
                    [ 'name' => 'name', 'label' => ucwords(__('holland_test_details.name')) ],
                ]
            ]
        ];
    }

    /**
     * Fields
     * @param  \Illuminate\Http\Request|null $request
     * @param HollandTestDetail $holland_test_detail
     * @return array
     */
    public static function fields(Request $request = null, HollandTestDetail $holland_test_detail = null)
    {
        return [
            'create' => [
                'holland_test_detail' => [
                    //[ 'field' => 'select', 'name' => 'parent_id', 'label' => ucwords(__('holland_test_details.parent')), 'required' => true, 'options' => \App\Parent::filter()->get()->map(function ($parent) {
                    //    return [ 'value' => $parent->id, 'text' => $parent->name ];
                    //})->prepend([ 'value' => '', 'text' => '-' ])->toArray() ],
                    [ 'field' => 'input', 'type' => 'text', 'name' => 'name', 'label' => ucwords(__('holland_test_details.name')), 'required' => true ],
                ]
            ],
            'edit' => [
                'holland_test_detail' => [
                    //[ 'field' => 'select', 'name' => 'parent_id', 'label' => ucwords(__('holland_test_details.parent')), 'options' => \App\Parent::filter()->get()->map(function ($parent) {
                    //    return [ 'value' => $parent->id, 'text' => $parent->name ];
                    //})->prepend([ 'value' => '', 'text' => '-' ])->toArray() ],
                    [ 'field' => 'input', 'type' => 'text', 'name' => 'name', 'label' => ucwords(__('holland_test_details.name')) ],
                ]
            ]
        ];
    }

    /**
     * Rules
     * @param  \Illuminate\Http\Request|null $request
     * @param HollandTestDetail $holland_test_detail
     * @return array
     */
    public static function rules(Request $request = null, HollandTestDetail $holland_test_detail = null)
    {
        return [
            'store' => [
                //'parent_id' => 'required|exists:parents,id',
                'name' => 'required|string|max:255',
            ],
            'update' => [
                //'parent_id' => 'exists:parents,id',
                'name' => 'string|max:255',
            ]
        ];
    }

    /**
    * Instantiate a new controller instance.
    *
    * @return void
    */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index()
    {
        $holland_test_details = HollandTestDetail::filter()
            ->paginate()->appends(request()->query());
        $this->authorize('index', 'App\HollandTestDetail');

        return response()->view('holland_test_details.index', [
            'holland_test_details' => $holland_test_details,
            'relations' => self::relations(request()),
            'visibles' => self::visibles(request())['index']
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create()
    {
        $this->authorize('create', 'App\HollandTestDetail');

        return response()->view('holland_test_details.create', [
            'fields' => self::fields(request())['create']
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(Request $request)
    {
        $this->authorize('create', 'App\HollandTestDetail');
        $request->validate(self::rules($request)['store']);

        $holland_test_detail = new HollandTestDetail;
        foreach (self::rules($request)['store'] as $key => $value) {
            if (str_contains($value, [ 'file', 'image', 'mimetypes', 'mimes' ])) {
                if ($request->hasFile($key)) {
                    $holland_test_detail->{$key} = $request->file($key)->store('holland_test_details');
                } elseif ($request->exists($key)) {
                    $holland_test_detail->{$key} = $request->{$key};
                }
            } elseif ($request->exists($key)) {
                $holland_test_detail->{$key} = $request->{$key};
            }
        }
        $holland_test_detail->save();

        if (request()->filled('redirect') && starts_with(request()->redirect, request()->root()))
            $response = response()->redirectTo(request()->redirect);
        else
            $response = response()->redirectToRoute('holland_test_details.show', $holland_test_detail->getKey());

        return $response->withInput([ $holland_test_detail->getForeignKey() => $holland_test_detail->getKey() ])
            ->with('status', __('Success'));
    }

    /**
     * Display the specified resource.
     *
     * @param HollandTestDetail $holland_test_detail
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(HollandTestDetail $holland_test_detail)
    {
        $this->authorize('view', $holland_test_detail);

        return response()->view('holland_test_details.show', [
            'holland_test_detail' => $holland_test_detail,
            'relations' => self::relations(request(), $holland_test_detail),
            'visibles' => self::visibles(request(), $holland_test_detail)['show'],
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param HollandTestDetail $holland_test_detail
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(HollandTestDetail $holland_test_detail)
    {
        $this->authorize('update', $holland_test_detail);

        return response()->view('holland_test_details.edit', [
            'holland_test_detail' => $holland_test_detail,
            'fields' => self::fields(request(), $holland_test_detail)['edit']
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param HollandTestDetail $holland_test_detail
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Request $request, HollandTestDetail $holland_test_detail)
    {
        $this->authorize('update', $holland_test_detail);
        $request->validate(self::rules($request, $holland_test_detail)['update']);

        foreach (self::rules($request, $holland_test_detail)['update'] as $key => $value) {
            if (str_contains($value, [ 'file', 'image', 'mimetypes', 'mimes' ])) {
                if ($request->hasFile($key)) {
                    $holland_test_detail->{$key} = $request->file($key)->store('holland_test_details');
                } elseif ($request->exists($key)) {
                    $holland_test_detail->{$key} = $request->{$key};
                }
            } elseif ($request->exists($key)) {
                $holland_test_detail->{$key} = $request->{$key};
            }
        }
        $holland_test_detail->save();

        if (request()->filled('redirect') && starts_with(request()->redirect, request()->root()))
            $response = response()->redirectTo(request()->redirect);
        else
            $response = response()->redirectToRoute('holland_test_details.show', $holland_test_detail->getKey());

        return $response->withInput([ $holland_test_detail->getForeignKey() => $holland_test_detail->getKey() ])
            ->with('status', __('Success'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param HollandTestDetail $holland_test_detail
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Exception
     */
    public function destroy(HollandTestDetail $holland_test_detail)
    {
        $this->authorize('delete', $holland_test_detail);
        $holland_test_detail->delete();

        if (request()->filled('redirect') && starts_with(request()->redirect, request()->root()) && !str_contains(request()->redirect, '/holland_test_details/'.$holland_test_detail->getKey()))
            $response = response()->redirectTo(request()->redirect);
        else
            $response = response()->redirectToRoute('holland_test_details.index');

        return $response->with('status', __('Success'));
    }

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
