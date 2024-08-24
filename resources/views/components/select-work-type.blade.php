<select class="form-select form-select-sm" name="{{ $name }}" id="{{ $id }}">
    <option value="">{{ $placeholder }}</option>
    @foreach ($workTypes as $workType)
        <option value="{{ $workType->id }}" @if ($workType->id == $value) selected @endif>{{ $workType->short_name }}
        </option>
    @endforeach
</select>
