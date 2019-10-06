<?php

namespace App\Http\Controllers\Role;

use App\User;
use App\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\RoleController;

/**
 * UserController
 */
class UserController extends Controller
{
    /**
     * Relations
     * @param  \Illuminate\Http\Request|null $request
     * @param Role $role
     * @param User $user
     * @return array
     */
    public static function relations(Request $request = null, Role $role = null, User $user = null)
    {
        return [
            'role' => RoleController::relations($request, $role)['role'],
            'user' => [
                'belongsToMany' => [], // also for morphToMany
                'hasMany' => [], // also for morphMany, hasManyThrough
                'hasOne' => [], // also for morphOne
            ]
        ];
    }

    /**
     * Visibles
     * @param  \Illuminate\Http\Request|null $request
     * @param Role $role
     * @param User $user
     * @return array
     */
    public static function visibles(Request $request = null, Role $role = null, User $user = null)
    {
        return [
            'parent' => [
                'role' => RoleController::visibles($request, $role)['show']['role']
            ],
            'index' => [
                'user' => [
                    [ 'name' => 'name', 'label' => ucwords(__('users.name')) ],
                    [ 'name' => 'email', 'label' => ucwords(__('users.email')) ],
                ]
            ],
            'show' => [
                'user' => [
                    [ 'name' => 'name', 'label' => ucwords(__('users.name')) ],
                    [ 'name' => 'email', 'label' => ucwords(__('users.email')) ],
                ]
            ]
        ];
    }

    /**
     * Fields
     * @param  \Illuminate\Http\Request|null $request
     * @param Role $role
     * @param User $user
     * @return array
     */
    public static function fields(Request $request = null, Role $role = null, User $user = null)
    {
        return [
            'create' => [
                'user' => [
                    [ 'field' => 'input', 'type' => 'text', 'name' => 'name', 'label' => ucwords(__('users.name')), 'required' => true ],
                    [ 'field' => 'input', 'type' => 'email', 'name' => 'email', 'label' => ucwords(__('users.email')), 'required' => true ],
                    [ 'field' => 'input', 'type' => 'password', 'name' => 'password', 'label' => ucwords(__('users.password')), 'required' => true ],
                    [ 'field' => 'input', 'type' => 'password', 'name' => 'password_confirmation', 'label' => ucwords(__('users.password_confirmation')), 'required' => true ],
                ]
            ],
            'edit' => [
                'user' => [
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
     * @param Role $role
     * @param User $user
     * @return array
     */
    public static function rules(Request $request = null, Role $role = null, User $user = null)
    {
        return [
            'store' => [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:6|confirmed',
            ],
            'update' => [
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
     * @param Role $role
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(Role $role)
    {
        $users = User::filter()
            ->where((new User)->qualifyColumn($role->getForeignKey()), $role->getKey())
            ->paginate()->appends(request()->query());
        $this->authorize('index', [ 'App\User', $role ]);

        return response()->view('roles.users.index', [
            'role' => $role,
            'users' => $users,
            'relations' => self::relations(request(), $role),
            'visibles' => array_merge(self::visibles(request(), $role)['parent'], self::visibles(request(), $role)['index']),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Role $role
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create(Role $role)
    {
        $this->authorize('create', [ 'App\User', $role ]);

        return response()->view('roles.users.create', [
            'role' => $role,
            'relations' => self::relations(request(), $role),
            'visibles' => self::visibles(request(), $role)['parent'],
            'fields' => self::fields(request(), $role)['create']
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Role $role
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(Request $request, Role $role)
    {
        $this->authorize('create', [ 'App\User', $role ]);
        $request->validate(self::rules($request, $role)['store']);

        $user = new User;
        foreach (self::rules($request, $role)['store'] as $key => $value) {
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

        if( $request->filled('password'))    {
            $user->password = bcrypt($request->password);
        }

        $user->role()->associate($role);
        $user->save();

        if (request()->filled('redirect') && starts_with(request()->redirect, request()->root()))
            $response = response()->redirectTo(request()->redirect);
        else
            $response = response()->redirectToRoute('roles.users.show', [ $role->getKey(), $user->getKey() ]);

        return $response->withInput([
            $role->getForeignKey() => $role->getKey(),
            $user->getForeignKey() => $user->getKey(),
        ])->with('status', __('Success'));
    }

    /**
     * Display the specified resource.
     *
     * @param Role $role
     * @param User $user
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(Role $role, User $user)
    {
        $role->users()->findOrFail($user->getKey());
        $this->authorize('view', [ $user, $role ]);

        return response()->view('roles.users.show', [
            'role' => $role,
            'user' => $user,
            'relations' => self::relations(request(), $role, $user),
            'visibles' => array_merge(self::visibles(request(), $role, $user)['parent'], self::visibles(request(), $role, $user)['show'])
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Role $role
     * @param User $user
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(Role $role, User $user)
    {
        $role->users()->findOrFail($user->getKey());
        $this->authorize('update', [ $user, $role ]);

        return response()->view('roles.users.edit', [
            'role' => $role,
            'user' => $user,
            'relations' => self::relations(request(), $role, $user),
            'visibles' => self::visibles(request(), $role, $user)['parent'],
            'fields' => self::fields(request(), $role, $user)['edit']
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Role $role
     * @param User $user
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Request $request, Role $role, User $user)
    {
        $role->users()->findOrFail($user->getKey());

        $this->authorize('update', [ $user, $role ]);
        $request->validate(self::rules($request, $role, $user)['update']);

        foreach (self::rules($request, $role, $user)['update'] as $key => $value) {
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
        $user->role()->associate($role);
        $user->save();

        if (request()->filled('redirect') && starts_with(request()->redirect, request()->root()))
            $response = response()->redirectTo(request()->redirect);
        else
            $response = response()->redirectToRoute('roles.users.show', [ $role->getKey(), $user->getKey() ]);

        return $response->withInput([
            $role->getForeignKey() => $role->getKey(),
            $user->getForeignKey() => $user->getKey(),
        ])->with('status', __('Success'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Role  $role
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Exception
     */
    public function destroy(Role $role, User $user)
    {
        $role->users()->findOrFail($user->getKey());
        $this->authorize('delete', [ $user, $role ]);
        $user->delete();

        if (request()->filled('redirect') && starts_with(request()->redirect, request()->root()) && !str_contains(request()->redirect, '/'.array_last(explode('.', 'roles.users')).'/'.$user->getKey()))
            $response = response()->redirectTo(request()->redirect);
        else
            $response = response()->redirectToRoute('roles.users.index', $role->getKey());

        return $response->with('status', __('Success'));
    }
}
