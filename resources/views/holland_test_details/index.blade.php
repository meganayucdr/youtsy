@extends('layouts.app')

@section('content-title', ucwords(__('holland_test_details.plural')))

@include('models.index', [
  'col_class' => 'col-md-8 col-md-offset-2 offset-md-2',
  'panel_title' => ucwords(__('holland_test_details.plural')),
  'resource_route' => 'holland_test_details',
  'model_variable' => 'holland_test_detail',
  'model_class' => \App\HollandTestDetail::class,
  'models' => $holland_test_details
])
