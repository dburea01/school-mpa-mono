<select class="form-select form-select-sm" name="{{ $name }}" id="{{ $id }}">
    <option value="">&nbsp;</option>
    @foreach ($subjects as $subject)
    <option value="{{ $subject->id }}" @if ($subject->id === $value) selected @endif>{{ $subject->name }}
    </option>
    @endforeach
</select>