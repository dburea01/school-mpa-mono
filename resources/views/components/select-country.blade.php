<select class="form-select form-select-sm" name="{{ $name }}" id="{{ $id }}">
    <option value="">&nbsp;</option>
    @foreach ($countries as $country)
        <option value="{{ $country->id }}" @if ($country->id === $value) selected @endif>{{ $country->name }}
        </option>
    @endforeach
</select>
