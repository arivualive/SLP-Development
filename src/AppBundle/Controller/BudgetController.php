<?php

namespace AppBundle\Controller;

/*use DateTime;*/
use AppBundle\Entity\Feed;
use AppBundle\Entity\UserSlp;
use AppBundle\Service\UserSlpGaeaUser;
use QuestionBundle\Entity\Answer;
use QuestionBundle\Entity\Manger;
use QuestionBundle\Entity\Survey;
use QuestionBundle\Entity\Question;
use AppBundle\Entity\ProgressManger;
use AppBundle\Entity\ProgressPsycho;
use AppBundle\Entity\SurveyProgress;
use AppBundle\Entity\BudgetTheme;
use QuestionBundle\Entity\Answer_Free;
use QuestionBundle\Entity\Sub_question;
use QuestionBundle\Entity\Answer_choice;
use Symfony\Component\Form\FormInterface;
use QuestionBundle\Repository\AnswerRepository;
use QuestionBundle\Repository\QuestionRepository;
use AppBundle\Repository\ProgressMangerRepository;
use Doctrine\Common\Persistence\ObjectManager;
use QuestionBundle\Repository\Answer_choiceRepository;
use AppBundle\Service\BudgetExcedent;

use AppBundle\AppBundle;
use GaeaUserBundle\Entity\User;
use AppBundle\Entity\Additive;
use AppBundle\Entity\Budget;
use AppBundle\Entity\Brand;
use AppBundle\Entity\Bulk;
use AppBundle\Entity\Category;
use AppBundle\Entity\Composition;
use AppBundle\Entity\Currency;
use AppBundle\Entity\Detail;
use AppBundle\Entity\Frequency;
use AppBundle\Entity\HiddenSugar;
use AppBundle\Entity\MainRevenue;
use AppBundle\Entity\OtherRevenue;
use AppBundle\Entity\Origin;
use AppBundle\Entity\Packing;
use AppBundle\Entity\PalmOil;
use AppBundle\Entity\Product;
use AppBundle\Entity\QualityLabel;
use AppBundle\Entity\Spending;
use AppBundle\Entity\Subcategory;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

use AppBundle\Repository\BudgetRepository;
use AppBundle\Repository\UserSlpRepository;
use AppBundle\Repository\MainRevenueRepository;
use AppBundle\Repository\OtherRevenueRepository;
use AppBundle\Repository\CurrencyRepository;
use AppBundle\Repository\FrequencyRepository;

use AppBundle\Form\BudgetType;
use AppBundle\Form\MainRevenueType;
use AppBundle\Form\OtherRevenueType;

use AppBundle\Service\SpendingService;
use AppBundle\Service\RevenueService;
use Doctrine\ORM\Query\Expr\Andx;

class BudgetController extends Controller
{

    /**
     * @Route("revenus", name="revenus_index")
    */
    public function index(){
        if ($this->getUser() != null) {

             // instance des repositories :
             $userSlpRepo = $this->getDoctrine()->getRepository(UserSlp::class);
             $mainRevenueRepo = $this->getDoctrine()->getRepository(MainRevenue::class);
 
             // variables dont on a besoin :
             $userSlp = $userSlpRepo->findOneByGaeaUserId($this->getUser()->getId());
             $budget = $userSlp->getBudget();
             $firstMainRev = $mainRevenueRepo->findFirstByBudget($budget->getId());

             if(!$firstMainRev){
                return $this->render('site/revenus/index.html.twig', [
                    'userSlp' => $userSlp,
                    'budget' => $budget
                ]);
             }

            return $this->render('site/revenus/index.html.twig', [
                'userSlp' => $userSlp,
                'budget' => $budget,
                'firstMainRev' => $firstMainRev
            ]);
        } else {
            return $this->redirectToRoute('fos_user_security_login');
        }
    }

