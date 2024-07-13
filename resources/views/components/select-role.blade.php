<select name="{{ $name }}" id="{{ $id }}" class="form-select form-select-sm" aria-label="select role">
    <option value="" @if (''===$value) selected @endif>-- rôle --</option>
    @foreach($roles as $role)
    <option value="{{ $role->name }}" @if ($role->name==$value) selected @endif>{{ $role->name }}</option>
    @endforeach
</select>