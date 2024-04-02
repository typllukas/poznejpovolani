<?php

namespace App\DataFixtures;

use App\Entity\Module;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ModuleFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $module1 = new Module();
        $module1->setModule('Pondělky');
        $module1->setAbbreviation('PO');
        $manager->persist($module1);

        $module2 = new Module();
        $module2->setModule('Středy');
        $module2->setAbbreviation('ST');
        $manager->persist($module2);

        $module3 = new Module();
        $module3->setModule('Fyzika a chemie');
        $module3->setAbbreviation('FYZ-CHEM');
        $manager->persist($module3);

        $module4 = new Module();
        $module4->setModule('Informatika');
        $module4->setAbbreviation('INF');
        $manager->persist($module4);

        $module5 = new Module();
        $module5->setModule('Průmysl a strojírenství');
        $module5->setAbbreviation('PS');
        $manager->persist($module5);

        $module6 = new Module();
        $module6->setModule('Zdravotnictví');
        $module6->setAbbreviation('ZDR');
        $manager->persist($module6);

        $module7 = new Module();
        $module7->setModule('Stavebnictví');
        $module7->setAbbreviation('STAV');
        $manager->persist($module7);

        $manager->flush();
    }
}
