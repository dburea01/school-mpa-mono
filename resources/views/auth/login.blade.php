@extends('layout')

@section('title', 'Se connecter')

@section('content')
<div class="row">
    <div class="col-md-6 offset-md-3">
        <div class="card">
            <div class="card-header text-center">Se connecter</div>

            <div class="card-body">
                @include('errors.messages-error-info')

                <p class="fst-italic">Connectez-vous ici avec votre email.</p>
                <form action="/login" method="POST">

                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                    {{-- email --}}
                    <div class="row mb-1">
                        <label for="email" class="col-sm-4 col-form-label col-form-label-sm text-md-end">Email : *</label>
                        <div class="col-sm-8">
                            <input type="email" required class="form-control form-control-sm @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}">
                            @error('email')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    {{-- password --}}
                    <div class="row mb-1">
                        <label for="password" class="col-sm-4 col-form-label col-form-label-sm text-md-end">Mot de passe : *</label>
                        <div class="col-sm-8">
                            <input type="password" required class="form-control form-control-sm @error('password') is-invalid @enderror" id="password" name="password">
                            @error('password')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    {{-- stay connected --}}
                    <div class="row mb-1">
                        <label for="remember_me" class="col-sm-4 col-form-label col-form-label-sm text-md-end">Rester connecté :</label>
                        <div class="col-sm-8 mt-1">
                            <input class="form-check-input" type="checkbox" value="" id="remember_me" name="remember_me">
                        </div>
                    </div>

                    {{-- submit --}}
                    <div class="row mb-1">
                        <div class="col-sm-8 offset-sm-4 d-grid gap-2">
                            <button class="btn btn-success" type="submit" aria-label="Validate" id="submit">Connection</button>

                            {{--
                            <a href="{{ route('password-lost') }}" class="link-dark">Mot de passe perdu ?</a>
                            --}}
                            <a href="#" class="link-dark">Mot de passe perdu ? (todo)</a>
                        </div>
                    </div>

                </form>

            </div>
        </div>
    </div>


</div>

<div class="row mt-3">
    <div class="col-md-6 offset-md-3">
        <div class="card">
            <div class="card-header text-center">Examples</div>

            <div class="card-body">
                @php
                $admin = App\Models\User::where('role_id', 'ADMIN')->where('login_status_id', 'VALIDATED')->first();
                $teacher = App\Models\User::where('role_id', 'TEACHER')->where('login_status_id', 'VALIDATED')->first();
                $parent = App\Models\User::where('role_id', 'PARENT')->where('login_status_id', 'VALIDATED')->first();
                @endphp


                <table class="table table-bordered table-sm text-center">
                    <tr>
                        <th class="text-end">administrateur</th>
                        <td>{{ $admin->email ?? 'unknown' }}</td>
                    </tr>
                    <tr>
                        <th class="text-end">enseignant</th>
                        <td>{{ $teacher->email ?? 'unknown' }}</td>
                    </tr>
                    <tr>
                        <th class="text-end">parent</th>
                        <td>{{ $parent->email ?? 'unknown' }}</td>
                    </tr>
                </table>


            </div>
        </div>
    </div>
</div>

@endsection