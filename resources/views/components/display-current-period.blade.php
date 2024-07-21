@if (isset($currentPeriod))
<span>
    {{ $currentPeriod->name }}
</span>
@else
<span class="text-bg-danger">
    <i class="bi bi-exclamation-triangle-fill"></i>
    Pas de période définie
    <i class="bi bi-exclamation-triangle-fill"></i>
</span>
@endif