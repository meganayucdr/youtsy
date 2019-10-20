<?php

namespace App\Http\Controllers;

use App\Question;
use Illuminate\Http\Request;

/**
 * QuestionController
 */
class QuestionController extends Controller
{
    /**
     * Relations
     * @param  \Illuminate\Http\Request|null $request
     * @param Question $question
     * @return array
     */
    public static function relations(Request $request = null, Question $question = null)
    {
        return [
            'question' => [
                'belongsToMany' => [], // also for morphToMany
                'hasMany' => [
                    //[ 'name' => 'children', 'label' => ucwords(__('questions.children')) ],
                ], // also for morphMany, hasManyThrough
                'hasOne' => [
                    //[ 'name' => 'child', 'label' => ucwords(__('questions.child')) ],
                ], // also for morphOne
            ]
        ];
    }

    /**
     * Visibles
     * @param  \Illuminate\Http\Request|null $request
     * @param Question $question
     * @return array
     */
    public static function visibles(Request $request = null, Question $question = null)
    {
        return [
            'index' => [
                'question' => [
                    //[ 'name' => 'parent', 'label' => ucwords(__('questions.parent')), 'column' => 'name' ], // Only support belongsTo, hasOne
                    [ 'name' => 'question', 'label' => ucwords(__('questions.question')) ],
                    [ 'name' => 'holland_code', 'label' => ucwords(__('questions.holland_code')), 'column' => 'name' ],
                ]
            ],
            'show' => [
                'question' => [
                    //[ 'name' => 'parent', 'label' => ucwords(__('questions.parent')), 'column' => 'name' ], // Only support belongsTo, hasOne
                    [ 'name' => 'name', 'label' => ucwords(__('questions.name')) ],
                ]
            ]
        ];
    }

    /**
     * Fields
     * @param  \Illuminate\Http\Request|null $request
     * @param Question $question
     * @return array
     */
    public static function fields(Request $request = null, Question $question = null)
    {
        return [
            'create' => [
                'question' => [
                    //[ 'field' => 'select', 'name' => 'parent_id', 'label' => ucwords(__('questions.parent')), 'required' => true, 'options' => \App\Parent::filter()->get()->map(function ($parent) {
                    //    return [ 'value' => $parent->id, 'text' => $parent->name ];
                    //})->prepend([ 'value' => '', 'text' => '-' ])->toArray() ],
                    [ 'field' => 'input', 'type' => 'text', 'name' => 'name', 'label' => ucwords(__('questions.name')), 'required' => true ],
                ]
            ],
            'edit' => [
                'question' => [
                    //[ 'field' => 'select', 'name' => 'parent_id', 'label' => ucwords(__('questions.parent')), 'options' => \App\Parent::filter()->get()->map(function ($parent) {
                    //    return [ 'value' => $parent->id, 'text' => $parent->name ];
                    //})->prepend([ 'value' => '', 'text' => '-' ])->toArray() ],
                    [ 'field' => 'input', 'type' => 'text', 'name' => 'name', 'label' => ucwords(__('questions.name')) ],
                ]
            ]
        ];
    }

    /**
     * Rules
     * @param  \Illuminate\Http\Request|null $request
     * @param Question $question
     * @return array
     */
    public static function rules(Request $request = null, Question $question = null)
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
        $questions = Question::filter()
            ->paginate()->appends(request()->query());
        $this->authorize('index', 'App\Question');

        return response()->view('questions.index', [
            'questions' => $questions,
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
        $this->authorize('create', 'App\Question');

        return response()->view('questions.create', [
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
        $this->authorize('create', 'App\Question');
        $request->validate(self::rules($request)['store']);

        $question = new Question;
        foreach (self::rules($request)['store'] as $key => $value) {
            if (str_contains($value, [ 'file', 'image', 'mimetypes', 'mimes' ])) {
                if ($request->hasFile($key)) {
                    $question->{$key} = $request->file($key)->store('questions');
                } elseif ($request->exists($key)) {
                    $question->{$key} = $request->{$key};
                }
            } elseif ($request->exists($key)) {
                $question->{$key} = $request->{$key};
            }
        }
        $question->save();

        if (request()->filled('redirect') && starts_with(request()->redirect, request()->root()))
            $response = response()->redirectTo(request()->redirect);
        else
            $response = response()->redirectToRoute('questions.show', $question->getKey());

        return $response->withInput([ $question->getForeignKey() => $question->getKey() ])
            ->with('status', __('Success'));
    }

    /**
     * Display the specified resource.
     *
     * @param Question $question
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(Question $question)
    {
        $this->authorize('view', $question);

        return response()->view('questions.show', [
            'question' => $question,
            'relations' => self::relations(request(), $question),
            'visibles' => self::visibles(request(), $question)['show'],
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Question $question
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(Question $question)
    {
        $this->authorize('update', $question);

        return response()->view('questions.edit', [
            'question' => $question,
            'fields' => self::fields(request(), $question)['edit']
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Question $question
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Request $request, Question $question)
    {
        $this->authorize('update', $question);
        $request->validate(self::rules($request, $question)['update']);

        foreach (self::rules($request, $question)['update'] as $key => $value) {
            if (str_contains($value, [ 'file', 'image', 'mimetypes', 'mimes' ])) {
                if ($request->hasFile($key)) {
                    $question->{$key} = $request->file($key)->store('questions');
                } elseif ($request->exists($key)) {
                    $question->{$key} = $request->{$key};
                }
            } elseif ($request->exists($key)) {
                $question->{$key} = $request->{$key};
            }
        }
        $question->save();

        if (request()->filled('redirect') && starts_with(request()->redirect, request()->root()))
            $response = response()->redirectTo(request()->redirect);
        else
            $response = response()->redirectToRoute('questions.show', $question->getKey());

        return $response->withInput([ $question->getForeignKey() => $question->getKey() ])
            ->with('status', __('Success'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Question $question
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Exception
     */
    public function destroy(Question $question)
    {
        $this->authorize('delete', $question);
        $question->delete();

        if (request()->filled('redirect') && starts_with(request()->redirect, request()->root()) && !str_contains(request()->redirect, '/questions/'.$question->getKey()))
            $response = response()->redirectTo(request()->redirect);
        else
            $response = response()->redirectToRoute('questions.index');

        return $response->with('status', __('Success'));
    }
}
