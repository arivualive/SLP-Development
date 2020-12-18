<?php
// src/AppBundle/Service/SpendingService.php
namespace AppBundle\Service;

use AppBundle\Entity\Spending;
use AppBundle\Entity\UserSlp;
use AppBundle\Entity\Cost;
use AppBundle\Entity\BudgetTheme;


use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use AppBundle\Repository\UserSlpRepository;

class SpendingService extends Controller {
    public function test() {
        $userSlpRepo = $this->getDoctrine()->getRepository(UserSlp::class);
        
        $UserConecte = $userSlpRepo->findOneByGaeaUserId($this->getUser()->getId());
        
        return $UserConecte;
    }

    /*public function calculateTotalUserSpendings() {
        
        $entityManager = $this->getDoctrine()->getManager();
        
        $userSlpRepo = $this->getDoctrine()->getRepository(UserSlp::class);
        
        $UserConecte = $userSlpRepo->findOneByGaeaUserId($this->getUser()->getId());

        $UserSpendings = $entityManager->getRepository(Spending::class)->findSlpUserSpendings($UserConecte);

        $totalCost = 0;

        foreach ($UserSpendings as $UserSpending) {
            $cost = $UserSpending->getCost()->getPrice();
            $quantity = $UserSpending->getQuantity()->getNumber();
            $amount = $cost * $quantity;
            $totalCost = $totalCost+$amount;
        }
        return $totalCost;
        
    }*/

    public function calculateTotalUserSpendingsByFrequency($frequency) {

        $actualMonth = date('n');
        $actualWeekMonth = date('n');
        $actualDay = date('d');
        $actualDayOfWeek = date('w');
        $monthNumberDay = date('t');

        //Vérification du jour de la semaine, verification si la semaine et sur 2 mois différent ou sur le meme mois
        $firstDayWeek = $actualDay - $actualDayOfWeek +1;
        $lastDayWeek = $actualDay+(6-$actualDayOfWeek) +1;
        if ($lastDayWeek > $monthNumberDay) {
            $lastDayWeek = $lastDayWeek - $monthNumberDay;
            if ($actualMonth < 12) {
                $actualWeekMonth += 1;
            } else {
                $actualWeekMonth = 1;
            }
        }

        if ($frequency == "month") {
            $minPeriod = date("Y-m-d H:i:s", mktime(0, 0, 0, date("m"), 1, date("Y")));
		    $maxPeriod = date("Y-m-t H:i:s", mktime(23, 59, 59, date("m"), 1, date("Y")));
        } else if ($frequency == "year") {
            $minPeriod = date("Y-m-d H:i:s", mktime(0, 0, 0, 1, 1, date("Y")));
		    $maxPeriod = date("Y-m-t H:i:s", mktime(23, 59, 59, 12, 1, date("Y")));
        } else if ($frequency == "week") {
            $minPeriod = date("Y-m-d H:i:s", mktime(0, 0, 0, $actualMonth, $firstDayWeek, date("Y")));
            $maxPeriod = date("Y-m-t H:i:s", mktime(23, 59, 59, $actualWeekMonth, $lastDayWeek, date("Y")));
        } else if ($frequency == "semestriel") {
            if ($actualMonth <= 6) {
                $minPeriod = date("Y-m-d H:i:s", mktime(0, 0, 0, 1, 1, date("Y")));
                $maxPeriod = date("Y-m-t H:i:s", mktime(23, 59, 59, 6, 1, date("Y")));
            } elseif ($actualMonth >= 7) {
                $minPeriod = date("Y-m-d H:i:s", mktime(0, 0, 0, 7, 1, date("Y")));
                $maxPeriod = date("Y-m-t H:i:s", mktime(23, 59, 59, 12, 1, date("Y")));
            }
        } else if ($frequency == "trimestriel") {
            if ($actualMonth <= 3) {
                $minPeriod = date("Y-m-d H:i:s", mktime(0, 0, 0, 1, 1, date("Y")));
                $maxPeriod = date("Y-m-t H:i:s", mktime(23, 59, 59, 3, 1, date("Y")));
            } elseif ($actualMonth >= 4 and $actualMonth <= 6) {
                $minPeriod = date("Y-m-d H:i:s", mktime(0, 0, 0, 4, 1, date("Y")));
                $maxPeriod = date("Y-m-t H:i:s", mktime(23, 59, 59, 6, 1, date("Y")));
            } elseif ($actualMonth >= 7 and $actualMonth <= 9) {
                $minPeriod = date("Y-m-d H:i:s", mktime(0, 0, 0, 7, 1, date("Y")));
                $maxPeriod = date("Y-m-t H:i:s", mktime(23, 59, 59, 9, 1, date("Y")));
            } elseif ($actualMonth >= 10 and $actualMonth <= 12) {
                $minPeriod = date("Y-m-d H:i:s", mktime(0, 0, 0, 10, 1, date("Y")));
                $maxPeriod = date("Y-m-t H:i:s", mktime(23, 59, 59, 12, 1, date("Y")));
            }
        }
        
        $entityManager = $this->getDoctrine()->getManager();
        
        $userSlpRepo = $this->getDoctrine()->getRepository(UserSlp::class);
        
        $UserConecte = $userSlpRepo->findOneByGaeaUserId($this->getUser()->getId());
        $UserSpendings = $entityManager->getRepository(Spending::class)->findFrequencySlpUserSpendings($UserConecte, $minPeriod, $maxPeriod);

        $totalCost = 0;
        foreach ($UserSpendings as $UserSpending) {
            $cost = $UserSpending->getCost()->getPrice();
            $quantity = $UserSpending->getQuantity()->getNumber();
            $amount = $cost * $quantity;
            $totalCost = $totalCost+$amount;
        }
        return $totalCost;
        
    }

