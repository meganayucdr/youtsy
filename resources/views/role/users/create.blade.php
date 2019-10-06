@extends('layouts.app')

@section('content-title', ucwords(__('role.users.plural')))

@include('models.create', [
  'panel_title' => ucwords(__('role.users.singular')),
  'resource_route' => 'role.users',
  'model_variable' => 'user',
  'model_class' => \App\User::class,
])
