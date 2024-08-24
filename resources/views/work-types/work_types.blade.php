@extends('layout')

@section('title', 'Liste des types de travail')
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
                Liste des types de travail ({{ $workTypes->count() }})
                @can('create', App\Models\WorkType::class)
                <a href="{{ route('work-types.create') }}">
                    Créer type de travail
                </a>
                @endcan
            </div>
            <div class="card-body">
                <table class="table table-sm table-bordered table-hover" aria-label="work types list">
                    <thead>
                        <tr>
                            <th>Nom court</th>
                            <th>Nom</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($workTypes as $workType)
                        <tr>
                            <td>
                                @can('update', $workType)
                                <a href=" {{ route('work-types.edit', ['work_type' => $workType]) }}">
                                    {{ $workType->short_name }}
                                </a>
                                @else
                                {{ $workType->short_name }}
                                @endcan

                                @if ($workType->is_active == '0')
                                <x-alert-inactive alert="Type de travail inactif" />
                                @endif

                                @if ($workType->comment != '')
                                <x-tooltip-comment :comment="$workType->comment" />
                                @endif
                            </td>

                            <td>{{ $workType->name }}</td>
                        </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>
        </div>
    </div>
</div>

@endsection