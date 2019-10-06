<?php

namespace App\Http\Controllers;

use App\Role;
use Illuminate\Http\Request;

/**
 * RoleController
 */
class RoleController extends Controller
{
    /**
     * Relations
     * @param  \Illuminate\Http\Request|null $request
     * @param Role $role
     * @return array
     */
    public static function relations(Request $request = null, Role $role = null)
    {
        return [
            'role' => [
                'belongsToMany' => [], // also for morphToMany
                'hasMany' => [
                    //[ 'name' => 'children', 'label' => ucwords(__('roles.children')) ],
                    [ 'name' => 'users', 'label' => ucwords(__('roles.users')) ]
                ], // also for morphMany, hasManyThrough
                'hasOne' => [
                    //[ 'name' => 'child', 'label' => ucwords(__('roles.child')) ],
                ], // also for morphOne
            ]
        ];
    }

    /**
     * Visibles
     * @param  \Illuminate\Http\Request|null $request
     * @param Role $role
     * @return array
     */
    public static function visibles(Request $request = null, Role $role = null)
    {
        return [
            'index' => [
                'role' => [
                    //[ 'name' => 'parent', 'label' => ucwords(__('roles.parent')), 'column' => 'name' ], // Only support belongsTo, hasOne
                    [ 'name' => 'role', 'label' => ucwords(__('roles.role')) ],
                ]
            ],
            'show' => [
                'role' => [
                    //[ 'name' => 'parent', 'label' => ucwords(__('roles.parent')), 'column' => 'name' ], // Only support belongsTo, hasOne
                    [ 'name' => 'role', 'label' => ucwords(__('roles.role')) ],
                ]
            ]
        ];
    }

    /**
     * Fields
     * @param  \Illuminate\Http\Request|null $request
     * @param Role $role
     * @return array
     */
    public static function fields(Request $request = null, Role $role = null)
    {
        return [
            'create' => [
                'role' => [
                    //[ 'field' => 'select', 'name' => 'parent_id', 'label' => ucwords(__('roles.parent')), 'required' => true, 'options' => \App\Parent::filter()->get()->map(function ($parent) {
                    //    return [ 'value' => $parent->id, 'text' => $parent->name ];
                    //})->prepend([ 'value' => '', 'text' => '-' ])->toArray() ],
                    [ 'field' => 'input', 'type' => 'text', 'name' => 'role', 'label' => ucwords(__('roles.role')), 'required' => true ],
                ]
            ],
            'edit' => [
                'role' => [
                    //[ 'field' => 'select', 'name' => 'parent_id', 'label' => ucwords(__('roles.parent')), 'options' => \App\Parent::filter()->get()->map(function ($parent) {
                    //    return [ 'value' => $parent->id, 'text' => $parent->name ];
                    //})->prepend([ 'value' => '', 'text' => '-' ])->toArray() ],
                    [ 'field' => 'input', 'type' => 'text', 'name' => 'role', 'label' => ucwords(__('roles.role')) ],
                ]
            ]
        ];
    }

    /**
     * Rules
     * @param  \Illuminate\Http\Request|null $request
     * @param Role $role
     * @return array
     */
    public static function rules(Request $request = null, Role $role = null)
    {
        return [
            'store' => [
                //'parent_id' => 'required|exists:parents,id',
                'role' => 'required|string|max:255',
            ],
            'update' => [
                //'parent_id' => 'exists:parents,id',
                'role' => 'string|max:255',
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
        $roles = Role::filter()
            ->paginate()->appends(request()->query());
        $this->authorize('index', 'App\Role');

        return response()->view('roles.index', [
            'roles' => $roles,
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
        $this->authorize('create', 'App\Role');

        return response()->view('roles.create', [
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
        $this->authorize('create', 'App\Role');
        $request->validate(self::rules($request)['store']);

        $role = new Role;
        foreach (self::rules($request)['store'] as $key => $value) {
            if (str_contains($value, [ 'file', 'image', 'mimetypes', 'mimes' ])) {
                if ($request->hasFile($key)) {
                    $role->{$key} = $request->file($key)->store('roles');
                } elseif ($request->exists($key)) {
                    $role->{$key} = $request->{$key};
                }
            } elseif ($request->exists($key)) {
                $role->{$key} = $request->{$key};
            }
        }
        $role->save();

        if (request()->filled('redirect') && starts_with(request()->redirect, request()->root()))
            $response = response()->redirectTo(request()->redirect);
        else
            $response = response()->redirectToRoute('roles.show', $role->getKey());

        return $response->withInput([ $role->getForeignKey() => $role->getKey() ])
            ->with('status', __('Success'));
    }

    /**
     * Display the specified resource.
     *
     * @param Role $role
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(Role $role)
    {
        $this->authorize('view', $role);

        return response()->view('roles.show', [
            'role' => $role,
            'relations' => self::relations(request(), $role),
            'visibles' => self::visibles(request(), $role)['show'],
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Role $role
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(Role $role)
    {
        $this->authorize('update', $role);

        return response()->view('roles.edit', [
            'role' => $role,
            'fields' => self::fields(request(), $role)['edit']
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Role $role
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Request $request, Role $role)
    {
        $this->authorize('update', $role);
        $request->validate(self::rules($request, $role)['update']);

        foreach (self::rules($request, $role)['update'] as $key => $value) {
            if (str_contains($value, [ 'file', 'image', 'mimetypes', 'mimes' ])) {
                if ($request->hasFile($key)) {
                    $role->{$key} = $request->file($key)->store('roles');
                } elseif ($request->exists($key)) {
                    $role->{$key} = $request->{$key};
                }
            } elseif ($request->exists($key)) {
                $role->{$key} = $request->{$key};
            }
        }
        $role->save();

        if (request()->filled('redirect') && starts_with(request()->redirect, request()->root()))
            $response = response()->redirectTo(request()->redirect);
        else
            $response = response()->redirectToRoute('roles.show', $role->getKey());

        return $response->withInput([ $role->getForeignKey() => $role->getKey() ])
            ->with('status', __('Success'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Role $role
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Exception
     */
    public function destroy(Role $role)
    {
        $this->authorize('delete', $role);
        $role->delete();

        if (request()->filled('redirect') && starts_with(request()->redirect, request()->root()) && !str_contains(request()->redirect, '/roles/'.$role->getKey()))
            $response = response()->redirectTo(request()->redirect);
        else
            $response = response()->redirectToRoute('roles.index');

        return $response->with('status', __('Success'));
    }
}
