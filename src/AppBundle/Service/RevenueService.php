<?php

namespace AppBundle\Service;

use AppBundle\Entity\MainRevenue;
use AppBundle\Entity\OtherRevenue;
use AppBundle\Entity\UserSlp;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class RevenueService extends Controller
{
    public function sortedRevenues($budget)
    {
 
        // instance des repositories :
        $mainRevenueRepo = $this->getDoctrine()->getRepository(MainRevenue::class);
        $otherRevenueRepo = $this->getDoctrine()->getRepository(OtherRevenue::class);

        // POUR AFFICHER LES MOIS ET ANNÉES DANS LA VUE :
        
        $today = new \DateTime('now');
        // on prend la date la plus ancienne dans la table Mainrevenue :
        if($budget){
            $firstDateInMainRev = $mainRevenueRepo->findFirstByBudget($budget->getId());
            if ($firstDateInMainRev != null) {
                $firstDateInMainRev = $firstDateInMainRev->getDate();
                // on compare avec $today pour obtenir le nombre de mois à prendre en compte pour calculer le nombre d'années à afficher
                // car chaque mois n'existe pas forcément dans les tables (si revenu constant, pas de nouvelle entrée par l'utilisateur)
                $totalMonths = date_diff($firstDateInMainRev, $today);
                $totalMonths = $totalMonths->format('%m') + ($totalMonths->format('%y')*12) + 1;
                // on calcule le nombre d'années sur lequel boucler en tenant compte que la dernière n'est pas forcément terminée
                $maxYear = ceil(($totalMonths-date('n'))/12); // arrondi au nombre entier supérieur

        //        exit();

                $revenues = []; // Tableau dans lequel on stocke les données par mois
                $totalByYears = []; // Tableau dans lequel on stocke les cumuls par année
                $month = date('n');
                $twelveMonths = 12;

                // Tableau des noms des mois pour les avoir en français
                $litteralMonths = array(1 => 'Janvier', 2 =>'Février', 3 => 'Mars', 4 => 'Avril', 5 => 'Mai', 6 => 'Juin',
                    7 => 'Juillet', 8 => 'Août',  9 => 'Septembre', 10 => 'Octobre', 11 => 'Novembre', 12 => 'Décembre');
                // ON BOUCLE D'ABORD SUR LES ANNÉES en remontant le temps à partir de l'année actuelle

                for ($year = date('Y'); $year >= date('Y')-$maxYear; $year--) {

                    $sumMainRevForThisYear = 0; // Variable qui permettra de faire la somme des MainRevenues pour un an
                    $sumOtherRevForThisYear = 0; // Variable qui permettra de faire la somme des OtherRevenues pour un an

                    // limiter le nombre de boucles pour les mois s'il y en a plus de 12 dans l'historique

                    if ($year == date('Y')) { // année en cours
                        $maxMonths = date('n');
                    } elseif ($totalMonths > 12) {  //année complète
                        $maxMonths = 12;
                    } else { // première année après 1 an d'activité
                        $maxMonths = $totalMonths;
                    }

                    if ($year != date('Y')) {
                        $month = 12;
                    }

                    // BOUCLE DES MOIS : remonter le temps en partant du mois actuel
                    for ($maxMonths; $maxMonths >= 1; $maxMonths--) {

                        if($totalMonths>0){
                            // on construit le premier jour du mois et le dernier jour du mois
                            $minDate = date("Y-m-d H:i:s", mktime(0, 0, 0, $month, 1, $year));
                            $maxDate = date("Y-m-t H:i:s", mktime(23, 59, 59, $month, 1, $year));

                            $mainRevenue= [];
                            // On recherche le mainRevenue dans un mois
                            $mainRevenue = $mainRevenueRepo->findByBudgetAndDates($budget->getId(), $minDate, $maxDate);
                            if ($mainRevenue == null) { // Si on n'a pas de revenu enregistré pour ce mois
                                // On récupère le précédent
                                $mainRevenue[0] = $mainRevenueRepo->findPreviousForDate($budget->getId(), $minDate);
                            }

                            // On recherche le otherRevenue dans un mois
                            $otherRevenue = $otherRevenueRepo->findByBudgetAndDates($budget->getId(), $minDate, $maxDate);
                            if ($otherRevenue == null) { // Si on n'a pas de revenu enregistré pour ce mois
                                // On récupère le précédent
                                $otherRevenue[0] = $otherRevenueRepo->findPreviousForDate($budget->getId(), $minDate);
                            }

                            // On ajoute pour le mois de cette boucle le montant mensuel à la somme des montants précédents
                            // uniquement si le revenu n'est pas null
                            if($mainRevenue[0] != null){
                                $sumMainRevForThisYear += $mainRevenue[0]->getAmount();
                                $sumOtherRevForThisYear += $otherRevenue[0]->getAmount();
                            }


                            // On stocke les données dans un tableau
                            if ($twelveMonths > 0) {

                                $revenues[$year][$litteralMonths[$month]] = array(
                                    'mainRevenue' => $mainRevenue,
                                    'otherRevenue' => $otherRevenue
                                );
                            }
                            // 0n enlève un mois aux dates pour la boucle suivante
                            $month -= 1;
                            $twelveMonths -= 1;

                            $totalMonths -= 1;
                        }

                    }
                    // FIN DE LA BOUCLE DES MOIS

                    // on crée le tableau des cumuls annuels
                    $totalByYears[$year] = array(
                            'mainRevenues' => $sumMainRevForThisYear,
                            'otherRevenues' => $sumOtherRevForThisYear
                        );

                    // on imbrique les deux tableaux de résultats (mensuels puis annuels) pour le return
                $sortedRevenues = [
                        'months' => $revenues,
                        'years' =>$totalByYears
                ];
                }
                return $sortedRevenues; // 2 tableaux imbriqués
            } else {
                $sortedRevenues = null;
                return $sortedRevenues;
            }
        }
    }
}
