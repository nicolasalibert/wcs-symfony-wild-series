<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Actor;
use Faker\Factory;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ActorFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 0 ; $i < 10 ; $i++) {
            $actor = new Actor();
            $actor->setName($faker->name());
            for ($y = 0 ; $y < 3 ; $y++) {
                $program = $this->getReference('program_' . ProgramFixtures::PROGRAMS[array_rand(ProgramFixtures::PROGRAMS)]);
                $actor->addProgram($program);
            }
            $manager->persist($actor);
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
