@extends('layouts.app')

@section('content-title', ucwords(__('careers.plural')))

@include('models.index', [
  'col_class' => 'col-md-8 col-md-offset-2 offset-md-2',
  'panel_title' => ucwords(__('careers.plural')),
  'resource_route' => 'careers',
  'model_variable' => 'career',
  'model_class' => \App\Career::class,
  'models' => $careers,
  'action_buttons_view' => 'generator::components.models.index.action_buttons',
])
