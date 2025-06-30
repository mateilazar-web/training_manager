@php
use App\Enums\UserTeamRole;
@endphp

<x-dashboard>
    @if (Auth::user()
    && Auth::user()->userTeamRoles->count() > 0
    && Auth::user()->userTeamRoles[0]->role !== UserTeamRole::Pending->value)
    <livewire:tile-current-session position="a1:a1" />
    <livewire:chart-tile chartFactory="{{ App\Charts\TagsChart::class }}" position="a2:a2" height="120%" />
    <livewire:chart-tile chartFactory="{{ App\Charts\GameDrillChart::class }}" position="a3:a3" height="120%" />
    @else
    <livewire:chart-tile chartFactory="{{ App\Charts\TagsChart::class }}" position="a1:a1" />
    <livewire:chart-tile chartFactory="{{ App\Charts\GameDrillChart::class }}" position="a2:a2" />
    @endif
</x-dashboard>