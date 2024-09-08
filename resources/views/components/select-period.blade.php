<select class="form-select form-select-sm" name="{{ $name }}" id="{{ $id }}" @if ($required) required @endif>
    <option value="">{{ $placeholder }}</option>
    @foreach ($periods as $period)
    <option value="{{ $period->id }}" @if ($period->id === $value) selected @endif>{{ $period->name }}
    </option>
    @endforeach
</select>