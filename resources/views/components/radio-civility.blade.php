@foreach ($civilities as $civility)
    <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="civility_id" id="{{ $civility->id }}"
            value="{{ $civility->id }}" @if ($value == $civility->id) checked @endif>
        <label class="form-check-label" for="{{ $civility->id }}">{{ $civility->short_name }}</label>
    </div>
@endforeach
