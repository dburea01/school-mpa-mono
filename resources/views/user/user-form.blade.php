@extends('layout')

@section('title', $user->id ? 'Modifier utilisateur' : 'Créer utilisateur')

@section('content')
<div class="row">
    <div class="col mx-auto">
        @include('errors.messages-error-info')
    </div>
</div>

<h2 class="text-center">
    @if ($user->id)
    Modifier utilisateur
    @else
    Créer utilisateur
    @endif
</h2>

@if ($user->id)
<form action="/users/{{ $user->id }}" method="POST" id="form-user">
    @method('PUT')
    @else
    <form action="/users" method="POST" id="form-user">
        @endif

        <input type="hidden" name="_token" value="{{ csrf_token() }}">

        <div class="row">
            <div class="col-md-6">
                <div class="card shadow">

                    <div class="card-header text-center">Identité</div>

                    <div class="card-body">
                        {{-- role --}}
                        <div class="row mb-3">
                            <label for="role_id" class="col-sm-4 col-form-label col-form-label-sm text-truncate text-sm-end">
                                Rôle : *</label>

                            <div class="col-sm-8">
                                <x-select-role name="role_id" id="role_id" required="true" :value="old('role_id', $user->role_id)" placeholder=" " />

                                @if ($errors->has('role_id'))
                                <span class="text-danger">{{ $errors->first('role_id') }}</span>
                                @endif
                            </div>
                        </div>

                        {{-- last name --}}
                        <div class="row mb-3">
                            <label for="last_name" class="col-sm-4 col-form-label col-form-label-sm text-truncate text-sm-end">
                                Nom : *</label>

                            <div class="col-sm-8">
                                <input type="text" class="form-control form-control-sm @error('last_name') is-invalid @enderror text-uppercase" required name="last_name" id="last_name" maxlength="60" value="{{ old('last_name', $user->last_name) }}">
                                @if ($errors->has('last_name'))
                                <span class="text-danger">{{ $errors->first('last_name') }}</span>
                                @endif
                            </div>
                        </div>

                        {{-- first name --}}
                        <div class="row mb-3">
                            <label for="first_name" class="col-sm-4 col-form-label col-form-label-sm text-truncate text-sm-end">
                                Prénom : *</label>

                            <div class="col-sm-8">
                                <input type="text" class="form-control form-control-sm @error('first_name') is-invalid @enderror text-capitalize" required name="first_name" id="first_name" maxlength="60" value="{{ old('first_name', $user->first_name) }}">
                                @if ($errors->has('first_name'))
                                <span class="text-danger">{{ $errors->first('first_name') }}</span>
                                @endif
                            </div>
                        </div>

                        {{-- civility --}}
                        <div class="row mb-3 civility">
                            <label class="col-sm-4 col-form-label col-form-label-sm text-truncate text-sm-end">
                                Civilité :</label>


                            <div class="col-sm-8">
                                <x-select-civility type="radio" name="civility_id" id="civility_id" required="false" :value="old('civility_id', $user->civility_id)" placeholder=" " />
                                @if ($errors->has('civility_id'))
                                <span class="text-danger">{{ $errors->first('civility_id') }}</span>
                                @endif
                            </div>

                        </div>

                        {{-- gender --}}
                        <div class="row mb-3 student">
                            <label class="col-sm-4 col-form-label col-form-label-sm text-truncate text-sm-end">
                                Sexe : *</label>

                            <div class="col-sm-8">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="gender_id" id="male" value="1" {{ old('gender_id', $user->gender_id) == '1' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="male">M</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="gender_id" id="female" value="2" {{ old('gender_id', $user->gender_id) == '2' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="female">F</label>
                                </div>
                            </div>
                            @if ($errors->has('gender_id'))
                            <div class="col-sm-8 offset-sm-4 text-danger">{{ $errors->first('gender_id') }}</div>
                            @endif
                        </div>

                        {{-- birth date --}}
                        <div class="row mb-3 student">
                            <label for="birth_date" class="col-sm-4 col-form-label col-form-label-sm text-truncate text-sm-end">
                                Date naissance : *</label>

                            <div class="col-sm-4">
                                <input type="text" class="form-control form-control-sm @error('birth_date') is-invalid @enderror" name="birth_date" id="birth_date" value="{{ old('birth_date', $user->birth_date) }}">


                            </div>
                            <div class="col-sm-4 form-text">jj/mm/aaaa</div>
                            @if ($errors->has('birth_date'))
                            <div class="col-sm-10 offset-sm-4">
                                <span class="text-danger">{{ $errors->first('birth_date') }}</span>
                            </div>
                            @endif
                        </div>

                        {{-- email --}}
                        <div class="row">
                            <label for="email" class="col-sm-4 col-form-label col-form-label-sm text-truncate text-sm-end">
                                Adresse mail : </label>

                            <div class="col-sm-8">
                                <input type="email" class="form-control form-control-sm @error('email') is-invalid @enderror" name="email" id="email" value="{{ old('email', $user->email) }}">
                                @if ($errors->has('email'))
                                <span class="text-danger">{{ $errors->first('email') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow mt-3">
                    <div class="card-header text-center">Statu</div>

                    <div class="card-body">
                        {{-- login status --}}
                        {{--
                        <div class="row mb-3">
                            <label for="login_status_id" class="col-sm-4 col-form-label col-form-label-sm text-truncate text-sm-end">
                                Statu : *</label>

                            <div class="col-sm-8">
                                <x-select-login-status name="login_status_id" id="login_status_id" required="true" :value="old('login_status_id', $user->login_status_id)" placeholder=" " />

                                @error('login_status_id')
                                <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                --}}
            </div>
        </div>
        </div>

        <div class="col-md-6 mt-3 mt-md-0">

            <div class="card shadow">
                <div class="card-header text-center">Coordonnées</div>
                <div class="card-body">

                    {{-- address --}}
                    <div class="row mb-3">
                        <label for="address" class="col-sm-4 col-form-label col-form-label-sm text-truncate text-sm-end">
                            Adresse :</label>

                        <div class="col-sm-8">
                            <textarea class="form-control form-control-sm @error('address') is-invalid @enderror" name="address" id="address" rows="2">{{ old('address', $user->address) }}</textarea>
                            @if ($errors->has('address'))
                            <span class="text-danger">{{ $errors->first('address') }}</span>
                            @endif
                        </div>
                    </div>

                    {{-- postal code + city --}}
                    <div class="row mb-3">
                        <label for="postal_code" class="col-sm-4 col-form-label col-form-label-sm text-truncate text-sm-end">
                            Code postal :</label>

                        <div class="col-sm-3">
                            <input type="text" class="form-control form-control-sm @error('postal_code') is-invalid @enderror" name="postal_code" id="postal_code" value="{{ old('postal_code', $user->postal_code) }}">
                            @if ($errors->has('postal_code'))
                            <span class="text-danger">{{ $errors->first('postal_code') }}</span>
                            @endif
                        </div>

                        <div class="col-sm-5">
                            <input type="text" class="form-control form-control-sm @error('city') is-invalid @enderror text-uppercase" name="city" id="city" value="{{ old('city', $user->city) }}" placeholder="Commune">
                            @if ($errors->has('city'))
                            <span class="text-danger">{{ $errors->first('city') }}</span>
                            @endif
                        </div>
                    </div>

                    {{-- country id --}}
                    <div class="row mb-3">
                        <label for="country_id" class="col-sm-4 col-form-label col-form-label-sm text-truncate text-sm-end">
                            Pays :</label>
                        <div class="col-sm-4">
                            <x-select-country name="country_id" id="country_id" required="false" :value="old('country_id', $user->country_id)" />
                            @if ($errors->has('country_id'))
                            <span class="text-danger">{{ $errors->first('country_id') }}</span>
                            @endif
                        </div>
                    </div>

                    {{-- phone number --}}
                    <div class="row">
                        <label for="phone_number" class="col-sm-4 col-form-label col-form-label-sm text-truncate text-sm-end">
                            Téléphone :</label>

                        <div class="col-sm-4">
                            <input type="text" class="form-control form-control-sm @error('phone_number') is-invalid @enderror text-uppercase" name="phone_number" id="phone_number" value="{{ old('phone_number', $user->phone_number) }}">
                            @if ($errors->has('phone_number'))
                            <span class="text-danger">{{ $errors->first('phone_number') }}</span>
                            @endif
                        </div>
                    </div>

                </div>

            </div>

            <div class="card shadow mt-3">
                <div class="card-header text-center">Divers</div>
                <div class="card-body">
                    {{-- health comment --}}
                    <div class="row mb-3">
                        <label for="health_comment" class="col-sm-4 col-form-label col-form-label-sm text-truncate text-sm-end">
                            Information SANTE :</label>

                        <div class="col-sm-8">
                            <textarea class="form-control form-control-sm @error('health_comment') is-invalid @enderror" name="health_comment" id="health_comment" rows="3" maxlength="500">{{ old('health_comment', $user->health_comment) }}</textarea>
                            @if ($errors->has('health_comment'))
                            <span class="text-danger">{{ $errors->first('health_comment') }}</span>
                            @endif
                        </div>
                    </div>

                    {{-- other comment --}}
                    <div class="row">
                        <label for="other_comment" class="col-sm-4 col-form-label col-form-label-sm text-truncate text-sm-end">
                            Commentaires :</label>

                        <div class="col-sm-8">
                            <textarea class="form-control form-control-sm @error('other_comment') is-invalid @enderror" name="other_comment" id="other_comment" rows="3" maxlength="500">{{ old('other_comment', $user->other_comment) }}</textarea>
                            @if ($errors->has('other_comment'))
                            <span class="text-danger">{{ $errors->first('other_comment') }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </div>
        </div>




        {{-- buttons --}}
        <div class="row mt-3">
            <div class="col">
                <button type="button" id="submit-form" class="btn btn-sm btn-success"><i class="bi bi-check2" aria-hidden="true"></i>
                    @if ($user->id)
                    Modifier utilisateur
                    @else
                    Créer utilisateur
                    @endif
                </button>
                @if ($user->id)
                @can('delete', $user)
                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#modalDeleteUser"><i class="bi bi-trash" aria-hidden="true"></i>
                    Supprimer utilisateur</button>
                @endcan
                @endif
            </div>

        </div>



    </form>

    <x-created-updated-by :model="$user" />

    <!-- Modal delete user -->
    <div class="modal fade" id="modalDeleteUser" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Supprimer <strong>{{ $user->full_name }}</strong></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Attention, vous vous apprétez à supprimer un utilisateur. En supprimant un utilisateur, vous
                        supprimez
                        également tous ses historiques d'affectations, de notes....</p>
                    <p class="text-danger">Action irréversible.</p>
                    <p>Veuillez confirmer la suppression de <strong>{{ $user->full_name }}</strong></p>
                </div>
                <div class="modal-footer">
                    <form class="form-inline" method="POST" action="/users/{{ $user->id }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        @method('DELETE')
                        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal"><i class="bi bi-chevron-left" aria-hidden="true"></i>
                            Abandonner</button>
                        <button type="submit" class="btn btn-sm btn-danger ml-3"><i class="bi bi-trash" aria-hidden="true"></i>
                            Supprimer <strong>{{ $user->full_name }}</strong></button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal duplicated user -->
    <div class="modal fade" id="modalDuplicatedUser" tabindex="-1" aria-labelledby="DuplicatedUser" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="DuplicatedUser">Attention - potentiel doublons ?</strong></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Attention, la personne semble déjà exister. Vérifiez avant de valider.</p>
                    <table class="table table-sm" id="table-duplicated-user">
                        
                    </table>
                </div>
                <div class="modal-footer">
                    <form class="form-inline" method="POST" action="/users/{{ $user->id }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        @method('DELETE')
                        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal"><i class="bi bi-chevron-left" aria-hidden="true"></i>
                            Abandonner</button>
                        <button type="submit" class="btn btn-sm btn-danger ml-3"><i class="bi bi-trash" aria-hidden="true"></i>
                            Supprimer <strong>{{ $user->full_name }}</strong></button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @endsection

    @section('extra_js')
    <script>
        function PreviewImage() {
            var oFReader = new FileReader();
            oFReader.readAsDataURL(document.getElementById("image_user").files[0]);

            oFReader.onload = function(oFREvent) {
                document.getElementById("uploadPreview").src = oFREvent.target.result;
            };
        };

        $(document).ready(function() {

            displayHiddeElement()

            $("#role_id").change(function() {
                displayHiddeElement();
            })

            $("#submit-form").click(function(e) {
                // $("#form-user").reportValidity();

                $.ajax({
                    url: "{{ route('getDuplicatedUsers') }}",
                    data: {
                        first_name: $("#first_name").val(),
                        last_name: $("#last_name").val()
                    },
                    dataType: "json",
                    success: function(data) {
                        console.log(data)
                        if (data.length == 0) {
                            $("#form-user").submit();
                        } else {
                            console.log('des doublons')
                            let append = ''
                            data.foreach(user => append = append('<tr><td>New Name</td><td>New Age</td></tr>'))
                            console.log(append)
                            $('#modalDuplicatedUser').modal('show');
                        }
                    }
                });


            });

            function displayHiddeElement() {
                if ($("#role_id").val() == 'STUDENT') {
                    $(".student").show()
                    $(".civility").hide()
                } else {
                    $(".student").hide()
                    $(".civility").show()
                }
            }

        })
    </script>
    @endsection