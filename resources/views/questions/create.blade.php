@extends('layouts.app')

@section('content-title', ucwords(__('questions.plural')))

@include('models.create', [
  'panel_title' => ucwords(__('questions.singular')),
  'resource_route' => 'questions',
  'model_variable' => 'question',
  'model_class' => \App\Question::class,
])
