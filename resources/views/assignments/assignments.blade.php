@extends('layout')
@section('title', "Affectations $classroom->short_name")

@section('content')
@include('errors.session-values')



<div class="row">

    <div class="col-md-3">
        <div class="row">
            <div class="col">
                <div class="card shadow">
                    <div class="card-header text-center">Filtres</div>
                    <div class="card-body">
                        <form class="row" action="{{ route('assignments.index', ['classroom'=>$classroom]) }}" id="form-users">

                            <div class="mt-1">
                                <x-select-role :value="$role_id" :is_assignable="true" name="role_id" id="role_id" />
                            </div>

                            <div class="d-grid gap-2 mt-3">
                                <button type="submit" class="btn btn-primary btn-sm">Filtrer</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col">
                <div class="card shadow">
                    <div class="card-header text-center">Répartition par sexe</div>
                    <div class="card-body">@todo
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="col-md-9">

        <div class="card shadow">
            <div class="card-header text-center">Affectations {{ $classroom->short_name }}
                <strong>({{ count($assignments) }})</strong>
            </div>
            <div class="card-body">
                <table class="table table-sm table-hover table-bordered" aria-label="list of the assignments of a classroom">
                    <thead>
                        <tr>
                            <th>Utilisateur</th>
                            <th>Rôle</th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($assignments as $assignment)
                        <tr>
                            <td>
                                {{ $assignment->last_name.' '.$assignment->first_name }}

                                @if($assignment->role_id == 'STUDENT')
                                @switch($assignment->gender_id)
                                @case(1)
                                <i class="bi bi-gender-male"></i>
                                @break
                                @case(2)
                                <i class="bi bi-gender-female"></i>
                                @break
                                @endswitch
                                @endif
                            </td>
                            <td>
                                {{ $assignment->role_name }}

                                @if ($assignment->subject_id != null)
                                <span class="badge text-bg-primary">{{ $assignment->subject_name }}</span>
                                @endif
                            </td>

                            <td>
                                @can('delete', App\Models\Assignment::class)
                                <form class="form-inline" method="POST" action="/classrooms/{{ $classroom->id }}/assignments/{{ $assignment->assignment_id }}">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    @method('DELETE')

                                    <i class="bi bi-person-dash" aria-hidden="true" style="cursor: pointer;" onclick="this.closest('form').submit();" title="Retirer {{ $assignment->last_name.' '.$assignment->first_name }} de la classe {{ $classroom->short_name }}"></i>
                                </form>
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