<x-dashboard>
    <livewire:chart-tile chartFactory="{{ App\Charts\TagsChart::class }}" position="a1:a1" height="85%" />
    <livewire:chart-tile chartFactory="{{ App\Charts\GameDrillChart::class }}" position="a2:a2" height="85%" />
</x-dashboard>