@extends('layout')

@section('title', 'Liste des résultats')
@section('content')
<div class="row">
    <div class="col mx-auto">
        @include('errors.session-values')
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header text-center">
                Liste des résultats <strong>{{ $work->title }}</strong>
            </div>
            <div class="card-body">

                <table class="table table-sm table-striped table-hover table-bordered">
                    <thead>
                        <tr>
                            <th>Elève
                                <a href="{{ route('results.index', ['work' => $work, 'sort' => 'name', 'direction' => 'asc']) }}"><i class="bi bi-arrow-down-up"></i></a>
                                <a href="{{ route('results.index', ['work' => $work, 'sort' => 'name', 'direction' => 'desc']) }}"><i class="bi bi-sort-alpha-down-alt"></i></a>
                            </th>
                            <th>Note
                                <a href="{{ route('results.index', ['work' => $work, 'sort' => 'note', 'direction' => 'asc']) }}"><i class="bi bi-sort-numeric-down"></i></a>
                                <a href="{{ route('results.index', ['work' => $work, 'sort' => 'note', 'direction' => 'desc']) }}"><i class="bi bi-sort-numeric-down-alt"></i></a>
                            </th>
                            <th>Appréciation</th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($usersWithResult as $userWithResult)
                        <tr @if ($userWithResult->is_absent) class="table-danger" @endif>
                            <td>{{ $userWithResult->last_name }} {{ $userWithResult->first_name }}

                                @if ($userWithResult->is_absent)
                                <x-alert-inactive alert="Elève déclaré absent" />
                                @endif
                            </td>
                            <td>{{ $userWithResult->note }}</td>
                            <td>{{ $userWithResult->appreciation_name }}

                                @if ($userWithResult->comment != '')
                                <span class="text-primary"><x-tooltip-comment :comment="$userWithResult->comment" /></span>
                                @endif
                            </td>
                            <td>
                                <a href="
                                @if ($userWithResult->result_id)
                                {{ route('results.edit', ['work' => $work, 'result' => $userWithResult->result_id ]) }}
                                @else
                                {{ route('results.create', ['work' => $work]) }}
                                @endif 
                                "><i class="bi bi-pencil"></i></a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-4 sticky-top align-self-start">
        <x-table-work-summary :$work :$usersWithResult />
    </div>
</div>

@endsection