@extends('layout')

@section('title', 'erreur - 404')

@section('content')

<h1 class="text-center">Page non trouvée, sniff !</h1>

<br><br>
{{--
@if (isset($exception))
<h3 class="text-center">{{ $exception->getMessage() }}</h3>
@endif
--}} 
@endsection