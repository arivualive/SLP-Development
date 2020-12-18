<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Currency;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\File\File;


class CurrencyFixtures extends AbstractFixture implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $currency = new Currency();
        $currency->setName('Franc suisse');
        $currency->setIsoCode('ISO 3166');
        $currency->setSymbol('CHF');

        $manager->persist($currency);

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