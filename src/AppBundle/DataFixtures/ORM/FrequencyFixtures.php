<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Frequency;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\File\File;


class FrequencyFixtures extends AbstractFixture implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $frequency = new Frequency();
        $frequency->setName('mensuel');

        $manager->persist($frequency);

        $manager->flush();
    }

    /* Fixture(s) à charger avant celle-ci :
    public function getDependencies()
    {
        return array(
            UserSlpFixtures::class,
        );
    }
*/
}