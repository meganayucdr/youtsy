@extends('layouts.app')

@section('content-title', ucwords(__('holland_test_details.plural')))

@include('models.create', [
  'panel_title' => ucwords(__('holland_test_details.singular')),
  'resource_route' => 'holland_test_details',
  'model_variable' => 'holland_test_detail',
  'model_class' => \App\HollandTestDetail::class,
])
