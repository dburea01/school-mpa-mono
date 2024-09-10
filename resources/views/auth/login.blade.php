@extends('layout')

@section('title', 'Se connecter')

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card shadow">
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





    <div class="col-md-6">
        <div class="card mt-3 mt-md-0 shadow">
            <div class="card-header text-center">Examples de profils pour tester</div>

            <div class="card-body">
                @php
                $admin = App\Models\User::where('role_id', 'ADMIN')->where('login_status_id', 'VALIDATED')->first();
                $teachers = App\Models\User::where('role_id', 'TEACHER')->where('login_status_id', 'VALIDATED')->limit(3)->get();

                $userGroup = App\Models\UserGroup::selectRaw('count(*) as quantity , group_id')->groupBy('group_id')->having('quantity', '>', 3)->first();
                $groupId = $userGroup->group_id;

                $parents = DB::table('users')->join('user_groups', 'user_groups.user_id', 'users.id')->where('users.role_id', 'PARENT')->where('user_groups.group_id', $groupId)->select('users.*')->get();
                foreach($parents as $parent){
                App\Models\User::where('id', $parent->id)->update(['login_status_id' => 'VALIDATED']);
                }

                $students = DB::table('users')->join('user_groups', 'user_groups.user_id', 'users.id')->where('users.role_id', 'STUDENT')->where('user_groups.group_id', $groupId)->select('users.*')->get();
                foreach($students as $student){
                App\Models\User::where('id', $student->id)->update(['login_status_id' => 'VALIDATED']);
                }
                @endphp

                <div class="border p-3 mb-3">
                    <strong>Administrateur : {{ $admin->last_name }} {{ $admin->first_name }}</strong><br>
                    {{ $admin->email ?? 'unknown' }}
                    <button type="button" class="login" data-email="{{ $admin->email}}">Login</button>
                </div>

                <div class="border p-3 mb-3">
                    @foreach($teachers as $teacher)
                    <strong>Enseignant {{ $loop->index + 1}} : {{ $teacher->last_name }} {{ $teacher->first_name }}</strong><br>
                    {{ $teacher->email ?? 'unknown' }}
                    <button type="button" class="login" data-email="{{ $teacher->email}}">Login</button><br><br>
                    @endforeach
                </div>

                <div class="border p-3 mb-3">
                    @foreach($parents as $parent)
                    <strong>Parent {{ $loop->index + 1}} : {{ $parent->last_name }} {{ $parent->first_name }}</strong><br>
                    {{ $parent->email ?? 'unknown' }}
                    <button type="button" class="login" data-email="{{ $parent->email}}">Login</button><br><br>
                    @endforeach

                    @foreach($students as $student)
                    <strong>Elève {{ $loop->index + 1}} : {{ $student->last_name }} {{ $student->first_name }}</strong><br>
                    {{ $student->email ?? 'unknown' }}
                    <button type="button" class="login" data-email="{{ $student->email}}">Login</button><br><br>
                    @endforeach
                </div>


            </div>
        </div>
    </div>
</div>

@endsection

@section('extra_js')
<script>
    $(document).ready(function() {

        $('.login').click(function() {

            let email = $(this).attr('data-email')
            console.log('email ' + email)
            $('#email').val(email)
            $('#password').val('password')
            $('#submit').click()


        })


    });
</script>
@endsection