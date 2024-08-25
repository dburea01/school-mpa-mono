<select class="form-select form-select-sm" name="{{ $name }}" id="{{ $id }}" @if($required) required @endif>
    <option value="">{{ $placeholder }}</option>
    @foreach ($workStatuses as $workStatus)
    <option value="{{ $workStatus->id }}" @if ($workStatus->id == $value) selected @endif>{{ $workStatus->name }}
    </option>
    @endforeach
</select>