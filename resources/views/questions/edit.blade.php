@extends('layouts.app')

@section('content-title', ucwords(__('questions.plural')))

@include('models.edit', [
  'panel_title' => ucwords(__('questions.singular')),
  'resource_route' => 'questions',
  'model_variable' => 'question',
  'model' => $question
])