    /**
     * @Route("revenus/show", name="revenus_show")
     */
    public function showAction(Request $request, RevenueService $revenueService) {
            
        if ($this->getUser() !== null) {

            // instance des repositories :

            $userSlpRepo = $this->getDoctrine()->getRepository(UserSlp::class);
            $currencyRepo = $this->getDoctrine()->getRepository(Currency::class);
            $mainRevenueRepo = $this->getDoctrine()->getRepository(MainRevenue::class);

            // variables dont on a besoin :

            $userSlp = $userSlpRepo->findOneByGaeaUserId($this->getUser()->getId());
            $budget = $userSlp->getBudget();
            $firstMainRev = $mainRevenueRepo->findFirstByBudget($budget->getId());

            $mainRevenues = [];
            $otherRevenues = [];
            $revenues = [];
            if ($budget) {
                $mainRevenues = $budget->getMainRevenues()->getValues();
                $otherRevenues = $budget->getOtherRevenues()->getValues();
                //$currentMainRevenue = $mainRevenueRepo->findOneByBudget($budget->getId());
                //$currentOtherRevenue = $otherRevenueRepo->findOneByBudget($budget->getId());


                // récupérer les revenues triés par mois et années en utilisant un service :
                $revenues = $revenueService->sortedRevenues($budget);

            }

            $currency = $currencyRepo->findOneById(1);

            return $this->render('site/revenus/show.html.twig', [
                'userSlp' => $userSlp,
                'budget' => $budget,
                'mainRevenues' => $mainRevenues,
                'otherRevenues' => $otherRevenues,
                'currency' => $currency,
                'revenues' => $revenues,
                'firstMainRev' => $firstMainRev
            ]);
        } else {
        return $this->redirectToRoute('fos_user_security_login');
        }
    }

    /**
     * @Route("revenus/new", name="revenus_new", methods={"GET","POST"})
     */
    public function newAction(Request $request){
        if ($this->getUser() !== null) {
            $userSlpRepo = $this->getDoctrine()->getRepository(UserSlp::class);
            $userSlp = $userSlpRepo->findOneByGaeaUserId($this->getUser()->getId());
            $budgetRepo = $this->getDoctrine()->getRepository(Budget::class);
            $budget = $budgetRepo->findOneByUserSlp($userSlp);
            $mainRevenueRepo = $this->getDoctrine()->getRepository(MainRevenue::class);
            $firstMainRev = $mainRevenueRepo->findFirstByBudget($budget->getId());

            if(!$firstMainRev){ // s'il n'existe encore aucun revenu dans la base
                $mainRevenueAmount = $request->get("mainRevenue");
                $otherRevenueAmount = $request->get("otherRevenue");

                // si les deux champs du formulaire sont bien remplis par des nombres :
                if (is_numeric($mainRevenueAmount) && is_numeric($otherRevenueAmount)){
    
                    $currencyRepo = $this->getDoctrine()->getRepository(Currency::class);
                    $currency = $currencyRepo->findOneById(1);
                    $frequencyRepo = $this->getDoctrine()->getRepository(Frequency::class);
                    $frequency = $frequencyRepo->findOneById(1);
                    $date = new \Datetime("now");
                    $mainRevenue = new MainRevenue();
                    $mainRevenue->setAmount($mainRevenueAmount);
                    $mainRevenue->setCurrency($currency);
                    $mainRevenue->setDate($date);
                    $mainRevenue->setFrequency($frequency);
                    $otherRevenue = new OtherRevenue();
                    $otherRevenue->setAmount($otherRevenueAmount);
                    $otherRevenue->setCurrency($currency);
                    $otherRevenue->setDate($date);
                    $otherRevenue->setFrequency($frequency);
                    // le budget existe déjà
                    //$budget = new Budget();
                    //$budget->setUserSlp($userSlp);
                    $budget->addMainRevenue($mainRevenue);
                    $budget->addOtherRevenue($otherRevenue);
                    $mainRevenue->setBudget($budget);
                    $otherRevenue->setBudget($budget);
                    
                    // enregistrer dans la base :
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($budget);
                    $entityManager->persist($mainRevenue);
                    $entityManager->persist($otherRevenue);
                    $entityManager->flush();
    
                    return $this->redirectToRoute('revenus_index');
                    // return $this->render('site/revenus/index.html.twig',[
                    //     'userSlp' => $userSlp,
                    //     'budget' => $budget,
                    //     'firstMainRev' => $firstMainRev
                    // ]);
                }
            }
            // return $this->redirectToRoute('revenus_index');
            return $this->render('site/revenus/index.html.twig', [
                'userSlp' => $userSlp,
                'budget' => $budget,
            ]);
        }
        return $this->redirectToRoute('fos_user_security_login');
    }

