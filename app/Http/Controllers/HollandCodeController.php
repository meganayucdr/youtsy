<?php

namespace App\Http\Controllers;

use App\HollandCode;
use Illuminate\Http\Request;

/**
 * HollandCodeController
 */
class HollandCodeController extends Controller
{
    /**
     * Relations
     * @param  \Illuminate\Http\Request|null $request
     * @param HollandCode $holland_code
     * @return array
     */
    public static function relations(Request $request = null, HollandCode $holland_code = null)
    {
        return [
            'holland_code' => [
                'belongsToMany' => [
                    [ 'name' => 'careers', 'label' => ucwords(__('holland_codes.careers')) ],
                ], // also for morphToMany
                'hasMany' => [
                ], // also for morphMany, hasManyThrough
                'hasOne' => [
                    //[ 'name' => 'child', 'label' => ucwords(__('holland_codes.child')) ],
                ], // also for morphOne
            ]
        ];
    }

    /**
     * Visibles
     * @param  \Illuminate\Http\Request|null $request
     * @param HollandCode $holland_code
     * @return array
     */
    public static function visibles(Request $request = null, HollandCode $holland_code = null)
    {
        return [
            'index' => [
                'holland_code' => [
                    //[ 'name' => 'parent', 'label' => ucwords(__('holland_codes.parent')), 'column' => 'name' ], // Only support belongsTo, hasOne
                    [ 'name' => 'code', 'label' => ucwords(__('holland_codes.code')) ],
                    [ 'name' => 'name', 'label' => ucwords(__('holland_codes.name')) ]
                ]
            ],
            'show' => [
                'holland_code' => [
                    //[ 'name' => 'parent', 'label' => ucwords(__('holland_codes.parent')), 'column' => 'name' ], // Only support belongsTo, hasOne
                    [ 'name' => 'code', 'label' => ucwords(__('holland_codes.code')) ],
                    [ 'name' => 'name', 'label' => ucwords(__('holland_codes.name')) ],
                    [ 'name' => 'explanation', 'label' => ucwords(__('holland_codes.explanation')) ],
                ]
            ]
        ];
    }

    /**
     * Fields
     * @param  \Illuminate\Http\Request|null $request
     * @param HollandCode $holland_code
     * @return array
     */
    public static function fields(Request $request = null, HollandCode $holland_code = null)
    {
        return [
            'create' => [
                'holland_code' => [
                    //[ 'field' => 'select', 'name' => 'parent_id', 'label' => ucwords(__('holland_codes.parent')), 'required' => true, 'options' => \App\Parent::filter()->get()->map(function ($parent) {
                    //    return [ 'value' => $parent->id, 'text' => $parent->name ];
                    //})->prepend([ 'value' => '', 'text' => '-' ])->toArray() ],
                    [ 'field' => 'input', 'type' => 'text', 'name' => 'code', 'label' => ucwords(__('holland_codes.code')), 'required' => true ],
                    [ 'field' => 'input', 'type' => 'text', 'name' => 'name', 'label' => ucwords(__('holland_codes.name')), 'required' => true ],
                    [ 'field' => 'textarea', 'type' => 'text', 'name' => 'explanation', 'label' => ucwords(__('holland_codes.explanation')), 'required' => true ],
                ]
            ],
            'edit' => [
                'holland_code' => [
                    //[ 'field' => 'select', 'name' => 'parent_id', 'label' => ucwords(__('holland_codes.parent')), 'options' => \App\Parent::filter()->get()->map(function ($parent) {
                    //    return [ 'value' => $parent->id, 'text' => $parent->name ];
                    //})->prepend([ 'value' => '', 'text' => '-' ])->toArray() ],
                    [ 'field' => 'input', 'type' => 'text', 'name' => 'code', 'label' => ucwords(__('holland_codes.code')) ],
                    [ 'field' => 'input', 'type' => 'text', 'name' => 'name', 'label' => ucwords(__('holland_codes.name')) ],
                    [ 'field' => 'textarea', 'type' => 'text', 'name' => 'explanation', 'label' => ucwords(__('holland_codes.explanation')) ],
                ]
            ]
        ];
    }

