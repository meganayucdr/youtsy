<?php

namespace App\Http\Controllers;

use App\Option;
use Illuminate\Http\Request;

/**
 * OptionController
 */
class OptionController extends Controller
{
    /**
     * Relations
     * @param  \Illuminate\Http\Request|null $request
     * @param Option $option
     * @return array
     */
    public static function relations(Request $request = null, Option $option = null)
    {
        return [
            'option' => [
                'belongsToMany' => [], // also for morphToMany
                'hasMany' => [
                    //[ 'name' => 'children', 'label' => ucwords(__('options.children')) ],
                ], // also for morphMany, hasManyThrough
                'hasOne' => [
                    //[ 'name' => 'child', 'label' => ucwords(__('options.child')) ],
                ], // also for morphOne
            ]
        ];
    }

    /**
     * Visibles
     * @param  \Illuminate\Http\Request|null $request
     * @param Option $option
     * @return array
     */
    public static function visibles(Request $request = null, Option $option = null)
    {
        return [
            'index' => [
                'option' => [
                    //[ 'name' => 'parent', 'label' => ucwords(__('options.parent')), 'column' => 'name' ], // Only support belongsTo, hasOne
                    [ 'name' => 'option', 'label' => ucwords(__('options.option')) ],
                    [ 'name' => 'weight', 'label' => ucwords(__('options.weight')) ],
                ]
            ],
            'show' => [
                'option' => [
                    //[ 'name' => 'parent', 'label' => ucwords(__('options.parent')), 'column' => 'name' ], // Only support belongsTo, hasOne
                    [ 'name' => 'option', 'label' => ucwords(__('options.option')) ],
                    [ 'name' => 'weight', 'label' => ucwords(__('options.weight')) ],
                ]
            ]
        ];
    }

    /**
     * Fields
     * @param  \Illuminate\Http\Request|null $request
     * @param Option $option
     * @return array
     */
    public static function fields(Request $request = null, Option $option = null)
    {
        return [
            'create' => [
                'option' => [
                    //[ 'field' => 'select', 'name' => 'parent_id', 'label' => ucwords(__('options.parent')), 'required' => true, 'options' => \App\Parent::filter()->get()->map(function ($parent) {
                    //    return [ 'value' => $parent->id, 'text' => $parent->name ];
                    //})->prepend([ 'value' => '', 'text' => '-' ])->toArray() ],
                    [ 'field' => 'input', 'type' => 'text', 'name' => 'option', 'label' => ucwords(__('options.option')), 'required' => true ],
                    [ 'field' => 'input', 'type' => 'text', 'name' => 'weight', 'label' => ucwords(__('options.weight')), 'required' => true ],
                ]
            ],
            'edit' => [
                'option' => [
                    //[ 'field' => 'select', 'name' => 'parent_id', 'label' => ucwords(__('options.parent')), 'options' => \App\Parent::filter()->get()->map(function ($parent) {
                    //    return [ 'value' => $parent->id, 'text' => $parent->name ];
                    //})->prepend([ 'value' => '', 'text' => '-' ])->toArray() ],
                    [ 'field' => 'input', 'type' => 'text', 'name' => 'option', 'label' => ucwords(__('options.option')) ],
                    [ 'field' => 'input', 'type' => 'text', 'name' => 'weight', 'label' => ucwords(__('options.weight')) ],
                ]
            ]
        ];
    }

    /**
     * Rules
     * @param  \Illuminate\Http\Request|null $request
     * @param Option $option
     * @return array
     */
    public static function rules(Request $request = null, Option $option = null)
    {
        return [
            'store' => [
                //'parent_id' => 'required|exists:parents,id',
                'option' => 'required|string|max:255',
                'weight' => 'required|numeric'
            ],
            'update' => [
                //'parent_id' => 'exists:parents,id',
                'option' => 'string|max:255',
                'weight' => 'numeric'
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
        $options = Option::filter()
            ->paginate()->appends(request()->query());
        $this->authorize('index', 'App\Option');

        return response()->view('options.index', [
            'options' => $options,
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
        $this->authorize('create', 'App\Option');

        return response()->view('options.create', [
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
        $this->authorize('create', 'App\Option');
        $request->validate(self::rules($request)['store']);

        $option = new Option;
        foreach (self::rules($request)['store'] as $key => $value) {
            if (str_contains($value, [ 'file', 'image', 'mimetypes', 'mimes' ])) {
                if ($request->hasFile($key)) {
                    $option->{$key} = $request->file($key)->store('options');
                } elseif ($request->exists($key)) {
                    $option->{$key} = $request->{$key};
                }
            } elseif ($request->exists($key)) {
                $option->{$key} = $request->{$key};
            }
        }
        $option->save();

        if (request()->filled('redirect') && starts_with(request()->redirect, request()->root()))
            $response = response()->redirectTo(request()->redirect);
        else
            $response = response()->redirectToRoute('options.show', $option->getKey());

        return $response->withInput([ $option->getForeignKey() => $option->getKey() ])
            ->with('status', __('Success'));
    }

    /**
     * Display the specified resource.
     *
     * @param Option $option
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(Option $option)
    {
        $this->authorize('view', $option);

        return response()->view('options.show', [
            'option' => $option,
            'relations' => self::relations(request(), $option),
            'visibles' => self::visibles(request(), $option)['show'],
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Option $option
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(Option $option)
    {
        $this->authorize('update', $option);

        return response()->view('options.edit', [
            'option' => $option,
            'fields' => self::fields(request(), $option)['edit']
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Option $option
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Request $request, Option $option)
    {
        $this->authorize('update', $option);
        $request->validate(self::rules($request, $option)['update']);

        foreach (self::rules($request, $option)['update'] as $key => $value) {
            if (str_contains($value, [ 'file', 'image', 'mimetypes', 'mimes' ])) {
                if ($request->hasFile($key)) {
                    $option->{$key} = $request->file($key)->store('options');
                } elseif ($request->exists($key)) {
                    $option->{$key} = $request->{$key};
                }
            } elseif ($request->exists($key)) {
                $option->{$key} = $request->{$key};
            }
        }
        $option->save();

        if (request()->filled('redirect') && starts_with(request()->redirect, request()->root()))
            $response = response()->redirectTo(request()->redirect);
        else
            $response = response()->redirectToRoute('options.show', $option->getKey());

        return $response->withInput([ $option->getForeignKey() => $option->getKey() ])
            ->with('status', __('Success'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Option $option
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Exception
     */
    public function destroy(Option $option)
    {
        $this->authorize('delete', $option);
        $option->delete();

        if (request()->filled('redirect') && starts_with(request()->redirect, request()->root()) && !str_contains(request()->redirect, '/options/'.$option->getKey()))
            $response = response()->redirectTo(request()->redirect);
        else
            $response = response()->redirectToRoute('options.index');

        return $response->with('status', __('Success'));
    }
}
