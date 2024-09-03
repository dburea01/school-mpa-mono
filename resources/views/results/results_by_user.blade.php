@extends('layout')

@section('title', 'Liste des résultats par élève')
@section('content')
    <div class="row">
        <div class="col mx-auto">
            @include('errors.session-values')
        </div>
    </div>

    <h1 class="text-center">Liste des résultats de <span class="text-primary">{{ $user->full_name }}</span>
        ({{ $results->total() }})</h1>

    <form class="row mt-3 mb-3" action="/schools/{{ $school->id }}/users/{{ $user->id }}/results">
        <div class="col-md-2 col-sm-12">
            <input type="text" class="form-control form-control-sm mr-sm-2" name="search" id="search"
                placeholder="Titre ..." value="{{ $search }}">
        </div>


        <div class="col-md-2 col-sm-12">
            <x-select-subject :$school name="subject_id" id="subject_id" :required="false" :value="$subject_id" />
        </div>

        <div class="col-md-2 col-sm-12">
            <x-select-classroom :$school :$period name="classroom_id" id="classroom_id" :required="false"
                :value="$classroom_id" />
        </div>

        <div class="col-md-2 col-sm-12">
            <x-select-work_type :$school name="work_type_id" id="work_type_id" :required="false" :value="$work_type_id" />
        </div>

        <div class="col-md-2 col-sm-12 d-grid gap-2 d-md-block">
            <button type="submit" aria-label="Filter" class="btn btn-primary btn-sm btn-block"><i class="bi bi-funnel"
                    aria-hidden="true"></i> Filtrer</button>
        </div>
    </form>

    <div class="row">
        <table class="table table-sm table-bordered ">
            <thead class="table-info">
                <tr>
                    <th>Devoir</th>
                    <th>Matière</th>
                    <th>Classe</th>

                    <th>Type</th>

                    <th>Note</th>
                    <th>Appréciation</th>
                    <th>Commentaire</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($results as $result)
                    <tr>
                        <td>{{ $result->work_title }}</td>
                        <td>{{ $result->subject_short_name }}</td>
                        <td>{{ $result->classroom_short_name }}</td>

                        <td>{{ $result->work_type_short_name }}</td>

                        <td>{{ $result->result_note }}</td>
                        <td>{{ $result->appreciation_name }}</td>
                        <td>{{ $result->result_comment }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>

    <div class=" d-flex justify-content-center">
        {{ $results->withQueryString()->links() }}
    </div>


@endsection
