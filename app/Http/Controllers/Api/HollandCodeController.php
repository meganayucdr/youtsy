<?php

namespace App\Http\Controllers\Api;

use App\HollandCode;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\Resource;

/**
 * HollandCodeController
 * @extends Controller
 */
class HollandCodeController extends Controller
{
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
        $holland_codes = HollandCode::filter()
            ->paginate()->appends(request()->query());
        $this->authorize('index', 'App\HollandCode');

        return Resource::collection($holland_codes);
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

        return (new Resource($holland_code))->response()->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     *
     * @param HollandCode $holland_code
     * @return Resource
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(HollandCode $holland_code)
    {
        $this->authorize('view', $holland_code);

        return new Resource($holland_code);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param HollandCode $holland_code
     * @return Resource
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

        return new Resource($holland_code);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param HollandCode $holland_code
     * @return Resource
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Exception
     */
    public function destroy(HollandCode $holland_code)
    {
        $this->authorize('delete', $holland_code);
        $holland_code->delete();

        return new Resource($holland_code);
    }
}
