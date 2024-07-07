@extends('layout')

@section('title', 'Liste des utilisateurs')

@section('content')

@include('errors.session-values')

<div class="row">


    <div class="col-md-3">
        <div class="card shadow">
            <div class="card-header text-center">Filtres</div>
            <div class="card-body">
                <form class="row" action="{{ route('users.index') }}" id="form-users">
                    <div class="mt-1">
                        <input type="text" class="form-control form-control-sm mr-sm-2" name="name" id="name" placeholder="Filtrer par nom" value="{{ $name }}">
                    </div>

                    <div class="mt-1">
                        <input type="text" class="form-control form-control-sm mr-sm-2" name="email" id="email" placeholder="Filtrer par email" value="{{ $email }}">
                    </div>

                    <div class="mt-1">
                        <select name="provider" id="provider" class="form-select form-select-sm" aria-label="select provider">
                            <option value="" @if (''===$provider) selected @endif>-- tous provider --</option>
                            <option value="local" @if ('local'===$provider) selected @endif>local</option>
                            <option value="google" @if ('google'===$provider) selected @endif>google</option>
                        </select>
                    </div>

                    <div class="mt-1">
                        <x-select-login-status :value="$login_status_id" name="login_status_id" id="login_status_id" />
                    </div>

                    <input type="hidden" name="mode" id="mode" value="{{ $mode }}">
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
                Liste des utilisateurs ({{ $users->count() }}/{{ $users->total() }})
                {{--
                <div class="btn-group btn-group-sm" role="group" aria-label="display mode">
                    <button type="button" class="btn btn-outline-primary mode @if($mode == 'photo') active @endif" data-mode="photo" aria-label="mode photo"><i class="bi bi-person-vcard"></i></button>
                    <button type="button" class="btn btn-outline-primary mode @if($mode == 'table') active @endif" data-mode="table" aria-label="mode table"><i class="bi bi-card-list"></i></button>
                </div>
                --}}
            </div>
            <div class="card-body">

                @if($mode == 'table')
                <div class="table-responsive">
                    <table class="table table-striped table-sm table-bordered table-hover">
                        {{--
                    <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Email</th>
                                <th>Login Status</th>
                                <th>&nbsp;</th>
                            </tr>
                        </thead>
                        --}}
                        <tbody>
                            @foreach ($users as $user)
                            <tr>
                                <td><a href="{{ route('users.edit', ['user' => $user]) }}">{{ $user->name }}</a></td>
                                <td>{{ $user->email }} @if ($user->provider == 'google') <i class="bi bi-google"></i> @endif</td>
                                <td>{{ $user->login_status_id }}</td>
                                {{--<td><button type="button" class="btn btn-danger btn-sm btn-delete-user" data-bs-toggle="modal" data-bs-target="#exampleModal" data-userid="{{$user->id}}" data-username="{{$user->name}}" aria-label="delete user">
                                <i class="bi bi-trash"></i>
                                </button>
                                </td>--}}
                                <td style="cursor: pointer;">
                                    <i class="bi bi-trash btn-delete-user text-danger" data-bs-toggle="modal" data-bs-target="#exampleModal" data-userid="{{$user->id}}" data-username="{{$user->name}}">
                                    </i>

                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif

                @if ($mode == 'photo')
                <div class="table-responsive">
                    <table class="table table-striped table-sm">
                        @foreach ($users as $user)
                        <tr>
                            <td class="align-middle">
                                <img style="width: 100px;" src="@if ($user->photo_url) {{ $user->photo_url }} @else {{ asset('img/user.png') }} @endif" alt="image not found" class="border">
                            </td>
                            <td class="align-middle">
                                <h3><a href="{{ route('users.edit', ['user' => $user]) }}">{{ $user->name }}</a></h3>
                                <h4>{{ $user->email }} @if ($user->provider == 'google') <i class="bi bi-google"></i> @endif</h4>
                                <h6>{{ $user->login_status_id }}</h6>

                            </td>


                        </tr>
                        @endforeach
                    </table>
                </div>
                @endif

                <div class="row">
                    {{ $users->withQueryString()->links() }}
                </div>

            </div>


        </div>

    </div>

    {{-- display modal to delete user --}}
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Supprimer <span id="user-name-to-delete"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>La suppression est irréversible. Veuillez confirmer votre choix.</p>
                    <p>Si votre souhait est que cet utilisateur ne puisse plus se connecter, vous pouvez également modifier son status en "BLOCKED" sans le supprimer.</p>
                </div>
                <div class="modal-footer">
                    <form id="btn-confirm-delete-user" class="form-inline" method="POST">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        @method('DELETE')
                        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal"><i class="bi bi-chevron-left"></i> Annuler</button>
                        <button type="submit" class="btn btn-sm btn-danger ml-3"><i class="bi bi-trash"></i> Confirmer suppression</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection

@section('extra_js')
<script>
    $(document).ready(function() {

        $('.btn-delete-user').click(function() {
            let userId = $(this).attr('data-userid')
            let userName = $(this).attr('data-username')

            $('#user-name-to-delete').text(userName)
            $('#btn-confirm-delete-user').attr('action', '/users/' + userId)
        })

        $('.mode').click(function() {
            let mode = $(this).attr('data-mode')
            $('#mode').val(mode)
            $('#form-users').submit()
        })

    });
</script>
@endsection