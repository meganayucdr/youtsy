@extends('layouts.app')

@section('content-title', ucwords(__('options.plural')))

@include('models.index', [
  'col_class' => 'col-md-8 col-md-offset-2 offset-md-2',
  'panel_title' => ucwords(__('options.plural')),
  'resource_route' => 'options',
  'model_variable' => 'option',
  'model_class' => \App\Option::class,
  'models' => $options,
  'action_buttons_view' => 'generator::components.models.index.action_buttons',
])
