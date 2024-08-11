<table class="table table-sm table-bordered">
    @foreach($repartitionByRole as $role)
    <tr>
        <th>{{ $role->role_name }}</th>
        <td>{{ $role->quantity }}</td>
    </tr>
    @endforeach
</table>

<table class="table table-sm table-bordered">

    @foreach($repartitionByGender as $gender)
    <tr>
        <th>@switch($gender->gender_id)
                                @case(1)
                                <i class="bi bi-gender-male"></i>
                                @break
                                @case(2)
                                <i class="bi bi-gender-female"></i>
                                @break
                                @default
                                ?
                                @endswitch</th>
        <td>{{ $gender->quantity }}</td>
    </tr>
    @endforeach
</table>