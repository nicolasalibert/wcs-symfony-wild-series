<?php

namespace App\DataFixtures;

use App\Entity\Episode;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

use Faker\Factory;

class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        
        foreach (ProgramFixtures::PROGRAMS as $programName) {
            for ($seasonIndex = 1 ; $seasonIndex <= 5 ; $seasonIndex++) {
                for ($episodeIndex = 1 ; $episodeIndex <= 10 ; $episodeIndex++) {
                    $episode = new Episode();
                    $episode->setTitle($faker->sentence(12));
                    $episode->setSynopsis($faker->paragraph(3));
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