    public function caluclateUserThemesSpendingsPencentageByFrequency($totalUserSpendings, $frequency) {
        
        if ($frequency == "month") {
            $minPeriod = date("Y-m-d H:i:s", mktime(0, 0, 0, date("m"), 1, date("Y")));
		    $maxPeriod = date("Y-m-t H:i:s", mktime(23, 59, 59, date("m"), 1, date("Y")));
        } else if ($frequency == "year") {
            $minPeriod = date("Y-m-d H:i:s", mktime(0, 0, 0, 1, 1, date("Y")));
		    $maxPeriod = date("Y-m-t H:i:s", mktime(23, 59, 59, 12, 1, date("Y")));
        }
        
        $entityManager = $this->getDoctrine()->getManager();

        $userSlpRepo = $this->getDoctrine()->getRepository(UserSlp::class);

        $UserConecte = $userSlpRepo->findOneByGaeaUserId($this->getUser()->getId());
        
        $themeRepo = $this->getDoctrine()->getRepository(BudgetTheme::class);

        $themes = $themeRepo->findAll();

        $themeSpendingAmount = 0;

        $total = [];

        $percentage = [];

        foreach ($themes as $theme) {
        $UserThemeSpendings = $entityManager->getRepository(Spending::class)->findFrequencySlpUserThemeSpendings($UserConecte, $theme->getName(), $minPeriod, $maxPeriod);

            $total[$theme->getName()] = 0;

            $percentage[$theme->getName()] = 0;

            $n = count($UserThemeSpendings)-1;
            for ($i = 0; $i <= $n; $i++) {

                $quantity = $UserThemeSpendings[$i]->getQuantity()->getNumber();

                $price = $UserThemeSpendings[$i]->getCost()->getPrice();

                $total[$theme->getName()] += $quantity * $price;
                
                
            }
            
            $percentage[$theme->getName()] = $totalUserSpendings ? ($total[$theme->getName()] / $totalUserSpendings) * 100 : 0;
        }
        
        //exit();
        
        return $percentage;
    }

