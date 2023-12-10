<?php

namespace App\Service;

use App\Entity\Program;

class ProgramDuration
{
    public function calculate(Program $program): string
    {
        $duration = 0;
        $seasons = $program->getSeasons();
        foreach ($seasons as $season) {
            $episodes = $season->getEpisodes();
            foreach ($episodes as $episode) {
                $duration += $episode->getDuration();
            }
        }


        return (int)($duration/60) . "h" . $duration%60 . "min";
    }
}