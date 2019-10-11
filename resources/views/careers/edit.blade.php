@extends('layouts.app')

@section('content-title', ucwords(__('careers.plural')))

@include('models.edit', [
  'panel_title' => ucwords(__('careers.singular')),
  'resource_route' => 'careers',
  'model_variable' => 'career',
  'model' => $career
])
