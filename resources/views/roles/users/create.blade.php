@extends('roles.show')

@include('models.children.create', [
  'resource_route' => 'roles.users',
  'model_variable' => 'user',
  'model_class' => \App\User::class,
  'parent' => $role
])
