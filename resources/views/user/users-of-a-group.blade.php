@extends('layout')
@section('title', "utilisateurs du groupe $group->name")

@section('content')
@include('errors.session-values')



<div class="row">
    <div class="col-md-6">

        <div class="card shadow">
            <div class="card-header text-center">Utilisateurs du groupe {{$group->name}}
                <strong>({{ count($groupWithUsers->users) }})</strong>
            </div>
            <div class="card-body">
                <table class="table table-sm table-hover table-bordered" aria-label="list of the users of the group">
                    <thead>
                        <tr>
                            <th>Utilisateur</th>
                            <th>Rôle</th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($groupWithUsers->users as $userOfTheGroup)
                        <tr>
                            <td>
                                {{ $userOfTheGroup->full_name }}
                                @if ($userOfTheGroup->status_id === 'INACTIVE')
                                <x-alert-inactive alert="utilisateur inactif" />
                                @endif
                            </td>
                            <td>{{ $userOfTheGroup->role->name }}</td>
                            <td>
                                @can('delete', App\Models\UserGroup::class)
                                <form class="form-inline" method="POST" action="/groups/{{ $group->id }}/users/{{ $userOfTheGroup->id }}?name={{ $name }}">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    @method('DELETE')

                                    <i class="bi bi-person-dash" aria-hidden="true" style="cursor: pointer;" onclick="this.closest('form').submit();" title="Retirer utilisateur du groupe {{ $group->name }}"></i>
                                </form>
                                @endcan
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <div class="col-md-6">

        <div class="card shadow">
            <div class="card-header text-center">Rechercher utilisateurs</div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-10">
                        <form class="row" action="{{ route('groups.users.index', ['group' => $group]) }}" aria-label="search">
                            <div class="col-md-6 col-sm-12">
                                <input type="text" class="form-control form-control-sm" name="name" placeholder="Nom..." value="{{ $name }}">
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <button type="submit" class="btn btn-primary btn-sm btn-block"><i class="bi bi-funnel" aria-hidden="true"></i> Filtrer
                                </button>
                            </div>

                        </form>
                    </div>
                </div>


                <table class="table table-sm table-hover table-bordered" aria-label="users filtered">
                    <thead>
                        <tr>
                            <th>Utilisateur</th>
                            <th>Rôle</th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($usersFiltered as $userFiltered)
                        <tr>
                            <td>
                                {{ $userFiltered->full_name }}
                                @if ($userFiltered->status_id === 'INACTIVE')
                                <x-alert-inactive alert="Utilisateur inactif" />
                                @endif
                            </td>
                            <td>{{ $userFiltered->role->name }}</td>
                            <td>
                                @if (
                                $groupWithUsers->users->doesntcontain(function ($userOfTheGroup) use ($userFiltered) {
                                return $userOfTheGroup->id === $userFiltered->id;
                                }))
                                @can('create', App\Models\UserGroup::class)
                                <form action="/groups/{{ $group->id }}/users?name={{$name}}" method="POST">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="user_id" value="{{ $userFiltered->id }}">
                                    <input type="hidden" name="name" value="{{ $name }}">
                                    <i class="bi bi-person-plus" aria-hidden="true" style="cursor: pointer;" onclick="this.closest('form').submit();" title="Ajouter utilisateur au groupe {{ $group->name }}"></i>
                                    {{--
                                    <button type="submit" class="btn btn-sm btn-success" aria-label="add" title="Ajouter utilisateur au groupe {{ $group->name }}">
                                    <i class="bi bi-person-plus" aria-hidden="true"></i> </button>
                                    --}}
                                </form>
                                @else
                                @endcan
                                @endif

                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>





            </div>
        </div>
    </div>
</div>

@endsection