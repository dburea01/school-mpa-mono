@extends('layout')

@section('title', 'Liste des résultats par élève')

@section('content')

@include('errors.session-values')

<div class="row">


    <div class="col-md-3">
        <div class="card shadow">
            <div class="card-header text-center">Filtres</div>
            <div class="card-body">
                <form class="row" action="/users/{{ $user->id }}/results">
                    <div class="mt-1">
                        <input type="text" class="form-control form-control-sm mr-sm-2" name="search" id="search"
                            placeholder="Titre travail" value="{{ $search }}">
                    </div>


                    <div class="mt-1">
                        <x-select-subject name="subject_id" id="subject_id" :required="false" :value="$subject_id" placeholder="-- toutes les matières --" />
                    </div>

                    <div class="mt-1">
                        <x-select-period name="period_id" id="period_id" :required="false" :value="$period_id" placeholder="-- toutes les périodes --" />
                    </div>

                    {{--
                    <div class="mt-1">
                        <x-select-classroom-of-period :period="$period" :value="$classroom_id" name="classroom_id" id="classroom_id" :required="false" placeholder="-" />
                    </div>
                    --}}

                    <div class="mt-1">
                        <x-select-work-type name="work_type_id" id="work_type_id" :required="false" :value="$work_type_id" placeholder="-- tous les types de travail --" />
                    </div>

                    <div class="d-grid gap-2 mt-3">
                        <button type="submit" class="btn btn-primary btn-sm">Filtrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-9">

        <div class="card mt-3 mt-md-0 shadow">
            <div class="card-header text-center">
                Liste des résultats de <span class="text-primary">{{ $user->full_name }}</span> ({{ $results->total() }})
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Travail</th>
                                <th>Type</th>
                                <th>Matière</th>
                                <th>Classe</th>
                                <th>Note</th>
                                <th>Appréciation</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($results as $result)
                            <tr>
                                <td>{{ $result->work_title }}
                                    @if ($result->result_is_absent)
                                    <x-alert-inactive alert="Elève déclaré absent" />
                                    @endif
                                </td>
                                <td>{{ $result->work_type_short_name }}</td>
                                <td>{{ $result->subject_short_name }}</td>
                                <td>{{ $result->classroom_short_name }}</td>
                                <td>{{ $result->result_note }}</td>
                                <td>{{ $result->appreciation_name }}
                                    @if ($result->result_comment != '')
                                    <x-tooltip-comment :comment="$result->result_comment" />
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class=" d-flex justify-content-center">
                    {{ $results->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>
</div>


@endsection