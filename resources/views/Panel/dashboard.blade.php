@extends('app')


@section('panel')

    {{ $user->first_name }}
    {{ $user->last_name }}

    <a href="{{ route('logout') }}">Wyloguj</a>


@endsection

