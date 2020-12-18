<?php
namespace AppBundle\Service;

use AppBundle\Entity\MainRevenue;
use AppBundle\Entity\OtherRevenue;
use AppBundle\Entity\Budget;
use AppBundle\Entity\Spending;
use AppBundle\Repository\BudgetRepository;
use AppBundle\Repository\MainRevenueRepository;
use AppBundle\Entity\UserSlp;
use DoctrineExtensions\Query\Mysql\Date;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class BudgetExcedent extends Controller{


    public function excedent ($totalSpending, $revenus) {

        if ($revenus != null) {
            $monthTotalSpending = $totalSpending['months'];
            $yearTotalSpending = $totalSpending['years'];
            $monthRevenus = $revenus['months'];
            $yearRevenus = $revenus['years'];
            $monthTotalRevenus = [];
            $yearTotalRevenus = [];

            $n = 0;

            // Je calcul le total de revenu par mois
            foreach ($monthRevenus as $key => $value) {
                foreach ($value as $t) {
                    $monthTotalRevenus[$n] = $t['mainRevenue'][0]->getAmount() + $t['otherRevenue'][0]->getAmount();
                    $n += 1;
                }
            }
            $n = 0;

            // Je calcul le total par année
            foreach ($yearRevenus as $key) {
                $yearTotalRevenus[$n] = $key['mainRevenues']+ $key['otherRevenues'];
                $n += 1;
            }
            // Excedent du mois actuel
            $excedent['actualMonth'] = $monthTotalRevenus[0] - $monthTotalSpending[0];

            // Je calcul l'éxcédent de revenus en rapport avec les dépenses par mois
            for ($i = 0; $i < count($monthTotalRevenus); $i++) {
                $excedent['months'][$i] = ceil((($monthTotalRevenus[$i]-$monthTotalSpending[$i]) * 100) / $monthTotalRevenus[$i]);
            }

            $nbYear = count($yearRevenus) - 1;

            for ($x = 0;  $x <= $nbYear; $x++) {
                $excedent['years'][$x] = ceil((($yearTotalRevenus[$x]-$yearTotalSpending[$x]) * 100) / $yearTotalRevenus[$x]);
            }
        } else {
            $excedent = null;
        }

        return $excedent;
    }


}
