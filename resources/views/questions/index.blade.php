@extends('layouts.app')

@section('content-title', ucwords(__('questions.plural')))

@include('models.index', [
  'col_class' => 'col-md-8 col-md-offset-2 offset-md-2',
  'panel_title' => ucwords(__('questions.plural')),
  'resource_route' => 'questions',
  'model_variable' => 'question',
  'model_class' => \App\Question::class,
  'models' => $questions,
  'action_buttons_view' => 'generator::components.models.index.action_buttons',
])
