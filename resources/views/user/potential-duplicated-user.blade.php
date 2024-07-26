@extends('layout')

@section('title', 'potential utilisateur dupliqué ?')

@section('content')

@include('errors.session-values')

<h1 class="text-center text-danger">Potentiel utilisateur déjà existant ?</h1>

<p class="text-center text-danger">Attention, il existe des utilisateurs similaires. Veuillez vérifier avant de continuer.</p>

<div class="row mt-3">
    <div class="col-md-6 col-sm-12">
        <div class="card shadow">
            <div class="card-header text-center">Utilisateur que vous créez</div>
            <div class="card-body">
                @if (session('userToCreate.id'))
                <form action="/users/{{ $user->id }}" method="POST">
                    @method('PUT')
                    @else
                    <form action="/users" method="POST">
                        @endif

                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="check_duplicated_user_done" value=true>

                        {{-- rôle --}}
                        <div class="row">
                            <label for="role" class="col-sm-4 col-form-label text-truncate text-sm-end">Rôle :</label>
                            <div class="col-sm-6">
                                <input type="text" readonly class="form-control-plaintext" id="role" name="role" value="{{ session('roleUserToCreate')->name }}">
                            </div>
                        </div>

                        {{-- last name --}}
                        <div class="row">
                            <label for="last_name" class="col-sm-4 col-form-label text-truncate text-sm-end">Nom :</label>
                            <div class="col-sm-6">
                                <input type="text" readonly class="form-control-plaintext" id="last_name" name="last_name" value="{{ session('userToCreate')->last_name }}">
                            </div>
                        </div>

                        {{-- first name --}}
                        <div class="row">
                            <label for="first_name" class="col-sm-4 col-form-label text-truncate text-sm-end">Prénom :</label>
                            <div class="col-sm-6">
                                <input type="text" readonly class="form-control-plaintext" id="first_name" name="first_name" value="{{ session('userToCreate')->first_name }}">
                            </div>
                        </div>

                        {{-- birth date --}}
                        <div class="row">
                            <label for="birth_date" class="col-sm-4 col-form-label text-truncate text-sm-end">Date de naissance
                                :</label>
                            <div class="col-sm-6">
                                <input type="text" readonly class="form-control-plaintext" id="birth_date" name="birth_date" value="{{ session('userToCreate')->birth_date }}">
                            </div>
                        </div>

                        {{-- gender --}}
                        <div class="row">
                            <label for="gender_id" class="col-sm-4 col-form-label text-truncate text-sm-end">Sexe :</label>
                            <div class="col-sm-6">
                                <input type="text" readonly class="form-control-plaintext" id="gender_id" name="gender_id" value="{{ session('userToCreate')->gender_id }}">
                            </div>
                        </div>

                        {{-- email --}}
                        <div class="row">
                            <label for="email" class="col-sm-4 col-form-label text-truncate text-sm-end">Adresse
                                email:</label>
                            <div class="col-sm-6">
                                <input type="text" readonly class="form-control-plaintext" id="email" name="email" value="{{ session('userToCreate')->email }}">
                            </div>
                        </div>

                        {{-- status --}}


                        @if (session('userToCreate'))
                        <div class="row">
                            <div class="col d-grid gap-2 d-block">
                                <button type="submit" class="btn btn-sm btn-success"><i class="bi bi-check2" aria-hidden="true"></i>
                                    Oui : je crée cet utilisateur</button>

                                <a href="{{ url()->previous() }}" class="btn btn-sm btn-danger">
                                    <i class="bi bi-chevron-left" aria-hidden="true"></i>
                                    Non : je ne crée pas cet utilisateur
                                </a>

                            </div>
                        </div>
                        @endif

                        {{-- other hidden column --}}
                        <input type="hidden" name="role_id" value="{{ session('userToCreate')->role_id }}">
                        <input type="hidden" name="civility_id" value="{{ session('userToCreate')->civility_id }}">
                        <input type="hidden" name="gender_id" value="{{ session('userToCreate')->gender_id }}">
                        <input type="hidden" name="other_comment" value="{{ session('userToCreate')->other_comment }}">
                        <input type="hidden" name="health_comment" value="{{ session('userToCreate')->health_comment }}">
                        <input type="hidden" name="address" value="{{ session('userToCreate')->address }}">
                        <input type="hidden" name="postal_code" value="{{ session('userToCreate')->postal_code }}">
                        <input type="hidden" name="city" value="{{ session('userToCreate')->city }}">
                        <input type="hidden" name="country_id" value="{{ session('userToCreate')->country_id }}">
                        <input type="hidden" name="phone_number" value="{{ session('userToCreate')->phone_number }}">

                    </form>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-sm-12">
        <div class="card shadow">
            <div class="card-header text-center">Liste des utilisateurs similaires</div>
            <div class="card-body">
                @if (session('existingUsers'))
                <table class="table table-bordered table-sm table-striped" aria-label="existing users">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Rôle</th>
                            <th>Date de naissance</th>
                        </tr>
                        @foreach (session('existingUsers') as $existingUser)
                        <tr>
                            <td>{{ $existingUser->full_name }}</td>
                            <td>{{ $existingUser->role->name }}</td>
                            <td>{{ $existingUser->birth_date }}</td>
                        </tr>
                        @endforeach
                </table>
                @endif
            </div>
        </div>
    </div>
</div>


@endsection