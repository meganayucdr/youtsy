<?php

namespace App\Http\Controllers;

use App\Role;
use App\User;
use Illuminate\Http\Request;

/**
 * UserController
 */
class UserController extends Controller
{
    /**
     * Relations
     * @param  \Illuminate\Http\Request|null $request
     * @param User $user
     * @return array
     */
    public static function relations(Request $request = null, User $user = null)
    {
        return [
            'user' => [
                'belongsToMany' => [], // also for morphToMany
                'hasMany' => [
                    //[ 'name' => 'children', 'label' => ucwords(__('users.children')) ],
                ], // also for morphMany, hasManyThrough
                'hasOne' => [
                    //[ 'name' => 'child', 'label' => ucwords(__('users.child')) ],
                ], // also for morphOne
            ]
        ];
    }

    /**
     * Visibles
     * @param  \Illuminate\Http\Request|null $request
     * @param User $user
     * @return array
     */
    public static function visibles(Request $request = null, User $user = null)
    {
        return [
            'index' => [
                'user' => [
                    //[ 'name' => 'parent', 'label' => ucwords(__('users.parent')), 'column' => 'name' ], // Only support belongsTo, hasOne
                    [ 'name' => 'name', 'label' => ucwords(__('users.name')) ],
                    [ 'name' => 'email', 'label' => ucwords(__('users.email')) ],
                    [ 'name' => 'role', 'label' => ucwords(__('users.role')), 'column' => 'role_id' ],
                ]
            ],
            'show' => [
                'user' => [
                    //[ 'name' => 'parent', 'label' => ucwords(__('users.parent')), 'column' => 'name' ], // Only support belongsTo, hasOne
                    [ 'name' => 'name', 'label' => ucwords(__('users.name')) ],
                    [ 'name' => 'email', 'label' => ucwords(__('users.email')) ],
                    [ 'name' => 'role', 'label' => ucwords(__('users.role')), 'column' => 'roles.role' ],
                ]
            ]
        ];
    }

    /**
     * Fields
     * @param  \Illuminate\Http\Request|null $request
     * @param User $user
     * @return array
     */
    public static function fields(Request $request = null, User $user = null)
    {
        return [
            'create' => [
                'user' => [
                    //[ 'field' => 'select', 'name' => 'parent_id', 'label' => ucwords(__('users.parent')), 'required' => true, 'options' => \App\Parent::filter()->get()->map(function ($parent) {
                    //    return [ 'value' => $parent->id, 'text' => $parent->name ];
                    //})->prepend([ 'value' => '', 'text' => '-' ])->toArray() ],
                    [ 'field' => 'input', 'type' => 'text', 'name' => 'name', 'label' => ucwords(__('users.name')), 'required' => true ],
                    [ 'field' => 'input', 'type' => 'email', 'name' => 'email', 'label' => ucwords(__('users.email')), 'required' => true ],
                    [ 'field' => 'input', 'type' => 'password', 'name' => 'password', 'label' => ucwords(__('users.password')), 'required' => true ],
                    [ 'field' => 'input', 'type' => 'password', 'name' => 'password_confirmation', 'label' => ucwords(__('users.password_confirmation')), 'required' => true ],
                    [ 'field' => 'select', 'name' => 'role_id', 'label' => ucwords(__('users.role')), 'required' => true, 'options' => \App\Role::filter()->get()->map(function ($parent) {
                        return [ 'value' => $parent->id, 'text' => $parent->role ];
                    })->prepend([ 'value' => '', 'text' => '-' ])->toArray() ],
                ]
            ],
            'edit' => [
                'user' => [
                    //[ 'field' => 'select', 'name' => 'parent_id', 'label' => ucwords(__('users.parent')), 'options' => \App\Parent::filter()->get()->map(function ($parent) {
                    //    return [ 'value' => $parent->id, 'text' => $parent->name ];
                    //})->prepend([ 'value' => '', 'text' => '-' ])->toArray() ],
                    [ 'field' => 'input', 'type' => 'text', 'name' => 'name', 'label' => ucwords(__('users.name')) ],
                    [ 'field' => 'input', 'type' => 'email', 'name' => 'email', 'label' => ucwords(__('users.email'))],
                    [ 'field' => 'select', 'name' => 'role_id', 'label' => ucwords(__('users.role')), 'required' => true, 'options' => \App\Role::filter()->get()->map(function ($parent) {
                        return [ 'value' => $parent->id, 'text' => $parent->role ];
                    })->prepend([ 'value' => '', 'text' => '-' ])->toArray() ],
                ]
            ]
        ];
    }

