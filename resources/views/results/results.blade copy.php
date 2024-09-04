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

                @foreach ($usersWithResult as $userWithResult)
                @php
                $booIsAbsent = $userWithResult->is_absent == '1' ? true : false;
                $backGroundColor = 'bg-light';

                if ($booIsAbsent) {
                $backGroundColor = 'bg-warning';
                } elseif ($userWithResult->note != null) {
                $backGroundColor = 'bg-success';
                }
                @endphp
                <form action="{{ route('results.store', ['work' => $work->id ]) }}" method="POST"
                    id="form_{{ $loop->index }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="row mb-1 border {{ $backGroundColor }}" style="--bs-bg-opacity: .2;">
                        <div class="col-md-4">
                            <strong>{{ $userWithResult->last_name }} {{ $userWithResult->first_name }}</strong>
                            <div class="form-check">

                                {{-- is absent --}}
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    @checked($booIsAbsent)
                                    id="result[{{ $loop->index }}][is_absent]"
                                    name="result[{{ $loop->index }}][is_absent]"
                                    onchange='document.getElementById("form_{{$loop->index}}").submit()'>

                                <label
                                    class="form-check-label"
                                    for="result[{{ $loop->index }}][is_absent]">Absence
                                </label>

                            </div>
                        </div>

                        <div class="col-md-8">
                            <input type="hidden" name="user_id" value="{{ $userWithResult->user_id }}">
                            <input type="hidden" name="index" value="{{ $loop->index }}">
                            <div class="row mt-2">
                                <div class="col-md-4">

                                    {{-- note --}}
                                    {{-- blade-formatter-disable --}}
                                    <input type="number"
                                        class='form-control form-control-sm mr-sm-2  @error("result.$loop->index.note") is-invalid @enderror'
                                        name="result[{{ $loop->index }}][note]"
                                        value="{{ old('result.' . $loop->index . '.note', $userWithResult->note) }}"
                                        placeholder="note"
                                        @readonly($booIsAbsent)
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
                                        :name="'result[' . $loop->index . '][appreciation_id]'"
                                        :value="old('result.' . $loop->index . '.appreciation_id', $userWithResult->appreciation_id)"
                                        {{-- 
                                        onchange='document.getElementById("form_{{$userWithResult->id}}").submit()'
                                        --}} />
                                    {{-- blade-formatter-enable --}}
                                </div>

                                {{-- button OK --}}
                                @can('create', App\Models\Result::class)
                                <div class="col-md-2 d-grid gap-2">
                                    <button type="submit" aria-label="Valider" class="btn btn-primary btn-sm" title="Valider résultat"><i
                                            class="bi bi-check" aria-hidden="true"></i></button>
                                </div>
                                <div class="col-md-2 d-grid gap-2">
                                    <button type="button" aria-label="Supprimer" class="btn btn-danger btn-sm delete-result" data-index="{{ $loop->index }}" title="Supprimer résultat (todo)">
                                        <i
                                            class="bi bi-trash" aria-hidden="true"></i></button>
                                </div>
                                @endcan

                                @error("result.$loop->index.note")
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                                @error("result.$loop->index.appreciation_id")
                                <span class="text-danger">{{ $message }}</span>
                                @enderror

                            </div>
                            <div class="row mt-1 mb-2">
                                <div class="col">

                                    {{-- comment --}}
                                    {{-- blade-formatter-disable --}}
                                    <textarea
                                        rows="1"
                                        maxlength="200"
                                        class='form-control form-control-sm mr-sm-2 @error("result.$loop->index.comment") is-invalid @enderror'
                                        name="result[{{ $loop->index }}][comment]"
                                        @readonly($booIsAbsent)
                                        placeholder="commentaire"
                                        onchange='document.getElementById("form_{{$loop->index}}").submit()'>{{ old('result.' . $loop->index . '.comment', $userWithResult->comment) }}</textarea>
                                    {{-- blade-formatter-enable --}}
                                </div>
                                @error("result.$loop->index.comment")
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>


                        </div>
                    </div>
                </form>
                @endforeach
            </div>
        </div>
    </div>

    <div class="col-md-4 sticky-top align-self-start">
        resumé to do
    </div>
</div>

@endsection

@section('extra_js')
<script>
    $(document).ready(function() {

        if (localStorage.getItem("scroll-position") != null) {
            $(window).scrollTop(localStorage.getItem("scroll-position"));
        }

        $(window).on("scroll", function() {
            localStorage.setItem("scroll-position", $(window).scrollTop());
        });

        $('.delete-result').click(function() {
            alert('todo')
            console.log(this)
            let index = $(this).attr('data-index')
            console.log(index)
        })

    });
</script>
@endsection