    public function caluclateUserThemesSpendingsByFrequency($totalUserSpendings, $frequency) {

        if ($frequency == "month") {
            $minPeriod = date("Y-m-d H:i:s", mktime(0, 0, 0, date("m"), 1, date("Y")));
            $maxPeriod = date("Y-m-t H:i:s", mktime(23, 59, 59, date("m"), 1, date("Y")));
        } else if ($frequency == "year") {
            $minPeriod = date("Y-m-d H:i:s", mktime(0, 0, 0, 1, 1, date("Y")));
            $maxPeriod = date("Y-m-t H:i:s", mktime(23, 59, 59, 12, 1, date("Y")));
        } else if ($frequency == "week") {
            $minPeriod = date("Y-m-d H:i:s", mktime(0, 0, 0, 1, 1, date("Y")));
            $maxPeriod = date("Y-m-t H:i:s", mktime(23, 59, 59, date("m"), 1, date("Y")));
        } else if ($frequency == "semestriel") {
            $minPeriod = date("Y-m-d H:i:s", mktime(0, 0, 0, 1, 1, date("Y")));
            $maxPeriod = date("Y-m-t H:i:s", mktime(23, 59, 59, date("m"), 1, date("Y")));
        } else if ($frequency == "trimestriel") {
            $minPeriod = date("Y-m-d H:i:s", mktime(0, 0, 0, 1, 1, date("Y")));
            $maxPeriod = date("Y-m-t H:i:s", mktime(23, 59, 59, date("m"), 1, date("Y")));
        }

        $entityManager = $this->getDoctrine()->getManager();

        $userSlpRepo = $this->getDoctrine()->getRepository(UserSlp::class);

        $UserConecte = $userSlpRepo->findOneByGaeaUserId($this->getUser()->getId());

        $themeRepo = $this->getDoctrine()->getRepository(BudgetTheme::class);

        $themes = $themeRepo->findAll();

        $themeSpendingAmount = 0;

        $total = [];

        foreach ($themes as $theme) {
            $UserThemeSpendings = $entityManager->getRepository(Spending::class)->findFrequencySlpUserThemeSpendings($UserConecte, $theme->getName(), $minPeriod, $maxPeriod);

            $total[$theme->getName()] = 0;


            $n = count($UserThemeSpendings)-1;
            for ($i = 0; $i <= $n; $i++) {

                $quantity = $UserThemeSpendings[$i]->getQuantity()->getNumber();

                $price = $UserThemeSpendings[$i]->getCost()->getPrice();

                $total[$theme->getName()] += $quantity * $price;

            }
        }
        //exit();

        return $total;
    }

    /*public function caluclateUserThemesSpendingsPencentage($totalUserSpendings) {
        $entityManager = $this->getDoctrine()->getManager();

        $userSlpRepo = $this->getDoctrine()->getRepository(UserSlp::class);

        $UserConecte = $userSlpRepo->findOneByGaeaUserId($this->getUser()->getId());
        
        $themeRepo = $this->getDoctrine()->getRepository(BudgetTheme::class);

        $themes = $themeRepo->findAll();

        $themeSpendingAmount = 0;

        $total = [];

        $percentage = [];

        foreach ($themes as $theme) {
            $UserThemeSpendings = $entityManager->getRepository(Spending::class)->findSlpUserThemeSpendings($UserConecte, $theme->getName());

            $total[$theme->getName()] = 0;

            $percentage[$theme->getName()] = 0;

            $n = count($UserThemeSpendings)-1;
            for ($i = 0; $i <= $n; $i++) {

                $quantity = $UserThemeSpendings[$i]->getQuantity()->getNumber();

                $price = $UserThemeSpendings[$i]->getCost()->getPrice();

                $total[$theme->getName()] += $quantity * $price;
                
                
            }
            
            $percentage[$theme->getName()] = ($total[$theme->getName()] / $totalUserSpendings) * 100;
        }


        
        exit();
        
        return $UserThemeSpendings;
    }*/

