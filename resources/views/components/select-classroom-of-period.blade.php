<select name="{{ $name }}" id="{{ $id }}" class="form-select form-select-sm" aria-label="select classroom">
    <option value="" @if (''===$value) selected @endif>-- classe --</option>
    @foreach($classrooms as $classroom)
    <option value="{{ $classroom->id }}" @if ($classroom->id==$value) selected @endif>{{ $classroom->short_name }}</option>
    @endforeach
</select>