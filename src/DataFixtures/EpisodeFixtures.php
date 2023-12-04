<?php

namespace App\DataFixtures;

use App\Entity\Episode;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{
    const SYNOPSIS = [
        'Episode pilote, coolos',
        'Rohlala le suspens khoya',
        'Ouais ouais sapass',
        'On s\'ennuie un peu là...',
        'Super épisode!',
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (ProgramFixtures::PROGRAMS as $programIndex => $programName) {
            for ($seasonIndex = 1 ; $seasonIndex <= 5 ; $seasonIndex++) {
                for ($episodeIndex = 1 ; $episodeIndex <= 3 ; $episodeIndex++) {
                    $episode = new Episode();
                    $episode->setTitle('Episode ' . $episodeIndex . ' : un épisode que il est bien');
                    $episode->setSynopsis(EpisodeFixtures::SYNOPSIS[$episodeIndex]);
                    $episode->setSeason($this->getReference($programName . '_season' . $seasonIndex));
                    $episode->setNumber($episodeIndex);
                    $manager->persist($episode);
                }
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            SeasonFixtures::class
        ];
    }
}
