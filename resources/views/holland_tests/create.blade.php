@extends('layouts.app')

@section('content-title', ucwords(__('holland_tests.plural')))

@include('models.create', [
  'panel_title' => ucwords(__('holland_tests.singular')),
  'resource_route' => 'holland_tests',
  'model_variable' => 'holland_test',
  'model_class' => \App\HollandTest::class,
])
