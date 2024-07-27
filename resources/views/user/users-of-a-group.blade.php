@extends('layout')
@section('title', "utilisateurs du groupe $group->name")

@section('content')
@include('errors.session-values')



<div class="row">
    <div class="col">

        <div class="card">
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
                                <button type="button" class="btn btn-sm btn-danger" title="Retirer utilisateur du groupe {{ $group->name }}" data-bs-toggle="modal" data-bs-target="#modalRemoveUserFromGroup_{{ $userOfTheGroup->id }}" aria-label="remove">
                                    <i class="bi bi-person-dash" aria-hidden="true"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <div class="col">

        <div class="card">
            <div class="card-header text-center">Rechercher utilisateurs</div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-10">
                        <form class="row" action="{{ route('groups.users.create', ['group' => $group]) }}" aria-label="search">
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
                                <form action="{{ route('groups.users.store', ['group'=> $group]) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="user_id" value="{{ $userFiltered->id }}">
                                    <input type="hidden" name="name" value="{{ $name }}">
                                    <button type="submit" class="btn btn-sm btn-success" aria-label="add" title="Ajouter utilisateur au groupe {{ $group->name }}">
                                        <i class="bi bi-person-plus" aria-hidden="true"></i> </button>
                                </form>
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

<!-- Modal -->
@foreach ($groupWithUsers->users as $userOfTheGroup)
<div class="modal fade" id="modalRemoveUserFromGroup_{{ $userOfTheGroup->id }}" tabindex="-1" aria-labelledby="modalLabel_{{ $userOfTheGroup->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel_{{ $userOfTheGroup->id }}">
                    Retirer utilisateur du groupe {{ $group->name }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Attention, vous vous apprétez à retirer <strong>{{ $userOfTheGroup->full_name }}</strong> du
                    groupe
                    <strong>{{ $group->name }}</strong>.

                </p>
                <p class="text-danger">L'utilisateur ne sera pas supprimé. Seul son lien avec ce groupe sera
                    supprimé.</p>
                <p>Veuillez confirmer.</p>
            </div>
            <div class="modal-footer">
                <form class="form-inline" method="POST" action="groups/{{ $group->id }}/users/{{ $userOfTheGroup->id }}?name={{ $name }}">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal"><i class="bi bi-chevron-left" aria-hidden="true"></i>
                        Abandonner</button>
                    <button type="submit" class="btn btn-sm btn-danger ml-3"><i class="bi bi-trash" aria-hidden="true"></i>
                        Retirer utilisateur du groupe</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach

@endsection