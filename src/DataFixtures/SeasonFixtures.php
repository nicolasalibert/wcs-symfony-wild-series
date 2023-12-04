<?php

namespace App\DataFixtures;

use App\Entity\Season;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

use Faker\Factory;

class SeasonFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        foreach (ProgramFixtures::PROGRAMS as $programIndex => $programName) {
            for ($i = 1 ; $i <= 5 ; $i++) {
                $season = new Season();
                $season->setNumber($faker->numberBetween(1, 8));
                $season->setYear($faker->numberBetween(1990, 2023));
                $season->setDescription($faker->paragraph(1));
                $season->setProgram($this->getReference('program_' . ProgramFixtures::PROGRAMS[$programIndex]));
                $manager->persist($season);
                $this->addReference($programName . '_season' . $i, $season);
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            ProgramFixtures::class,
        ];
    }
  
}
