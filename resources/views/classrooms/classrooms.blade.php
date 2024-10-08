@extends('layout')

@section('title', 'Liste des classes')
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
                Liste des classes ({{ $classrooms->count() }}) <strong>{{ $period->name }}</strong>
                @can('create', App\Models\Classrooms::class)
                <a href="{{ route('classrooms.create', ['period' => $period ]) }}">
                    Créer classe
                </a>
                @endcan
            </div>
            <div class="card-body table-responsive">
                <table class="table table-sm table-bordered table-hover" aria-label="classrooms list">
                    <thead>
                        <tr>
                            <th>Nom court</th>
                            <th class="d-none d-sm-block">Nom</th>
                            <th>Enseignants</th>
                            <th>Elèves</th>
                            <th>Travaux</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($classrooms as $classroom)
                        <tr>

                            <td>
                                @can('update', [App\Models\Classroom::class, $classroom])
                                <a href=" {{ route('classrooms.edit', ['period' => $period, 'classroom' => $classroom]) }}">
                                    {{ $classroom->short_name }}
                                </a>
                                @else
                                {{ $classroom->short_name }}
                                @endcan
                            </td>

                            <td class="d-none d-sm-block">
                                {{ $classroom->name }}

                                @if ($classroom->comment != '')
                                <x-tooltip-comment :comment="$classroom->comment" />
                                @endif
                            </td>
                            <td class="text-center">
                                @can('viewAny', [App\Models\Assignment::class])
                                <a href=" {{ route('assignments.index', ['classroom' => $classroom, 'role_id' => 'TEACHER']) }}">
                                    {{ $classroom->teachers_count }}
                                </a>
                                @endcan
                            </td>
                            <td class="text-center">@can('viewAny', [App\Models\Assignment::class])
                                <a href=" {{ route('assignments.index', ['classroom' => $classroom, 'role_id' => 'STUDENT']) }}">
                                    {{ $classroom->students_count }}
                                </a>
                                @endcan
                            </td>
                            <td class="text-center">
                                @can('viewAny', [App\Models\Work::class])
                                <a href=" {{ route('works.index', ['period' => $period, 'classroom_id' => $classroom->id]) }}">
                                    {{ $classroom->works_count }}
                                </a>
                                @else
                                {{ $classroom->works_count }}
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