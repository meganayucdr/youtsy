@extends('roles.show')

@include('models.children.show', [
  'resource_route' => 'roles.users',
  'model_variable' => 'user',
  'parent' => $role,
  'model' => $user
])
