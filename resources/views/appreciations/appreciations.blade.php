@extends('layout')

@section('title', 'Liste des appréciations')
@section('content')
<div class="row">
    <div class="col mx-auto">
        @include('errors.session-values')
    </div>
</div>

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card shadow">
            <div class="card-header text-center">
                Liste des appréciations ({{ $appreciations->count() }})
                @can('create', App\Models\Appreciation::class)
                <a href="{{ route('appreciations.create') }}">
                    Créer appréciation
                </a>
                @endcan
            </div>
            <div class="card-body">
                <table class="table table-sm table-bordered table-hover" aria-label="appreciations list">
                    <thead>
                        <tr>
                            <th>Position</th>
                            <th>Nom court</th>
                            <th>Nom</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($appreciations as $appreciation)
                        <tr>
                            <td>
                                {{ $appreciation->position }}
                            </td>
                            <td>
                                @can('update', $appreciation)
                                <a href=" {{ route('appreciations.edit', $appreciation->id) }}">
                                    {{ $appreciation->short_name }}
                                </a>
                                @else
                                {{ $appreciation->short_name }}
                                @endcan
                            </td>

                            <td>
                                {{ $appreciation->name }}
                                @if ($appreciation->is_active == '0')
                                <x-alert-inactive alert="Appréciation inactive" />
                                @endif

                                @if ($appreciation->comment != '')
                                <x-tooltip-comment :comment="$appreciation->comment" />
                                @endif
                            </td>


                        </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>
        </div>
    </div>
</div>

@endsection