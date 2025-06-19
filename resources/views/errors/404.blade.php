@if(request()->expectsJson())
@json(['message' => 'Page not found.',])
@else
@extends('themes-lezada::layouts.app', ['exception' => $exception])
@section('content')
@livewire('themes-lezada::livewire.errors.page-not-found')
@endsection
@endif