    /**
     * Rules
     * @param  \Illuminate\Http\Request|null $request
     * @param HollandCode $holland_code
     * @return array
     */
    public static function rules(Request $request = null, HollandCode $holland_code = null)
    {
        return [
            'store' => [
                //'parent_id' => 'required|exists:parents,id',
                'code' => 'required|string|max:255',
                'name' => 'required|string|max:255',
                'explanation' => 'string',
            ],
            'update' => [
                //'parent_id' => 'exists:parents,id',
                'code' => 'string|max:255',
                'name' => 'string|max:255',
                'explanation' => 'string',
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
        $holland_codes = HollandCode::filter()
            ->paginate()->appends(request()->query());
        $this->authorize('index', 'App\HollandCode');

        return response()->view('holland_codes.index', [
            'holland_codes' => $holland_codes,
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
        $this->authorize('create', 'App\HollandCode');

        return response()->view('holland_codes.create', [
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
        $this->authorize('create', 'App\HollandCode');
        $request->validate(self::rules($request)['store']);

        $holland_code = new HollandCode;
        foreach (self::rules($request)['store'] as $key => $value) {
            if (str_contains($value, [ 'file', 'image', 'mimetypes', 'mimes' ])) {
                if ($request->hasFile($key)) {
                    $holland_code->{$key} = $request->file($key)->store('holland_codes');
                } elseif ($request->exists($key)) {
                    $holland_code->{$key} = $request->{$key};
                }
            } elseif ($request->exists($key)) {
                $holland_code->{$key} = $request->{$key};
            }
        }
        $holland_code->save();

        if (request()->filled('redirect') && starts_with(request()->redirect, request()->root()))
            $response = response()->redirectTo(request()->redirect);
        else
            $response = response()->redirectToRoute('holland_codes.show', $holland_code->getKey());

        return $response->withInput([ $holland_code->getForeignKey() => $holland_code->getKey() ])
            ->with('status', __('Success'));
    }

    /**
     * Display the specified resource.
     *
     * @param HollandCode $holland_code
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(HollandCode $holland_code)
    {
        $this->authorize('view', $holland_code);

        return response()->view('holland_codes.show', [
            'holland_code' => $holland_code,
            'relations' => self::relations(request(), $holland_code),
            'visibles' => self::visibles(request(), $holland_code)['show'],
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param HollandCode $holland_code
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(HollandCode $holland_code)
    {
        $this->authorize('update', $holland_code);

        return response()->view('holland_codes.edit', [
            'holland_code' => $holland_code,
            'fields' => self::fields(request(), $holland_code)['edit']
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param HollandCode $holland_code
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Request $request, HollandCode $holland_code)
    {
        $this->authorize('update', $holland_code);
        $request->validate(self::rules($request, $holland_code)['update']);

        foreach (self::rules($request, $holland_code)['update'] as $key => $value) {
            if (str_contains($value, [ 'file', 'image', 'mimetypes', 'mimes' ])) {
                if ($request->hasFile($key)) {
                    $holland_code->{$key} = $request->file($key)->store('holland_codes');
                } elseif ($request->exists($key)) {
                    $holland_code->{$key} = $request->{$key};
                }
            } elseif ($request->exists($key)) {
                $holland_code->{$key} = $request->{$key};
            }
        }
        $holland_code->save();

        if (request()->filled('redirect') && starts_with(request()->redirect, request()->root()))
            $response = response()->redirectTo(request()->redirect);
        else
            $response = response()->redirectToRoute('holland_codes.show', $holland_code->getKey());

        return $response->withInput([ $holland_code->getForeignKey() => $holland_code->getKey() ])
            ->with('status', __('Success'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param HollandCode $holland_code
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Exception
     */
    public function destroy(HollandCode $holland_code)
    {
        $this->authorize('delete', $holland_code);
        $holland_code->delete();

        if (request()->filled('redirect') && starts_with(request()->redirect, request()->root()) && !str_contains(request()->redirect, '/holland_codes/'.$holland_code->getKey()))
            $response = response()->redirectTo(request()->redirect);
        else
            $response = response()->redirectToRoute('holland_codes.index');

        return $response->with('status', __('Success'));
    }
}
