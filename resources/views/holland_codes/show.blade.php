@extends('layouts.app')

@section('content-title', ucwords(__('holland_codes.plural')))

@include('models.show', [
  'panel_title' => ucwords(__('holland_codes.singular')),
  'resource_route' => 'holland_codes',
  'model_variable' => 'holland_code',
  'model' => $holland_code
])
