<?php

namespace App\DataFixtures;

use App\Entity\Episode;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

use Faker\Factory;
use Symfony\Component\String\Slugger\SluggerInterface;

class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{
    private $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        
        foreach (ProgramFixtures::PROGRAMS as $programName) {
            for ($seasonIndex = 1 ; $seasonIndex <= 5 ; $seasonIndex++) {
                for ($episodeIndex = 1 ; $episodeIndex <= 10 ; $episodeIndex++) {
                    $episode = new Episode();
                    $episode->setTitle($faker->sentence(4));
                    $episode->setSynopsis($faker->paragraph(3));
                    $episode->setSeason($this->getReference($programName . '_season' . $seasonIndex));
                    $episode->setNumber($episodeIndex);
                    $episode->setDuration(rand(15, 60));
                    $slug = $this->slugger->slug($episode->getTitle());
                    $episode->setSlug($slug);
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
