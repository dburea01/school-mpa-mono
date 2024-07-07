<select name="{{ $name }}" id="{{ $id }}" class="form-select form-select-sm" aria-label="select status">
    <option value="" @if (''===$value) selected @endif>-- tous status --</option>
    {{--
    <option value="CREATED" @if ('CREATED'===$value) selected @endif>CREATED</option>
    <option value="VALIDATED" @if ('VALIDATED'===$value) selected @endif>VALIDATED</option>
    <option value="BLOCKED" @if ('BLOCKED'===$value) selected @endif>BLOCKED</option>
    --}}

    @foreach($loginStatuses as $loginStatus)
    <option value="{{ $loginStatus->id }}" @if ($loginStatus->id==$value) selected @endif>{{ $loginStatus->name }}</option>
    @endforeach
</select>