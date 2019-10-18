<?php

namespace App\Http\Controllers;

use App\Career;
use App\HollandCode;
use Illuminate\Http\Request;

/**
 * CareerController
 */
class CareerController extends Controller
{
    /**
     * Relations
     * @param  \Illuminate\Http\Request|null $request
     * @param Career $career
     * @return array
     */
    public static function relations(Request $request = null, Career $career = null)
    {
        return [
            'career' => [
                'belongsToMany' => [
                    [ 'name' => 'hollandCodes', 'label' => ucwords(__('careers.holland_codes'))  ]
                ], // also for morphToMany
                'hasMany' => [
                    //[ 'name' => 'children', 'label' => ucwords(__('careers.children')) ],
                ], // also for morphMany, hasManyThrough
                'hasOne' => [
                    //[ 'name' => 'child', 'label' => ucwords(__('careers.child')) ],
                ], // also for morphOne
            ]
        ];
    }

    /**
     * Visibles
     * @param  \Illuminate\Http\Request|null $request
     * @param Career $career
     * @return array
     */
    public static function visibles(Request $request = null, Career $career = null)
    {
        return [
            'index' => [
                'career' => [
                    //[ 'name' => 'parent', 'label' => ucwords(__('careers.parent')), 'column' => 'name' ], // Only support belongsTo, hasOne
                    [ 'name' => 'name', 'label' => ucwords(__('careers.name')) ],
                ]
            ],
            'show' => [
                'career' => [
                    //[ 'name' => 'parent', 'label' => ucwords(__('careers.parent')), 'column' => 'name' ], // Only support belongsTo, hasOne
                    [ 'name' => 'name', 'label' => ucwords(__('careers.name')) ],
                ]
            ]
        ];
    }

    /**
     * Fields
     * @param  \Illuminate\Http\Request|null $request
     * @param Career $career
     * @return array
     */
    public static function fields(Request $request = null, Career $career = null)
    {
        return [
            'create' => [
                'career' => [
                    //[ 'field' => 'select', 'name' => 'parent_id', 'label' => ucwords(__('careers.parent')), 'required' => true, 'options' => \App\Parent::filter()->get()->map(function ($parent) {
                    //    return [ 'value' => $parent->id, 'text' => $parent->name ];
                    //})->prepend([ 'value' => '', 'text' => '-' ])->toArray() ],
                    [ 'field' => 'input', 'type' => 'text', 'name' => 'name', 'label' => ucwords(__('careers.name')), 'required' => true ],
                    [ 'field' => 'select', 'multiple' => 'multiple', 'name' => 'holland_code_id', 'label' => ucwords(__('careers.holland_code')), 'required' => true, 'options' => \App\HollandCode::filter()->get()->map(function ($holland_code) {
                        return [ 'value' => $holland_code->id, 'text' => $holland_code->name ];
                    })->prepend([ 'value' => '', 'text' => '-' ])->toArray() ],
                ]
            ],
            'edit' => [
                'career' => [
                    //[ 'field' => 'select', 'name' => 'parent_id', 'label' => ucwords(__('careers.parent')), 'options' => \App\Parent::filter()->get()->map(function ($parent) {
                    //    return [ 'value' => $parent->id, 'text' => $parent->name ];
                    //})->prepend([ 'value' => '', 'text' => '-' ])->toArray() ],
                    [ 'field' => 'input', 'type' => 'text', 'name' => 'name', 'label' => ucwords(__('careers.name')) ],
                    [ 'field' => 'select', 'multiple' => 'multiple', 'name' => 'holland_code_id', 'label' => ucwords(__('careers.holland_code')), 'options' => \App\HollandCode::filter()->get()->map(function ($holland_codes) {
                        return [ 'value' => $holland_codes->id, 'text' => $holland_codes->name ];
                    })->prepend([ 'value' => '', 'text' => '-' ])->toArray() ],
                ]
            ]
        ];
    }

    /**
     * Rules
     * @param  \Illuminate\Http\Request|null $request
     * @param Career $career
     * @return array
     */
    public static function rules(Request $request = null, Career $career = null)
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
        $careers = Career::filter()
            ->paginate()->appends(request()->query());
        $this->authorize('index', 'App\Career');

        return response()->view('careers.index', [
            'careers' => $careers,
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
        $this->authorize('create', 'App\Career');

        return response()->view('careers.create', [
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
        $this->authorize('create', 'App\Career');
        $request->validate(self::rules($request)['store']);

        $career = new Career;
        foreach (self::rules($request)['store'] as $key => $value) {
            if (str_contains($value, [ 'file', 'image', 'mimetypes', 'mimes' ])) {
                if ($request->hasFile($key)) {
                    $career->{$key} = $request->file($key)->store('careers');
                } elseif ($request->exists($key)) {
                    $career->{$key} = $request->{$key};
                }
            } elseif ($request->exists($key)) {
                $career->{$key} = $request->{$key};
            }
        }

        $career->save();

        $holland_code = HollandCode::find($request->holland_code_id);
        $career->hollandCodes()->attach($holland_code);

        if (request()->filled('redirect') && starts_with(request()->redirect, request()->root()))
            $response = response()->redirectTo(request()->redirect);
        else
            $response = response()->redirectToRoute('careers.show', $career->getKey());

        return $response->withInput([ $career->getForeignKey() => $career->getKey() ])
            ->with('status', __('Success'));
    }

    /**
     * Display the specified resource.
     *
     * @param Career $career
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(Career $career)
    {
        $this->authorize('view', $career);

        return response()->view('careers.show', [
            'career' => $career,
            'relations' => self::relations(request(), $career),
            'visibles' => self::visibles(request(), $career)['show'],
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Career $career
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(Career $career)
    {
        $this->authorize('update', $career);

        return response()->view('careers.edit', [
            'career' => $career,
            'fields' => self::fields(request(), $career)['edit']
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Career $career
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Request $request, Career $career)
    {
        $this->authorize('update', $career);
        $request->validate(self::rules($request, $career)['update']);

        foreach (self::rules($request, $career)['update'] as $key => $value) {
            if (str_contains($value, [ 'file', 'image', 'mimetypes', 'mimes' ])) {
                if ($request->hasFile($key)) {
                    $career->{$key} = $request->file($key)->store('careers');
                } elseif ($request->exists($key)) {
                    $career->{$key} = $request->{$key};
                }
            } elseif ($request->exists($key)) {
                $career->{$key} = $request->{$key};
            }
        }
        $career->save();

        $holland_code = HollandCode::find($request->holland_code_id);
        $career->hollandCodes()->attach($holland_code);

        if (request()->filled('redirect') && starts_with(request()->redirect, request()->root()))
            $response = response()->redirectTo(request()->redirect);
        else
            $response = response()->redirectToRoute('careers.show', $career->getKey());

        return $response->withInput([ $career->getForeignKey() => $career->getKey() ])
            ->with('status', __('Success'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Career $career
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Exception
     */
    public function destroy(Career $career)
    {
        $this->authorize('delete', $career);
        $career->delete();

        if (request()->filled('redirect') && starts_with(request()->redirect, request()->root()) && !str_contains(request()->redirect, '/careers/'.$career->getKey()))
            $response = response()->redirectTo(request()->redirect);
        else
            $response = response()->redirectToRoute('careers.index');

        return $response->with('status', __('Success'));
    }
}
