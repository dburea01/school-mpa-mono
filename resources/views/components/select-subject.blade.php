<select class="form-select form-select-sm" name="{{ $name }}" id="{{ $id }}" @if ($required) required @endif>
    <option value="">{{ $placeholder }}</option>
    @foreach ($subjects as $subject)
    <option value="{{ $subject->id }}" @if ($subject->id === $value) selected @endif>{{ $subject->name }}
    </option>
    @endforeach
</select>