@extends('layouts.app')

@section('content-title', ucwords(__('holland_tests.plural')))

@include('models.index', [
  'col_class' => 'col-md-8 col-md-offset-2 offset-md-2',
  'panel_title' => ucwords(__('holland_tests.plural')),
  'resource_route' => 'holland_tests',
  'model_variable' => 'holland_test',
  'model_class' => \App\HollandTest::class,
  'models' => $holland_tests,
  'action_buttons_view' => 'generator::components.models.index.action_buttons',
])