    public function calculateTotalPerMonthLastTwelve() {

        $entityManager = $this->getDoctrine()->getManager();

        $userSlpRepo = $this->getDoctrine()->getRepository(UserSlp::class);

        $UserConecte = $userSlpRepo->findOneByGaeaUserId($this->getUser()->getId());
        // Tableau pour trier par mois
        $monthTime = [
            0 => date("Y-m-d H:i:s", mktime(23,59,59,date('m'),date('t'), date('y'))),
            1 => date("Y-m-d H:i:s", mktime(23,59,59,date('m')-1,date('t'), date('y'))),
            2 => date("Y-m-d H:i:s", mktime(23,59,59,date('m')-2,date('t'), date('y'))),
            3 => date("Y-m-d H:i:s", mktime(23,59,59,date('m')-3,date('t'), date('y'))),
            4 => date("Y-m-d H:i:s", mktime(23,59,59,date('m')-4,date('t'), date('y'))),
            5 => date("Y-m-d H:i:s", mktime(23,59,59,date('m')-5,date('t'), date('y'))),
            6 => date("Y-m-d H:i:s", mktime(23,59,59,date('m')-6,date('t'), date('y'))),
            7 => date("Y-m-d H:i:s", mktime(23,59,59,date('m')-7,date('t'), date('y'))),
            8 => date("Y-m-d H:i:s", mktime(23,59,59,date('m')-8,date('t'), date('y'))),
            9 => date("Y-m-d H:i:s", mktime(23,59,59,date('m')-9,date('t'), date('y'))),
            10 => date("Y-m-d H:i:s", mktime(23,59,59,date('m')-10,date('t'), date('y'))),
            11 => date("Y-m-d H:i:s", mktime(23,59,59,date('m')-11,date('t'), date('y'))),
            12 => date("Y-m-d H:i:s", mktime(23,59,59,date('m')-12,date('t'), date('y'))),
        ];

        $totalPerMonth = [];
        for ($n = 0; $n <= 11; $n++) {

            $totalCost = 0;
            $maxPeriod = $monthTime[$n];
            $minPeriod = $monthTime[$n + 1];

            $UserSpendings = $entityManager->getRepository(Spending::class)->findFrequencySlpUserSpendings($UserConecte, $minPeriod, $maxPeriod);

            if ($UserSpendings != null) {
                foreach ($UserSpendings as $UserSpending) {
                    $cost = $UserSpending->getCost()->getPrice();
                    $quantity = $UserSpending->getQuantity()->getNumber();
                    $amount = $cost * $quantity;
                    $totalCost = $totalCost + $amount;
                }
                $totalPerMonth[$n] = $totalCost;
            } else {
                $totalPerMonth[$n] = $totalCost;
            }
        }

        return $totalPerMonth;
    }

    public function calculateTotalPerYear (){
        $entityManager = $this->getDoctrine()->getManager();

        $userSlpRepo = $this->getDoctrine()->getRepository(UserSlp::class);

        $UserConecte = $userSlpRepo->findOneByGaeaUserId($this->getUser()->getId());


        $totalPerMonth = [];
        $totalPerYear = [];
        for ($n = 0; $n <= 11; $n++) {

            //Tableau du début/fin d'année pour récupérer les donnée par année
            $year = [
                0 => date("Y-m-d H:i:s", mktime(0,0,0,1,1, date('y')-$n)),
                1 => date("Y-m-d H:i:s", mktime(23,59,59,12,31, date('y')-$n)),
            ];

            $totalCost = 0;
            $maxPeriod = $year[1];
            $minPeriod = $year[0];
            //Récupération des donnée
            $UserSpendings = $entityManager->getRepository(Spending::class)->findFrequencySlpUserSpendings($UserConecte, $minPeriod, $maxPeriod);

            if ($UserSpendings != null) {
                foreach ($UserSpendings as $UserSpending) {
                    $cost = $UserSpending->getCost()->getPrice();
                    $quantity = $UserSpending->getQuantity()->getNumber();
                    $amount = $cost * $quantity;
                    $totalCost = $totalCost+$amount;
                }
                $totalPerYear[$n] = $totalCost;
            } else {
                $totalPerYear[$n] = $totalCost;
                break;
            }
        }
        return $totalPerYear;
    }
}
