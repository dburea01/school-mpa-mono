<select class="form-select form-select-sm" name="{{ $name }}" id="{{ $id }}" @if($required) required @endif>
    <option value="">{{ $placeholder }}</option>
    @foreach ($workTypes as $workType)
        <option value="{{ $workType->id }}" @if ($workType->id == $value) selected @endif>{{ $workType->short_name }}
        </option>
    @endforeach
</select>
