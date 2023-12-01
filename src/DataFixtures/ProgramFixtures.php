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
        'Vikings',
        'Wilfred'
    ];

    const SYNOPSIS = [
        'Coolos !',
        'Ouais bonne séries ça',
        'Pas ouf gros',
        'Ça ça déboite cousin',
        'Pas mal celle ci',
        'En fait c\'est un avis pas un synopsis ça merde',
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::PROGRAMS as $programName) {
            $program = new Program();
            $program->setTitle($programName);
            $program->setCategory($this->getReference('category_' . CategoryFixtures::CATEGORIES[array_rand(CategoryFixtures::CATEGORIES)]));
            $program->setSynopsis(
                self::SYNOPSIS[array_rand(self::SYNOPSIS)]);
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
