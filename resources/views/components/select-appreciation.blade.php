<select class="form-select form-select-sm" name="{{ $name }}" onchange="{{ $onchange }}">
    <option value="">-- appreciation --</option>
    @foreach ($appreciations as $appreciation)
        <option value="{{ $appreciation->id }}" @if ($appreciation->id == $value) selected @endif>
            {{ $appreciation->name }}
        </option>
    @endforeach
</select>
