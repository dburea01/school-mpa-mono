@foreach ($users as $user)
    @if($user->role_id == 'STUDENT')
    <span class="badge text-bg-secondary">
        {{ $user->full_name }}
    </span>
    @endif

    @if($user->role_id == 'PARENT')
    <span class="badge text-bg-info">
        {{ $user->full_name_with_civility }}
    </span>
    @endif
@endforeach
