<?php

namespace AppBundle\DataFixtures\ORM;


use AppBundle\Entity\Actuality;
use AppBundle\Entity\SectionActuality;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\File\File;


class LoadActuality  extends AbstractFixture implements FixtureInterface
{


    public function load(ObjectManager $manager)
    {
        $section = new SectionActuality();
        $section->setSection(array('ConsomAction'));
        $section->setUrl('web/images/actualite/logo_consom_Action.png');
        $section->setAlt('nom de l\'image de la section');

        $section2 = new SectionActuality();
        $section2->setSection(array('Repertoire vert'));
        $section2->setUrl('web/images/actualite/repertoirevert1.jpg');
        $section2->setAlt('nom de l\'image de la section');


        $manager->persist($section);
        $manager->persist($section2);

        // load Actuality
        $actuality = new Actuality();
        $actuality->setTitle('Article numéro 1');
        $actuality->setDescription('Description de l\'article 1');
        $actuality->setSection($section->getSection());
        $actuality->setAlt('nom de l\'image de l\'article');
        $actuality->setUrl('url de l\'image');


        $actuality2 = new Actuality();
        $actuality2->setTitle('Article numéro 2');
        $actuality2->setDescription('Description de l\'article 2');
        $actuality2->setSection($section2->getSection());
        $actuality2->setAlt('nom de l\'image de l\'article');
        $actuality2->setUrl('url de l\'image');

        $manager->persist($actuality);
        $manager->persist($actuality2);



        $manager->flush();
    }

}