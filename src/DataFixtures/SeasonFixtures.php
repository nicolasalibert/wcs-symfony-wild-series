<?php

namespace App\DataFixtures;

use App\Entity\Season;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class SeasonFixtures extends Fixture implements DependentFixtureInterface
{
    const YEARS = [
        1998, 2001, 2003, 2004, 2007, 2011, 2014, 2018, 2022,
    ];

    const DESCRIPTIONS = [
        'Faut bien commencer quelque part...',
        'Patate cette saison gros!!',
        'Ouais c\'est pas la meilleure celle-ci',
        'Ouais elle passe bien cette saison ouais',
        'Dernière saison miam',
    ];

    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        foreach (ProgramFixtures::PROGRAMS as $programIndex => $program) {
            foreach (self::DESCRIPTIONS as $descriptionIndex => $description) {
                $season = new Season();
                $season->setNumber($descriptionIndex);
                $season->setYear(rand(1990, 2023));
                $season->setDescription($description);
                $season->setProgram($this->getReference('program_' . ProgramFixtures::PROGRAMS[$programIndex]));
                $manager->persist($season);
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
