@extends('layouts.app')

@section('content-title', ucwords(__('role.users.plural')))

@include('models.show', [
  'panel_title' => ucwords(__('role.users.singular')),
  'resource_route' => 'role.users',
  'model_variable' => 'user',
  'model' => $user
])
