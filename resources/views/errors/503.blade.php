@extends('errors::layout')

@section('image')
    <div class="">
        <img src="/img/svg/maintenance.svg" width="300" alt="{{ config('app.name') }} Logo">
    </div>
@endsection

@section('title', __('{{ config('app.name') }} - Maintenance mode'))
@section('code', '503')
@section('message', 'Maintenance mode, be back soon.'))
