@extends('layout')

@section('title', 'erreur - 403')

@section('content')

<h1 class="text-center">Ouh là..... Ce que vous tentez de faire est interdit !</h1>

<br><br>
{{--
@if (isset($exception))
<h3 class="text-center">{{ $exception->getMessage() }}</h3>
@endif
--}}
@endsection