<?php

namespace App\Charts;

use App\Enums\UserTeamRole;
use App\Models\SessionDrill;
use App\Models\Tag;
use Fidum\ChartTile\Charts\Chart;
use Fidum\ChartTile\Contracts\ChartFactory;
use Illuminate\Support\Facades\Auth;

class TagsChart implements ChartFactory
{
    public static function make(array $settings): ChartFactory
    {
        return new self();
    }

    public function chart(): Chart
    {
        $chart = new Chart();

        $tags = Tag::query()
            ->select('id', 'name')
            ->where("name", "!=", "Game")
            ->where("name", "!=", "Drill")
            ->get();

        $data = [];

        foreach ($tags as $tag) {
            $sessionDrills = SessionDrill::query()
                ->select('session_drills.id')
                ->join("drills", "drills.id", "=", "session_drills.drill_id")
                ->join("drill_tags", "drill_tags.drill_id", "=", "drills.id")
                ->join("tags", "drill_tags.tag_id", "=", "tags.id")
                ->join("sessions", "sessions.id", "=", "session_drills.session_id")
                ->where("drill_tags.tag_id", $tag->id);
            
            if (!empty(Auth::user())) {
                /** @var \App\Models\User $authenticatedUser */
                $authenticatedUser = Auth::user();

                if (count($authenticatedUser->userTeamRoles) > 0) {
                    if ($authenticatedUser->userTeamRoles[0]->role !== UserTeamRole::Pending->value) {
                        $sessionDrills = $sessionDrills->where("sessions.user_id", $authenticatedUser->id);
                    }
                }
            }

            $data[] = $sessionDrills->count();
        }

        $total = array_sum($data);

        if (!empty($total)) {
            $data = array_map(
                function ($element, $total) {
                    return (int)($element * 100 / $total);
                },
                $data,
                array_fill(0, count($data), $total)
            );
        }

        $chart->labels($tags->pluck('name'))
            ->options([
                'responsive' => true,
                'maintainAspectRatio' => true,
                'tooltip' => [
                    'show' => true,
                ],
                'animation' => [
                    'duration' => 0,
                ],
                'title' => [
                    'display' => true,
                    'text' => 'Session drills by type',
                    'position' => 'top',
                ],
                'legend' => [
                    'display' => true,
                    'position' => 'bottom',
                    
                ],
                
                'scales' => [
                    'xAxes' => ['display' => false],
                    'yAxes' => ['display' => false],
                ],
            ])
            ->dataset('Tags', 'doughnut', $data)
            ->backgroundColor(['#FF9CEE', '#B28DFF', '#6EB5FF', '#BFFCC6', '#FFFF83', '#FFD683', '#FF8398', '#AE513D', '#A4E6E8']);
        #
        return $chart;
    }
}
