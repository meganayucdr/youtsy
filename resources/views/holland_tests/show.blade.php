@extends('layouts.app')

@section('content-title', ucwords(__('holland_tests.plural')))

@include('models.show', [
  'panel_title' => ucwords(__('holland_tests.singular')),
  'resource_route' => 'holland_tests',
  'model_variable' => 'holland_test',
  'model' => $holland_test
])