    /**
     * Rules
     * @param  \Illuminate\Http\Request|null $request
     * @param User $user
     * @return array
     */
    public static function rules(Request $request = null, User $user = null)
    {
        return [
            'store' => [
                //'parent_id' => 'required|exists:parents,id',
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:6|confirmed',
            ],
            'update' => [
                //'parent_id' => 'exists:parents,id',
                'name' => 'string|max:255',
                'email' => 'email|unique:users,email,' . ($user ? $user->getKey() : 'NULL') . ',id',
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
        $users = User::filter()
            ->paginate()->appends(request()->query());
        $this->authorize('index', 'App\User');

        return response()->view('users.index', [
            'users' => $users,
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
        $this->authorize('create', 'App\User');

        return response()->view('users.create', [
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
        $this->authorize('create', 'App\User');
        $request->validate(self::rules($request)['store']);

        $user = new User;
        foreach (self::rules($request)['store'] as $key => $value) {
            if (str_contains($value, [ 'file', 'image', 'mimetypes', 'mimes' ])) {
                if ($request->hasFile($key)) {
                    $user->{$key} = $request->file($key)->store('users');
                } elseif ($request->exists($key)) {
                    $user->{$key} = $request->{$key};
                }
            } elseif ($request->exists($key)) {
                $user->{$key} = $request->{$key};
            }
        }

        $role = Role::find($request->role_id);
        $user->role()->associate($role);

        $user->save();

        if (request()->filled('redirect') && starts_with(request()->redirect, request()->root()))
            $response = response()->redirectTo(request()->redirect);
        else
            $response = response()->redirectToRoute('users.show', $user->getKey());

        return $response->withInput([ $user->getForeignKey() => $user->getKey() ])
            ->with('status', __('Success'));
    }

    /**
     * Display the specified resource.
     *
     * @param User $user
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(User $user)
    {
        $this->authorize('view', $user);

        return response()->view('users.show', [
            'user' => $user,
            'relations' => self::relations(request(), $user),
            'visibles' => self::visibles(request(), $user)['show'],
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param User $user
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(User $user)
    {
        $this->authorize('update', $user);

        return response()->view('users.edit', [
            'user' => $user,
            'fields' => self::fields(request(), $user)['edit']
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param User $user
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Request $request, User $user)
    {
        $this->authorize('update', $user);
        $request->validate(self::rules($request, $user)['update']);

        foreach (self::rules($request, $user)['update'] as $key => $value) {
            if (str_contains($value, [ 'file', 'image', 'mimetypes', 'mimes' ])) {
                if ($request->hasFile($key)) {
                    $user->{$key} = $request->file($key)->store('users');
                } elseif ($request->exists($key)) {
                    $user->{$key} = $request->{$key};
                }
            } elseif ($request->exists($key)) {
                $user->{$key} = $request->{$key};
            }
        }

        $role = Role::find($request->role_id);
        $user->role()->associate($role);

        $user->save();

        if (request()->filled('redirect') && starts_with(request()->redirect, request()->root()))
            $response = response()->redirectTo(request()->redirect);
        else
            $response = response()->redirectToRoute('users.show', $user->getKey());

        return $response->withInput([ $user->getForeignKey() => $user->getKey() ])
            ->with('status', __('Success'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Exception
     */
    public function destroy(User $user)
    {
        $this->authorize('delete', $user);
        $user->delete();

        if (request()->filled('redirect') && starts_with(request()->redirect, request()->root()) && !str_contains(request()->redirect, '/users/'.$user->getKey()))
            $response = response()->redirectTo(request()->redirect);
        else
            $response = response()->redirectToRoute('users.index');

        return $response->with('status', __('Success'));
    }
}
