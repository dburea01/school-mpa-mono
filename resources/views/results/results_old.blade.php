@extends('layout')

@section('title', 'Liste des résultats')
@section('content')
    <div class="row">
        <div class="col mx-auto">
            @include('errors.session-values')
        </div>
    </div>

    <h1 class="text-center text-primary">Noter un travail</h1>

    <div class="row">
        <div class="col-md-8">
            @foreach ($usersWithResult as $userWithResult)
                @php
                    $booIsAbsent = $userWithResult->result ? $userWithResult->result->is_absent : false;
                    $backGroundColor = 'bg-light';
                    
                    if ($booIsAbsent) {
                        $backGroundColor = 'bg-warning';
                    } elseif ($userWithResult->result && $userWithResult->result->note != null) {
                        $backGroundColor = 'bg-success';
                    }
                @endphp
                <form action="/schools/{{ $school->id }}/works/{{ $work->id }}/results" method="POST"
                    id="form_{{ $userWithResult->id }}">
                    @csrf
                    <div class="row border {{ $backGroundColor }}" style="--bs-bg-opacity: .2;">
                        <div class="col-md-4">
                            <strong>{{ $userWithResult->full_name }}</strong>
                            <div class="form-check">

                                {{-- is absent --}}
                                {{-- blade-formatter-disable --}}
                                <input 
                                    class="form-check-input" 
                                    type="checkbox"
                                    @checked($booIsAbsent)
                                    id="result[{{ $loop->index }}][is_absent]" 
                                    name="result[{{ $loop->index }}][is_absent]"
                                    onchange='document.getElementById("form_{{$userWithResult->id}}").submit()'
                                >
                                
                                <label 
                                    class="form-check-label" 
                                    for="result[{{ $loop->index }}][is_absent]">Absence
                                </label>
                                {{-- blade-formatter-enable --}}
                            </div>
                        </div>

                        <div class="col-md-8">
                            <input type="hidden" name="user_id" value="{{ $userWithResult->id }}">
                            <input type="hidden" name="index" value="{{ $loop->index }}">
                            <div class="row mt-2">
                                <div class="col-md-4">

                                    {{-- note --}}
                                    {{-- blade-formatter-disable --}}
                                    <input type="number"
                                        class='form-control form-control-sm mr-sm-2  @error("result.$loop->index.note") is-invalid @enderror'
                                        name="result[{{ $loop->index }}][note]"
                                        value="{{ old('result.' . $loop->index . '.note', $userWithResult->result ? $userWithResult->result->note : '') }}"
                                        placeholder="note" 
                                        @readonly($booIsAbsent)
                                        min="{{ $work->note_min }}" 
                                        max="{{ $work->note_max }}"
                                        step="{{ $work->note_increment =! 0 ? $work->note_increment : 0.25 }}"
                                        >
                                    {{-- blade-formatter-enable --}}
                                </div>
                                <div class="col-md-4">
                                    {{-- appreciation --}}
                                    {{-- blade-formatter-disable --}}
                                    <x-select-appreciation 
                                        :appreciations="$appreciations" 
                                        :name="'result[' . $loop->index . '][appreciation_id]'" 
                                        :value="old('result.' . $loop->index . '.appreciation_id', $userWithResult->result ? $userWithResult->result->appreciation_id : '')" 
                                        {{-- 
                                        onchange='document.getElementById("form_{{$userWithResult->id}}").submit()'
                                        --}}
                                        />
                                    {{-- blade-formatter-enable --}}
                                </div>

                                {{-- button OK --}}
                                @can('create', [App\Models\Result::class, $school, $work])
                                    <div class="col-md-4 d-grid gap-2">
                                        <button type="submit" aria-label="Valider" class="btn btn-primary btn-sm"><i
                                                class="bi bi-check" aria-hidden="true"></i></button>
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
                                        onchange='document.getElementById("form_{{$userWithResult->id}}").submit()'>{{ old('result.' . $loop->index . '.comment', $userWithResult->result ? $userWithResult->result->comment : '') }}</textarea>
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

        <div class="col-md-4 sticky-top align-self-start">
            <x-table-work-summary :$work :$usersWithResult />
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

        });
    </script>
@endsection
