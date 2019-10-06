<?php

namespace App\Http\Controllers\Api;

use App\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\Resource;

/**
 * RoleController
 * @extends Controller
 */
class RoleController extends Controller
{
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
                'role' => 'required|string|max:255',
            ],
            'update' => [
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
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index()
    {
        $roles = Role::filter()
            ->paginate()->appends(request()->query());
        $this->authorize('index', 'App\Role');

        return Resource::collection($roles);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
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

        return (new Resource($role))->response()->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     *
     * @param Role $role
     * @return Resource
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(Role $role)
    {
        $this->authorize('view', $role);

        return new Resource($role);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Role $role
     * @return Resource
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

        return new Resource($role);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Role $role
     * @return Resource
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Exception
     */
    public function destroy(Role $role)
    {
        $this->authorize('delete', $role);
        $role->delete();

        return new Resource($role);
    }
}
