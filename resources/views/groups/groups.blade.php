@extends('layout')

@section('title', 'Liste des groupes')

@section('content')

@include('errors.session-values')

<div class="row">


    <div class="col-md-3">
        <div class="card shadow">
            <div class="card-header text-center">Filtres</div>
            <div class="card-body">
                <form class="row" action="{{ route('groups.index') }}">
                    <div class="mt-1">
                        <input type="text" class="form-control form-control-sm mr-sm-2" name="name" id="name" placeholder="Filtrer par nom" value="{{ $name }}">
                    </div>

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
                Liste des groupes ({{ $groups->count() }}/{{ $groups->total() }})
                @can('create', App\Models\Group::class)
                <a href="{{ route('groups.create') }}">Créer groupe</a>
                @endcan
            </div>
            <div class="card-body">

                <div class="table-responsive">
                    <table class="table table-striped table-sm table-bordered table-hover">

                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>&nbsp;</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($groups as $group)
                            <tr>
                                <td>
                                    @can('updateGroup')
                                    <a href="{{ route('groups.edit', ['group' => $group]) }}">{{ $group->name }}</a>
                                    @else
                                    {{ $group->name }}
                                    @endcan
                                </td>

                                <td>
                                    @can('deleteGroup')
                                    <i class="bi bi-trash btn-delete-group text-danger" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#exampleModal" data-groupid="{{$group->id}}" data-groupname="{{$group->name}}"></i>
                                    @endcan
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>


                <div class="row">
                    {{ $groups->withQueryString()->links() }}
                </div>

            </div>


        </div>

    </div>

    {{-- display modal to delete group --}}
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Supprimer groupe <strong><span id="group-name-to-delete"></span></strong></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>La suppression est irréversible. Veuillez confirmer votre choix.</p>
                    <p>La suppression du groupe n'entraîne pas la suppression des utilisateurs qui y appartiennent.</p>
                </div>
                <div class="modal-footer">
                    <form id="btn-confirm-delete-group" class="form-inline" method="POST">
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

        $('.btn-delete-group').click(function() {
            let groupId = $(this).attr('data-groupid')
            let groupName = $(this).attr('data-groupname')

            $('#group-name-to-delete').text(groupName)
            $('#btn-confirm-delete-group').attr('action', '/groups/' + groupId)
        })

    });
</script>
@endsection