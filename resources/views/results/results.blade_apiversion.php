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
                <input type="hidden" id="work-id" value="{{ $work->id }}">
            </div>
            <div class="card-body">

                @foreach ($usersWithResult as $userWithResult)
                @php
                $booIsAbsent = $userWithResult->is_absent == 1 ? true : false;
                $backGroundColor = 'white';

                if ($booIsAbsent) {
                $backGroundColor = 'orange';
                } elseif ($userWithResult->note != null) {
                $backGroundColor = 'LightGreen';
                }
                @endphp
                <form>

                    <div class="row mb-1 border" id="row_{{ $loop->index }}" style="--bs-bg-opacity: .2; background-color: {{$backGroundColor}}">
                        <div class="col-md-4">
                            <strong>{{ $userWithResult->last_name }} {{ $userWithResult->first_name }}</strong>

                            {{-- is absent --}}
                            <div class="form-check">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    @checked($booIsAbsent)
                                    id="is_absent_{{ $loop->index}}">
                                <label
                                    class="form-check-label"
                                    for="is_absent_{{ $loop->index}}">Absent
                                </label>
                            </div>
                        </div>

                        <div class="col-md-8">
                            <input type="hidden" name="user_id" id="user_{{$loop->index}}" value="{{ $userWithResult->user_id }}">
                            <input type="hidden" name="index" value="{{ $loop->index }}">
                            <div class="row mt-2">
                                <div class="col-md-4">

                                    {{-- note --}}
                                    {{-- blade-formatter-disable --}}
                                    <input type="number"
                                        class="form-control form-control-sm mr-sm-2"
                                        id="note_{{$loop->index}}"
                                        value="{{ $userWithResult->note }}"
                                        placeholder="note"
                                        min="0"
                                        max="20"
                                        step="0.01">
                                    {{-- blade-formatter-enable --}}
                                </div>
                                <div class="col-md-4">
                                    {{-- appreciation --}}
                                    {{-- blade-formatter-disable --}}
                                    <x-select-appreciation
                                        :appreciations="$appreciations"
                                        :id="'appreciation_'.$loop->index"
                                        :name="'appreciation_'.$loop->index"
                                        :value="$userWithResult->appreciation_id" />
                                    {{-- blade-formatter-enable --}}
                                </div>

                                {{-- button OK --}}
                                @can('create', App\Models\Result::class)
                                <div class="col-md-2 d-grid gap-2">
                                    <button type="button" aria-label="Valider" class="btn btn-primary btn-sm validate-result" data-index="{{ $loop->index }}" title="Valider résultat">
                                        <i class="bi bi-check" aria-hidden="true"></i>
                                    </button>
                                </div>

                                {{-- button delete result --}}
                                <div class="col-md-2 d-grid gap-2">
                                    <button type="button" aria-label="Supprimer" class="btn btn-danger btn-sm delete-result" data-index="{{ $loop->index }}" title="Supprimer résultat (todo)">
                                        <i class="bi bi-trash" aria-hidden="true"></i>
                                    </button>
                                </div>
                                @endcan

                            </div>
                            <div class="row mt-1 mb-2">
                                <div class="col">

                                    {{-- comment --}}
                                    <textarea
                                        rows="1"
                                        maxlength="200"
                                        class='form-control form-control-sm mr-sm-2'
                                        id="comment_{{$loop->index}}"
                                        placeholder="commentaire">{{ $userWithResult->comment }}</textarea>
                                </div>
                            </div>


                        </div>
                        <span class="text-danger" id="error_{{$loop->index}}"></span>
                    </div>


                </form>
                @endforeach
            </div>
        </div>
    </div>

    <div class="col-md-4 sticky-top align-self-start">
        {{--
        <x-table-work-summary :$work :$usersWithResult />
        --}}
    </div>
</div>

@endsection

@section('extra_js')
<script>
    $(document).ready(function() {

        $('.validate-result').click(function() {

            let index = $(this).attr('data-index')
            console.log('index ' + index)

            let userId = $('#user_' + index).val()
            console.log('user id ' + userId)

            let workId = $('#work-id').val()
            console.log('work id ' + workId)

            let note = $('#note_' + index).val()
            console.log('note ' + note)

            let appreciationId = $('#appreciation_' + index).val()
            console.log('appreciation id ' + appreciationId)

            let comment = $('#comment_' + index).val()
            console.log('comment ' + comment)

            let isAbsent = $('#is_absent_' + index).prop('checked') == true ? 1 : 0
            console.log('is absent ' + isAbsent)

            const data = {
                'note': note,
                'comment': comment,
                'user_id': userId,
                'appreciation_id': appreciationId,
                'is_absent': isAbsent
            }

            $.ajax({
                url: '/works/' + workId + '/results',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                data: data,
                dataType: 'json',
                success: function(data) {

                    console.log('tout est ok')
                    console.log(data)

                    $('#error_' + index).html('')
                    $('#note_' + index).val(data.data.note)
                    $('#comment_' + index).val(data.data.comment)
                    $('#appreciation_' + index).val(data.data.appreciation_id)

                    if (data.data.is_absent) {
                        console.log('orange')
                        backGroundColor = 'orange'
                    } else {
                        console.log('green')
                        backGroundColor = 'LightGreen'
                    }
                    setBackGroundColor(index, backGroundColor);

                },
                error: function(data) {
                    console.log('error')
                    console.log(data.status)

                    if (data.status == 422) {
                        const errors = $.parseJSON(data.responseText);
                        console.log(errors)

                        $('#error_' + index).html(errors.message)
                    }


                },
            })

        })

        $('.delete-result').click(function() {
            let index = $(this).attr('data-index')
            console.log('index ' + index)

            let userId = $('#user_' + index).val()
            console.log('user id ' + userId)

            let workId = $('#work-id').val()
            console.log('work id ' + workId)

            const data = {
                'user_id': userId
            }

            $.ajax({
                url: '/works/' + workId + '/results/destroy',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                data: data,
                dataType: 'json',
                success: function(data) {

                    console.log('tout est ok')
                    $('#error_' + index).html('')
                    $('#note_' + index).val('')
                    $('#comment_' + index).val('')
                    $('#appreciation_' + index).val('')
                    $('#is_absent_' + index).prop('checked', false)
                    setBackGroundColor(index, "white");


                },
                error: function(data) {
                    console.log('error')
                    console.log(data.status)

                    if (data.status == 422) {
                        const errors = $.parseJSON(data.responseText);
                        console.log(errors)

                        $('#error_' + index).html(errors.message)
                    }
                },
            })
        })

        function setBackGroundColor(index, color) {
            console.log(index, color)
            $('#row_' + index).css("background-color", color);
        }

    });
</script>
@endsection