    /**
     * @Route("revenus/edit", name="revenus_edit")
     */
    public function editAction(Request $request) {
            
        if ($this->getUser() !== null) {

            $userSlpRepo = $this->getDoctrine()->getRepository(UserSlp::class);
            $userSlp = $userSlpRepo->findOneByGaeaUserId($this->getUser()->getId());
            $budgetRepo = $this->getDoctrine()->getRepository(Budget::class);
            $budget = $budgetRepo->findOneByUserSlp($userSlp);
            $mainRevenueRepo = $this->getDoctrine()->getRepository(MainRevenue::class);
            $firstMainRev = $mainRevenueRepo->findFirstByBudget($budget->getId());

            $mainRevenueAmount = $request->get("mainRevenue");
            $otherRevenueAmount = $request->get("otherRevenue");

                // si l'utilisateur a bien rempli les deux cases par des chiffres
                if (is_numeric($mainRevenueAmount) && is_numeric($otherRevenueAmount)){
                    
                    $mainRevenueRepo = $this->getDoctrine()->getRepository(MainRevenue::class);
                    $otherRevenueRepo = $this->getDoctrine()->getRepository(OtherRevenue::class);
                    // on crée le premier et le dernier jour du mois
                    $minDate = date("Y-m-d H:i:s", mktime(0, 0, 0, date('m'), 1, date('y')));
                    $maxDate = date("Y-m-t H:i:s", mktime(23, 59, 59, date('m'), 1, date('y')));
                    // on vérifie s'il y a déjà des lignes dans la BDD le même mois
                    $mainRevenue = $mainRevenueRepo->findByBudgetAndDates($budget->getId(), $minDate, $maxDate);
                    $otherRevenue = $otherRevenueRepo->findByBudgetAndDates($budget->getId(), $minDate, $maxDate);
                    // S'il y a déjà des valeurs en base pour le même mois, on les supprime
                    if ($mainRevenue != null && $otherRevenue != null) {
                        $entityManager = $this->getDoctrine()->getManager();
                        $budget->removeMainrevenue($mainRevenue[0]);
                        $budget->removeOtherRevenue($otherRevenue[0]);
                        $entityManager->flush();
                    }

                    // On crée ensuite les nouvelles entrées dans la BDD :

                    //variables
                    $currencyRepo = $this->getDoctrine()->getRepository(Currency::class);
                    $currency = $currencyRepo->findOneById(1);
                    $frequencyRepo = $this->getDoctrine()->getRepository(Frequency::class);
                    $frequency = $frequencyRepo->findOneById(1);
                    $date = new \Datetime("now");
                    // setters
                    $mainRevenue = new MainRevenue();
                    $mainRevenue->setAmount($mainRevenueAmount);
                    $mainRevenue->setCurrency($currency);
                    $mainRevenue->setDate($date);
                    $mainRevenue->setFrequency($frequency);
                    $otherRevenue = new OtherRevenue();
                    $otherRevenue->setAmount($otherRevenueAmount);
                    $otherRevenue->setCurrency($currency);
                    $otherRevenue->setDate($date);
                    $otherRevenue->setFrequency($frequency);

                    // mettre à jour les collections dans l'entité budget qui existe déjà
                    $budget->setUserSlp($userSlp);
                    $budget->addMainRevenue($mainRevenue);
                    $budget->addOtherRevenue($otherRevenue);
                    $mainRevenue->setBudget($budget);
                    $otherRevenue->setBudget($budget);
                    
                    // enregistrer le tout dans la base :
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($budget);
                    $entityManager->persist($mainRevenue);
                    $entityManager->persist($otherRevenue);
                    $entityManager->flush();
    
                    return $this->redirectToRoute('revenus_index');
                    // return $this->render('site/revenus/index.html.twig',[
                    //     'userSlp' => $userSlp,
                    //     'budget' => $budget,
                    //     'firstMainRev' => $firstMainRev
                    // ]);
                }
            //return $this->redirectToRoute('revenus_index');
            return $this->render('site/revenus/index.html.twig',[
                'userSlp' => $userSlp,
                'budget' => $budget,
                'firstMainRev' => $firstMainRev
            ]);
        } else {
            return $this->redirectToRoute('fos_user_security_login');
            }
        }

