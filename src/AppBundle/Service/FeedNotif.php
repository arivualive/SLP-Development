<?php
// src/AppBundle/Service/FeedNotif.php
namespace AppBundle\Service;

use AppBundle\Entity\Feed;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class FeedNotif extends Controller{

    private $em ;
    

    public function __construct(ObjectManager $em)
    {
        $this->em= $em;
    }

    public function createWelcomeUser($userSlp){
        
        $feed = new Feed;
        $date = new \DateTime() ;
    
        $feed->setTitre('Bienvenue')
                    ->setDescription('Blablabla')
                    ->setUser($userSlp)
                    ->setCategorie('Welcome')
                    ->setImage('urlImage1')
                    ->setStatus(0)
                    ->setPriorite(0)
                    ->setUpdateAt($date);
                $this->em->persist($feed);
                $this->em->flush();
    }

    public function createMajSocioQuestion($userSlp)
    {
        $feed = new Feed;
        $date = new \DateTime() ;         
            $feed->setTitre('Maj Questionnaire ')
                    ->setDescription('nouvelle maj')
                    ->setUser($userSlp)
                    ->setCategorie('questionnaire-socio')
                    ->setImage('urlImage1')
                    ->setStatus(1)
                    ->setPriorite(1)
                    ->setUpdateAt($date);
            $this->em->persist($feed);        
            $this->em->flush(); 
    }

    public function removeMajSocioQuestion($feedTab){
                                                          
            foreach($feedTab as $value) {
                $this->em->remove($value);
                $this->em->flush(); 
            } 
             
    }
}