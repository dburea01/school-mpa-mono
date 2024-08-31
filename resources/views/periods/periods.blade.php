@extends('layout')

@section('title', 'Liste des années scolaires')
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
                Liste des années scolaires ({{ $periods->count() }})
                @can('create', App\Models\Period::class)
                <a href="{{ route('periods.create') }}">
                    Créer année scolaire
                </a>
                @endcan
            </div>
            <div class="card-body">
                <table class="table table-sm table-bordered table-hover" aria-label="periods list">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Date début</th>
                            <th>Date fin</th>
                            <th>Classes</th>
                            <th>Travaux</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($periods as $period)
                        <tr>
                            <td>
                                @can('update', [App\Models\Period::class, $period])
                                <a href=" {{ route('periods.edit', $period->id) }}">
                                    {{ $period->name }}
                                </a>
                                @else
                                {{ $period->name }}
                                @endcan

                                @if ($period->is_current)
                                &nbsp;&nbsp;&nbsp; <i class="bi bi-check-square-fill text-primary" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="Année scolaire courante"></i>
                                @endif
                            </td>

                            <td>
                                {{ $period->start_date }}
                            </td>
                            <td>
                                {{ $period->end_date }}
                            </td>
                            <td>
                                @can('viewAny', App\Models\Classroom::class)
                                <a href=" {{ route('classrooms.index', ['period' => $period->id]) }}">
                                    {{ $period->classrooms_count }}
                                </a>
                                @else
                                {{ $period->classrooms_count }}
                                @endcan
                            </td>
                            <td>
                                @can('viewAny', App\Models\Work::class)
                                <a href=" {{ route('works.index', ['period' => $period->id]) }}">
                                    {{ $period->works_count }}
                                </a>
                                @else
                                {{ $period->works_count }}
                                @endcan
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