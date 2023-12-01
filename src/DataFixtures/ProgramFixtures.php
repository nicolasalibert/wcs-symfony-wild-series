<?php

namespace App\DataFixtures;

use App\Entity\Program;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ProgramFixtures extends Fixture implements DependentFixtureInterface
{
    const PROGRAMS = [
        'BoJack Horseman',
        'Mr Robot',
        'How I Met Your Mother',
        'Breaking Bad',
        'American Horror Story',
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::PROGRAMS as $programName) {
            $program = new Program();
            $program->setTitle($programName);
            $program->setCategory($this->getReference('category_Action'));
            $program->setSynopsis('Coolos!');
            $manager->persist($program);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            CategoryFixtures::class,
        ];
    }
}
