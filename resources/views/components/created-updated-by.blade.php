<em style="font-size: 10px">

    @if ($model->created_by)
        Création par : <strong>{{ $model->created_by }} ({{ $model->created_at->format('d/m/Y H:i') }})</strong>
    @endif
    <br>

    @if ($model->updated_by)
        Modification par : <strong>{{ $model->updated_by }} ({{ $model->updated_at->format('d/m/Y H:i') }})</strong>
    @endif
</em>