    /**
     * @Route("/actualitedashboard", name="actualite_dashboard")
     *
     */
    public function actuDashboard()
    {
        return $this->render('site/actualite_dashboard.html.twig');
    }

    /**
     * @Route("/template-dashboard", name="test_Dashboard")
     */
    public function testDashboard()
    {
        return $this->render('site/test_dashboard.html.twig');
        
    }

    /**
     * @Route("/presentationdashboard", name="presentationdashboard")
     */
    public function presentationdashboard()
    {
        return $this->render('site/presentationdashboard.html.twig');
    }

    /**
     * @Route("/eating-budget", name="eating-budget")
     */
    public function eatingBudget(){

        if ($this->getUser() !== null) {

            // instance des repositories :

            $userSlpRepo = $this->getDoctrine()->getRepository(UserSlp::class);
            $themeRepo = $this->getDoctrine()->getRepository(BudgetTheme::class);
            $categoryRepo = $this->getDoctrine()->getRepository(Category::class);
            $subcategoryRepo = $this->getDoctrine()->getRepository(Subcategory::class);
            $productRepo = $this->getDoctrine()->getRepository(Product::class);
            $detailRepo = $this->getDoctrine()->getRepository(Detail::class); // lieu d'achat
            $bulkRepo = $this->getDoctrine()->getRepository(Bulk::class);
            $packingRepo = $this->getDoctrine()->getRepository(Packing::class);
            $qualityLabelRepo = $this->getDoctrine()->getRepository(QualityLabel::class);
            $originRepo = $this->getDoctrine()->getRepository(Origin::class);
            $compositionRepo = $this->getDoctrine()->getRepository(Composition::class);
            $additiveRepo = $this->getDoctrine()->getRepository(Additive::class);
            $palmOilRepo = $this->getDoctrine()->getRepository(PalmOil::class);
            $hiddenSugarRepo = $this->getDoctrine()->getRepository(HiddenSugar::class);
            
            // variables dont on a besoin pour l'affichage :

            $userSlp = $userSlpRepo->findOneByGaeaUserId($this->getUser()->getId());
            $themeId = $themeRepo->findOneByName('eating')->getId();
            $categories = $categoryRepo->findByTheme($themeId);
            
            $details = $detailRepo->findAll();
            $bulkOptions= $bulkRepo->findAll();
            $packingOptions = $packingRepo->findAll();
            $qualityLabels = $qualityLabelRepo->findAll();
            $originOptions = $originRepo->findAll();
            $compositionOptions = $compositionRepo->findAll();
            $additives = $additiveRepo->findAll();
            $palmOils = $palmOilRepo->findAll();
            $hiddenSugars = $hiddenSugarRepo->findAll();

            // produits triés par user et catégorie, puis par user et sous-catégorie :

            $productsByCatAndUser = [];
            $productsBySubcatAndUser = [];

            foreach ($categories as $category){

                $catName = $category->getName();

                // cas particulier TABAC et ALCOOL : articles directement dans la catégorie
                if ($catName == 'Tabac' OR $catName == 'Alcool'){
                    $productsByCat = $productRepo->findByUserSlpAndAlcoolOrTabacCat($userSlp, $category);
                } else {
                    $productsByCat = $productRepo->findByUserSlpAndCat($userSlp, $category);
                }
                
                // insérer dans le tableau de résultats avec la catégorie en clef
                $productsByCatAndUser[$catName] = $productsByCat;
                
                $subcategories = $subcategoryRepo->findByCategory($category->getId());
                
                foreach ($subcategories as $subcategory){

                    $productsBySubcat = $productRepo->findByUserSlpAndSubcat($userSlp, $subcategory);
                    $subcatName = $subcategory->getName();

                    // insérer dans le tableau de résultats avec la sous-catégorie en clef
                    $productsBySubcatAndUser[$subcatName] = $productsBySubcat;
                }
            }


                return $this->render('site/budget/eating-budget.html.twig',[
                    'categories' => $categories,
                    'productsByCatAndUser' => $productsByCatAndUser,
                    'productsBySubcatAndUser' => $productsBySubcatAndUser,
                    'details' => $details,
                    'bulkOptions' => $bulkOptions,
                    'packingOptions' => $packingOptions,
                    'qualityLabels' => $qualityLabels,
                    'originOptions' => $originOptions,
                    'compositionOptions' => $compositionOptions,
                    'additives' => $additives,
                    'palmOils' => $palmOils,
                    'hiddenSugars' => $hiddenSugars,
                    // 'product' => $product,
                ]);
        }else{
            return $this->redirectToRoute('fos_user_security_login');
        }
    }

