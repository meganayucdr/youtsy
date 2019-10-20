@extends('layouts.app')

@section('content-title', ucwords(__('options.plural')))

@include('models.create', [
  'panel_title' => ucwords(__('options.singular')),
  'resource_route' => 'options',
  'model_variable' => 'option',
  'model_class' => \App\Option::class,
])
