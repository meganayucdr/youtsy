@extends('layouts.app')

@section('content-title', ucwords(__('holland_codes.plural')))

@include('models.index', [
  'col_class' => 'col-md-8 col-md-offset-2 offset-md-2',
  'panel_title' => ucwords(__('holland_codes.plural')),
  'resource_route' => 'holland_codes',
  'model_variable' => 'holland_code',
  'model_class' => \App\HollandCode::class,
  'models' => $holland_codes,
  'action_buttons_view' => 'generator::components.models.index.action_buttons',
])
