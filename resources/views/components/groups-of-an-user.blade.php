@foreach ($groups as $group)
    <span class="badge text-bg-info">
        {{ $group->name }}
    </span>
@endforeach
