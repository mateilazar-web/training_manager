@php
use App\Enums\UserTeamRole;
@endphp

<x-dashboard>
    <livewire:tile-total-number-of-users position="a1:a1" />
    <livewire:tile-total-number-of-teams position="a2:a2" />
     @if (Auth::user()
        && Auth::user()->userTeamRoles->count() > 0
        && Auth::user()->userTeamRoles[0]->role !== UserTeamRole::Pending->value)
            <livewire:tile-current-session position="a3:a6" />
    @endif
</x-dashboard>