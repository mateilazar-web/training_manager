@php
use App\Enums\UserTeamRole;
@endphp

<x-dashboard>
    @if (Auth::user()
    && Auth::user()->userTeamRoles->count() > 0
    && Auth::user()->userTeamRoles[0]->role !== UserTeamRole::Pending->value)
    <livewire:tile-current-session position="a1:d1" />
    <livewire:chart-tile chartFactory="{{ App\Charts\TagsChart::class }}" position="a2:b2" height="120%" />
    <livewire:chart-tile chartFactory="{{ App\Charts\GameDrillChart::class }}" position="c2:d2" height="120%" />
    @else
    <livewire:chart-tile chartFactory="{{ App\Charts\TagsChart::class }}" position="a1:a1" height="200%" />
    <livewire:chart-tile chartFactory="{{ App\Charts\GameDrillChart::class }}" position="b1:b1" height="200%" />
    @endif
</x-dashboard>