    /**
     * @Route("/budget-dashboard-program-enter-spendings",
     *     name="budget-dashboard-program"
     * )
     */
    public function budgetDashboardEnterSpendings()
    {
        return
            $this->render('site/budget/budget_dashboard_program_enter_spendings.html.twig');
    }
 /*   /**
     * @Route("/budget_dashboard_program_enter_spendings",
     *      name="budget_dashboard_program_enter_spendings"
     * )
     */
 /*   public function budgetdashboardEnterSpendings()
    {
        return $this->render('site/budget/budget_dashboard_program_enter_spendings.html.twig');
    }
*/
    /**
     * @Route("/eating_budget_dashboard_program_enter_spendings",
     *     name="budget_dashboard_program_enter_spendings"
     * )
     */
    public function eatingBudgetdashboardEnterSpendings()
    {
        $entityManager = $this->getDoctrine()->getManager();

        $currencies = $entityManager->getRepository(Currency::class)->findAll();

        $user = $this->getUser();

        if ($user) {

            $userSlpRepo = $this->getDoctrine()->getRepository(UserSlp::class);

            //$UserConecte = $userSlpRepo->findOneByGaeaUserId($this->getUser()->getId());

            $userSlp = $userSlpRepo->findOneByGaeaUserId($user->getId());

            
            //$userSlp = $this->searchUserSlpFromGaeaUser($user);

            $spendings = $entityManager->getRepository(Spending::class)->findSlpUserSpendingsWithoutFrequency($userSlp);


            return $this->render('site/budget/eating_budget_dashboard_enter_spendings.html.twig', [
                "spendings" => $spendings,
                "currencies" => $currencies,
                /*"userslp" => $userSlp*/
            ]);
        }
        return $this->redirectToRoute('fos_user_security_login');
    }

/**
     * @Route("/budgetdepenses", name="budget_depenses")
     */
    public function budgetDepenses(Request $request)
    {
        
        $entityManager = $this->getDoctrine()->getManager();

        $currencies = $entityManager->getRepository(Currency::class)->findAll();

        $user = $this->getUser();

        if ($user) {

            $userSlpRepo = $this->getDoctrine()->getRepository(UserSlp::class);

            $UserConecte = $userSlpRepo->findOneByGaeaUserId($this->getUser()->getId());
            
            $userSlp = $this->searchUserSlpFromGaeaUser($user);

            $spendings = $entityManager->getRepository(Spending::class)->findSlpUserSpendings($UserConecte);


            return $this->render('site/general-budget-spendings-by-periods/programme_budget_depenses.html.twig', [
                "spendings" => $spendings,
                "currencies" => $currencies,
                /*"userslp" => $userSlp*/
            ]);
            
        }
        return $this->redirectToRoute('fos_user_security_login');
    }

