<select class="form-select form-select-sm" name="{{ $name }}" id="{{ $id }}">
    <option value="">--</option>
    @foreach ($civilities as $civility)
        <option value="{{ $civility->id }}" @if ($civility->id === $value) selected @endif>{{ $civility->name }}
        </option>
    @endforeach
</select>
