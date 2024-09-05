<select aria-label="appreciation" class="form-select form-select-sm" name="{{ $name }}" id="{{ $id }}" onchange="{{ $onchange }}">
    <option value="">-- appreciation --</option>
    @foreach ($appreciations as $appreciation)
    <option value="{{ $appreciation->id }}" @if ($appreciation->id == $value) selected @endif>
        {{ $appreciation->name }}
    </option>
    @endforeach
</select>