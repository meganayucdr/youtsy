<?php

namespace App\Http\Controllers;

use App\Career;
use App\HollandCode;
use Illuminate\Http\Request;
use App\Fields\Select2Ajax;

/**
 * CareerController
 */
class CareerController extends Controller
{
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
                'holland_code_id' => 'required|exists:holland_code,id',
                'name' => 'required|string|max:255',
            ],
            'update' => [
                //'parent_id' => 'exists:parents,id',
                'holland_code_id' => 'exists:holland_code,id',
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

        return view('careers/index', ['careers' => $careers]);
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
        $holland_codes = HollandCode::all();
        return view('careers/create', ['holland_codes' => $holland_codes->pluck('name', 'id')]);
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
        
        $career = new Career; 

        $career->name = $request->career_name;

        $career->save();
        $career->hollandCodes()->attach($request->holland_codes);
        return redirect()->route('careers.index');
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
    }
}
