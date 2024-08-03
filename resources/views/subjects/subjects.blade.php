@extends('layout')

@section('title', 'Liste des matières')
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
                Liste des matières ({{ $subjects->count() }})
                @can('create', App\Models\Subject::class)
                <a href="{{ route('subjects.create') }}">
                    Créer matière
                </a>
                @endcan
            </div>
            <div class="card-body">
                <table class="table table-sm table-bordered table-hover" aria-label="subjects list">
                    <thead>
                        <tr>
                            <th>Nom court</th>
                            <th>Nom</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($subjects as $subject)
                        <tr>
                            <td>
                                @can('update', [App\Models\subject::class, $subject])
                                <a href=" {{ route('subjects.edit', $subject->id) }}">
                                    {{ $subject->short_name }}
                                </a>
                                @else
                                {{ $subject->short_name }}
                                @endcan
                            </td>

                            <td>
                                {{ $subject->name }}
                                @if ($subject->is_active == '0')
                                <x-alert-inactive alert="Matière inactive" />
                                @endif

                                @if ($subject->comment != '')
                                <x-tooltip-comment :comment="$subject->comment" />
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