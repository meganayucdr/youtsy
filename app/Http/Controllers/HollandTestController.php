<?php

namespace App\Http\Controllers;

use App\HollandTest;
use Illuminate\Http\Request;
use App\Question;
use App\Option;
use App\User;

/**
 * HollandTestController
 */
class HollandTestController extends Controller
{
    /**
     * Relations
     * @param  \Illuminate\Http\Request|null $request
     * @param HollandTest $holland_test
     * @return array
     */
    public static function relations(Request $request = null, HollandTest $holland_test = null)
    {
        return [
            'holland_test' => [
                'belongsToMany' => [], // also for morphToMany
                'hasMany' => [
                    //[ 'name' => 'children', 'label' => ucwords(__('holland_tests.children')) ],
                ], // also for morphMany, hasManyThrough
                'hasOne' => [
                    //[ 'name' => 'child', 'label' => ucwords(__('holland_tests.child')) ],
                ], // also for morphOne
            ]
        ];
    }

    /**
     * Visibles
     * @param  \Illuminate\Http\Request|null $request
     * @param HollandTest $holland_test
     * @return array
     */
    public static function visibles(Request $request = null, HollandTest $holland_test = null)
    {
        return [
            'index' => [
                'holland_test' => [
                    //[ 'name' => 'parent', 'label' => ucwords(__('holland_tests.parent')), 'column' => 'name' ], // Only support belongsTo, hasOne
                    [ 'name' => 'name', 'label' => ucwords(__('holland_tests.name')) ],
                ]
            ],
            'show' => [
                'holland_test' => [
                    //[ 'name' => 'parent', 'label' => ucwords(__('holland_tests.parent')), 'column' => 'name' ], // Only support belongsTo, hasOne
                    [ 'name' => 'name', 'label' => ucwords(__('holland_tests.name')) ],
                ]
            ]
        ];
    }

    /**
     * Fields
     * @param  \Illuminate\Http\Request|null $request
     * @param HollandTest $holland_test
     * @return array
     */
    public static function fields(Request $request = null, HollandTest $holland_test = null)
    {
        return [
            'create' => [
                'holland_test' => [
                    //[ 'field' => 'select', 'name' => 'parent_id', 'label' => ucwords(__('holland_tests.parent')), 'required' => true, 'options' => \App\Parent::filter()->get()->map(function ($parent) {
                    //    return [ 'value' => $parent->id, 'text' => $parent->name ];
                    //})->prepend([ 'value' => '', 'text' => '-' ])->toArray() ],
                    [ 'field' => 'input', 'type' => 'text', 'name' => 'name', 'label' => ucwords(__('holland_tests.name')), 'required' => true ],
                ]
            ],
            'edit' => [
                'holland_test' => [
                    //[ 'field' => 'select', 'name' => 'parent_id', 'label' => ucwords(__('holland_tests.parent')), 'options' => \App\Parent::filter()->get()->map(function ($parent) {
                    //    return [ 'value' => $parent->id, 'text' => $parent->name ];
                    //})->prepend([ 'value' => '', 'text' => '-' ])->toArray() ],
                    [ 'field' => 'input', 'type' => 'text', 'name' => 'name', 'label' => ucwords(__('holland_tests.name')) ],
                ]
            ]
        ];
    }

    /**
     * Rules
     * @param  \Illuminate\Http\Request|null $request
     * @param HollandTest $holland_test
     * @return array
     */
    public static function rules(Request $request = null, HollandTest $holland_test = null)
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
        $holland_tests = HollandTest::filter()
            ->paginate()->appends(request()->query());
        $this->authorize('index', 'App\HollandTest');

        return response()->view('holland_tests.index', [
            'holland_tests' => $holland_tests,
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
        $this->authorize('create', 'App\HollandTest');

        return response()->view('holland_tests.create', [
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
        $this->authorize('create', 'App\HollandTest');
        $request->validate(self::rules($request)['store']);

        $holland_test = new HollandTest;
        foreach (self::rules($request)['store'] as $key => $value) {
            if (str_contains($value, [ 'file', 'image', 'mimetypes', 'mimes' ])) {
                if ($request->hasFile($key)) {
                    $holland_test->{$key} = $request->file($key)->store('holland_tests');
                } elseif ($request->exists($key)) {
                    $holland_test->{$key} = $request->{$key};
                }
            } elseif ($request->exists($key)) {
                $holland_test->{$key} = $request->{$key};
            }
        }
        $holland_test->save();

        if (request()->filled('redirect') && starts_with(request()->redirect, request()->root()))
            $response = response()->redirectTo(request()->redirect);
        else
            $response = response()->redirectToRoute('holland_tests.show', $holland_test->getKey());

        return $response->withInput([ $holland_test->getForeignKey() => $holland_test->getKey() ])
            ->with('status', __('Success'));
    }

    /**
     * Display the specified resource.
     *
     * @param HollandTest $holland_test
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(HollandTest $holland_test)
    {
        $this->authorize('view', $holland_test);

        return response()->view('holland_tests.show', [
            'holland_test' => $holland_test,
            'relations' => self::relations(request(), $holland_test),
            'visibles' => self::visibles(request(), $holland_test)['show'],
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param HollandTest $holland_test
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(HollandTest $holland_test)
    {
        $this->authorize('update', $holland_test);

        return response()->view('holland_tests.edit', [
            'holland_test' => $holland_test,
            'fields' => self::fields(request(), $holland_test)['edit']
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param HollandTest $holland_test
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Request $request, HollandTest $holland_test)
    {
        $this->authorize('update', $holland_test);
        $request->validate(self::rules($request, $holland_test)['update']);

        foreach (self::rules($request, $holland_test)['update'] as $key => $value) {
            if (str_contains($value, [ 'file', 'image', 'mimetypes', 'mimes' ])) {
                if ($request->hasFile($key)) {
                    $holland_test->{$key} = $request->file($key)->store('holland_tests');
                } elseif ($request->exists($key)) {
                    $holland_test->{$key} = $request->{$key};
                }
            } elseif ($request->exists($key)) {
                $holland_test->{$key} = $request->{$key};
            }
        }
        $holland_test->save();

        if (request()->filled('redirect') && starts_with(request()->redirect, request()->root()))
            $response = response()->redirectTo(request()->redirect);
        else
            $response = response()->redirectToRoute('holland_tests.show', $holland_test->getKey());

        return $response->withInput([ $holland_test->getForeignKey() => $holland_test->getKey() ])
            ->with('status', __('Success'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param HollandTest $holland_test
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Exception
     */
    public function destroy(HollandTest $holland_test)
    {
        $this->authorize('delete', $holland_test);
        $holland_test->delete();

        if (request()->filled('redirect') && starts_with(request()->redirect, request()->root()) && !str_contains(request()->redirect, '/holland_tests/'.$holland_test->getKey()))
            $response = response()->redirectTo(request()->redirect);
        else
            $response = response()->redirectToRoute('holland_tests.index');

        return $response->with('status', __('Success'));
    }

    public function showTest()  {
        $questions = Question::paginate(10);
        $options = Option::all();
        $holland_test_id = HollandTest::select('id')->orderBy('created_at', 'desc')->first();

        if ($holland_test_id == null)   {
            $holland_test_id = 1;
        }

        return response()->view('holland_tests.show_test', [
            'questions' => $questions,
            'options' => $options,
            'holland_test_id' => $holland_test_id
        ]);
    }
}