    /**
     * @Route("/programme_budget_depenses", name="programmebudgetDepenses")
     */
    public function programmebudgetDepenses()
    {
        return $this->render('site/programme_budget_depenses.html.twig');

    }

    /**
     * @Route("/hidden_sugar", name="hidden_sugar")
     */
    public function hiddenSugar()
    {
        return $this->render('site/hidden-sugar.html.twig');
    }

    /**
     * @Route("/palm_oil", name="palm_oil")
     */
    public function palmOil()
    {
        return $this->render('site/palm_oil.html.twig');
    }

    /**
     * @Route("/additives", name="additives")
     */
    public function additives()
    {
        return $this->render('site/additives.html.twig');
    }

    /**
     * @Route("/programme_budget_dashboard", name="programmebudgetDashboard")
     */
    public function programmebudgetDashboard(SpendingService $SpendingService, BudgetExcedent $budgetExcedent, RevenueService $revenueService)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $themes = $entityManager->getRepository(BudgetTheme::class)->findAll();
        $currencyRepo = $this->getDoctrine()->getRepository(Currency::class);
        $mainRevenueRepo = $this->getDoctrine()->getRepository(MainRevenue::class);

        $user = $this->getUser();
        $userSlpRepo = $this->getDoctrine()->getRepository(UserSlp::class);
        $userSlp = $userSlpRepo->findOneByGaeaUserId($this->getUser()->getId());

