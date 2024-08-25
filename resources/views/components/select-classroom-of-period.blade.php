<select name="{{ $name }}" id="{{ $id }}" class="form-select form-select-sm" aria-label="select classroom" @if ($required) required @endif>
    <option value="" @if (''===$value) selected @endif>{{ $placeholder }}</option>
    @foreach($classrooms as $classroom)
    <option value="{{ $classroom->id }}" @if ($classroom->id==$value) selected @endif>{{ $classroom->short_name }}</option>
    @endforeach
</select>