        if ($user) {
//            $litteralMonths = array(1 => 'Janvier', 2 =>'Février', 3 => 'Mars', 4 => 'Avril', 5 => 'Mai', 6 => 'Juin',
//                7 => 'Juillet', 8 => 'Août', 9 => 'Septembre', 10 => 'Octobre', 11 => 'Novembre', 12 => 'Décembre');
            $litteralMonths = array(1 => 'J', 2 =>'F', 3 => 'M', 4 => 'A', 5 => 'M', 6 => 'J',
                7 => 'J', 8 => 'A', 9 => 'S', 10 => 'O', 11 => 'N', 12 => 'D');

            $date = date('m')-1+1;
            $yearNow = date('Y');

            // Récupération de la money du user
            $mainRevenue = $mainRevenueRepo->findPreviousForDate($userSlp->getBudget(),
                date("Y-m-t H:i:s", mktime(23, 59, 59, date('m'), date('t'), date('Y')))
            );
            if ($mainRevenue != null) {
                $currency = $currencyRepo->findOneById($mainRevenue->getCurrency()->getId());
            } else {
                $currency = ['symbol' => 'CHF'];
            }

            $totalSpendingPerMonthForLastTwelve = $SpendingService->calculateTotalPerMonthLastTwelve();
            $totalSpendingPerYear = $SpendingService->calculateTotalPerYear();
            $totalSpending = ['months' =>  $totalSpendingPerMonthForLastTwelve, 'years' => $totalSpendingPerYear];
            $revenus = $revenueService->sortedRevenues($userSlp->getBudget());

            $excedent = $budgetExcedent->excedent($totalSpending, $revenus);
            $excedentMonth = [];
            $excedentYear = [];
            $excedentActualMonth = 0;
            if($excedent != null){
                $excedentMonth = $excedent['months'];
                $excedentYear = $excedent['years'];
                $excedentActualMonth = $excedent['actualMonth'];
              }
            if ($excedentYear) {
                $nbYear = count($excedentYear);

                // Je définis les années
                $year = [];
                for ($n = 0; $n < $nbYear; $n++) {
                    $year[$n] = $yearNow - $n;
                }
            } else { $year = null;}

            $frequency = "month";


            $totalUserSpendingsCurrentMonth = $SpendingService->calculateTotalUserSpendingsByFrequency($frequency);


            $spendingsPercentagesByThemeCurrentMonth = $SpendingService->caluclateUserThemesSpendingsPencentageByFrequency($totalUserSpendingsCurrentMonth, $frequency);
            $spendingByThemeCurrentMonth = $SpendingService->caluclateUserThemesSpendingsByFrequency($totalUserSpendingsCurrentMonth, $frequency);

            $frequency = "year";


            $totalUserSpendingsCurrentYear = $SpendingService->calculateTotalUserSpendingsByFrequency($frequency);


            $spendingsPercentagesByThemeCurrentYear = $SpendingService->caluclateUserThemesSpendingsPencentageByFrequency($totalUserSpendingsCurrentYear, $frequency);
            $spendingByThemeCurrentYear = $SpendingService->caluclateUserThemesSpendingsByFrequency($totalUserSpendingsCurrentYear, $frequency);

            $frequency = "week";
            $totalUserSpendingsCurrentWeek = $SpendingService->calculateTotalUserSpendingsByFrequency($frequency);
            $spendingByThemeCurrentWeek = $SpendingService->caluclateUserThemesSpendingsByFrequency($totalUserSpendingsCurrentWeek, $frequency);

            $frequency = "semestriel";
            $totalUserSpendingsCurrentSemestriel = $SpendingService->calculateTotalUserSpendingsByFrequency($frequency);
            $spendingByThemeCurrentSemestriel = $SpendingService->caluclateUserThemesSpendingsByFrequency($totalUserSpendingsCurrentSemestriel, $frequency);

            $frequency = "trimestriel";
            $totalUserSpendingsCurrentTrimestriel = $SpendingService->calculateTotalUserSpendingsByFrequency($frequency);
            $spendingByThemeCurrentTrimestriel = $SpendingService->caluclateUserThemesSpendingsByFrequency($totalUserSpendingsCurrentTrimestriel, $frequency);


            return $this->render('site/programme_budget_dashboard.html.twig', [
                //"spendings" => $spendings,
                "spendingsPercentagesByThemeCurrentMonth" => $spendingsPercentagesByThemeCurrentMonth,
                "spendingsPercentagesByThemeCurrentYear" => $spendingsPercentagesByThemeCurrentYear,
                "spendingsByThemeCurrentMonth" => $spendingByThemeCurrentMonth,
                "spendingsByThemeCurrentYear" => $spendingByThemeCurrentYear,
                "spendingsByThemeCurrentWeek" => $spendingByThemeCurrentWeek,
                "spendingsByThemeCurrentSemestriel" => $spendingByThemeCurrentSemestriel,
                "spendingsByThemeCurrentTrimestriel" => $spendingByThemeCurrentTrimestriel,
                'excedentMonth' => $excedentMonth,
                'excedentYear' => $excedentYear,
                'litteralMonths' => $litteralMonths,
                'date' => $date,
                'year' => $year,
                'excedentActualMonth' => $excedentActualMonth,
                'currency' => $currency,
                "themes" => $themes,
            ]);
        }
        return $this->redirectToRoute('fos_user_security_login',[
        ]);
    }

    public function searchUserSlpFromGaeaUser($user) {
        $gaeaUserId = $user->getId();
        $userSlp = $this->getDoctrine()->getRepository('AppBundle:UserSlp')->findOneBy(array('gaeaUserId' => $gaeaUserId));

        return $userSlp;
    }

    /**
     * @Route("/programme_budget", name="programme_dashboard_budget")
     */
    public function programmeDashboardBudget()
    {
        // On recupère le user à renvoyer dans la vue
        $userSLP = $this->getUser();

        return $this->render('site/budget/budget_program.html.twig', [
            'userslp' => $userSLP
        ]);
//        return $this->render('site/budget/budget-prog-test.html.twig', [
//            'userslp' => $userSLP
//        ]);

    }
}