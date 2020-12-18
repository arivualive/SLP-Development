<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Budget;
use AppBundle\Entity\Currency;
use AppBundle\Entity\Feed;
use AppBundle\Entity\MainRevenue;
use AppBundle\Entity\UserSlp;
use AppBundle\Entity\Personalisation;
use AppBundle\Form\ContactType;
use AppBundle\Entity\Miniprofil;
use AppBundle\Service\BudgetExcedent;
use AppBundle\Service\FeedNotif;
use AppBundle\Entity\Miniprofile;
use AppBundle\Service\RevenueService;
use AppBundle\Service\SpendingService;
use QuestionBundle\Entity\Answer;
use QuestionBundle\Entity\Choice;
use QuestionBundle\Entity\Manger;
use QuestionBundle\Entity\Survey;
use QuestionBundle\Entity\Question;
use AppBundle\Entity\ProgressManger;
use AppBundle\Entity\SurveyProgress;
use QuestionBundle\Entity\Answer_free;
use QuestionBundle\Entity\Answer_scale;
use QuestionBundle\Entity\Sub_question;
use QuestionBundle\Entity\Answer_choice;
use QuestionBundle\Entity\Question_master;
use AppBundle\Repository\UserSlpRepository;
use AppBundle\Repository\PersonalisationpRepository;
use QuestionBundle\Entity\Reponse_thematique;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use QuestionBundle\Entity\Conditional_question;
use QuestionBundle\Repository\AnswerRepository;
use QuestionBundle\Repository\ChoiceRepository;
use QuestionBundle\Repository\MangerRepository;
use QuestionBundle\Repository\QuestionRepository;
use QuestionBundle\Entity\Conditional_question_master;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use QuestionBundle\Repository\Reponse_thematiqueRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Doctrine\ORM\EntityManagerInterface;

class MiniProfilController extends Controller
{
    /**
     * @Route("/dashboard", name="site_dashboard")
     */
    public function dashboardAction(FeedNotif $feedNotif,SpendingService $SpendingService, BudgetExcedent $budgetExcedent, RevenueService $revenueService)
    {
        if (!$auth_checker = $this->get('security.authorization_checker')->isGranted("ROLE_USER")) {
            return $this->redirectToRoute('fos_user_security_login');
        }
        /*/traitement de la question 38 (addition des valeurs de choix)
        $question = $questionRepo->findOneById(38);
        $test = [];
        $subq = $question->getSubQuestions();
        foreach ($subq as $subQuestion) {
          $answer = $answerRepo2->getSubQ($userSlp, $subQuestion);

          if($answer != null){
            $choices = $answer->getChoices();

            foreach ($choices as $choice) {
              $test[] = $choice->getValue();
            }
          }
        }
        $test2 = '';
        foreach ($test as  $value) {
          $test2 .= $value;
        }*/
        $feedRepo = $this->getDoctrine()->getRepository(Feed::class);
        $userSlpRepo = $this->getDoctrine()->getRepository(UserSlp::class);
        $ProgressPsychoRepo = $this->getDoctrine()->getRepository(SurveyProgress::class);
        $surveyRepo = $this->getDoctrine()->getRepository(Survey::class);
        $questionPsychoRepo = $this->getDoctrine()->getRepository(Question::class);
        $answerRepository = $this->getDoctrine()->getRepository(Answer::class);
        $personalisationRepo = $this->getDoctrine()->getRepository(Personalisation::class);

        $UserConecte = $userSlpRepo->findOneByGaeaUserId($this->getUser()->getId());
        $Sondage = $surveyRepo->findOneById("3");
        $ProgressPsycho = $ProgressPsychoRepo->findOneBy(["user" => $UserConecte, "survey" => $Sondage]);
        

        if (isset($ProgressPsycho)) {
            $done = $ProgressPsycho->getDone();
            if ($ProgressPsycho->getLastAnwseredQuestion() instanceof Sub_question) {
                 $DerniereQuestion = $ProgressPsycho->getLastAnwseredQuestion()->getMasterQuestion()->getNumber();
            } else {
                $DerniereQuestion = $ProgressPsycho->getLastAnwseredQuestion()->getNumber();
                 }

         }else{
              $done = 0;
              $DerniereQuestion = 0;
         }

          if ($UserConecte == null)
        {
            $userSlp = null;
            $questions = null;
            $formPsycho = null;
        }else{
             $userSlp = $UserConecte;
            if ($done)
            {
                $questions = [];
                $formPsycho = 0;               
            } else {
                $questions = $questionPsychoRepo->findQuestionWithoutSubBySurvey($Sondage, $DerniereQuestion);
                $formPsycho = 1;
            }
        }
        
        // récupère le nombre de total de questions du questionnaire manger
        $mangerRepo = $this->getDoctrine()->getRepository(Manger::class);
        $totalQuestionsManger = $mangerRepo->getTotalQuestions();
        
        // récupère l'id de l'utilisateur et calcul le nombre de réponses répondues
//        $reponseRepo = $this->getDoctrine()->getRepository(Reponse_thematique::class);
//        $nombreReponses = $reponseRepo->getUser($userSlp);
//        $nombreReponses = $nombreReponses / $totalQuestionsManger *100;
        $nombreReponses = 10;

        $questionDateSupAnswerDate = $answerRepository->findByDateQuestionAndAnswer($userSlp);
        
        // affiche le feed de bienvenue via service feedNotif
        // $feedTab = [];
        // $feedTab = $feedRepo->findby(['categorie'=>'Welcome','user'=>$userSlp]);

        //récupère les feeds concernant question socio de l'utilisateur
        $feedTabQuestion = $feedRepo->findby(['categorie'=>'questionnaire-socio','user'=>$userSlp]);

        if($userSlp) {
          // if(!$feedTab) {
          //   $feedNotif->createWelcomeUser($userSlp);
          // }
          if($questionDateSupAnswerDate != null) {
            if($feedTabQuestion == null) {
              $feedNotif->createMajSocioQuestion($userSlp);
            } 
          } else {
            $feedNotif->removeMajSocioQuestion($feedTabQuestion);           
            }
        }
        $feed = $feedRepo->findBy(
          array('user' => $userSlp) // Critere Id User
        );

       
        // Affichage du nombre de nouveaux feeds depuis la dernière connexion de l'user
        $counter = $feedRepo->findByDatePassed($userSlp->getLastLogin());

        // Affichage du maximum de 6 feeds pour la version desktop
        $desktopFeed = $feedRepo->findBy(array('user' => $userSlp), array('id' => 'DESC'), 6, null );   // Critere Id User$

        // Affichage des feeds selon leurs priorités
        $mobileFeedOne = $feedRepo->findBy(array('user' => $userSlp, 'priorite' => true), array('id' => 'DESC'), null, null); // Critere Id User$
        $mobileFeedTwo = $feedRepo->findBy(array('user' => $userSlp, 'priorite' => false), array('id' => 'DESC'), null, null); // Critere Id User$

        // Récupération directe de l'image recadrée
        $ImagesPerso = $userSlp->getPersonalisationId() ? $userSlp->getPersonalisationId()->getImage() : "/images/dashboard/soleil-levant3.jpg";

        // R
        $currencyRepo = $this->getDoctrine()->getRepository(Currency::class);
        $mainRevenueRepo = $this->getDoctrine()->getRepository(MainRevenue::class);

        // Table des mois
        $litteralMonths = array(1 => 'Janvier', 2 =>'Février', 3 => 'Mars', 4 => 'Avril', 5 => 'Mai', 6 => 'Juin',
            7 => 'Juillet', 8 => 'Août', 9 => 'Sesptembre', 10 => 'Octobre', 11 => 'Novembre', 12 => 'Décembre');
        $letterMonths = array(1 => 'J', 2 =>'F', 3 => 'M', 4 => 'A', 5 => 'M', 6 => 'J',
            7 => 'J', 8 => 'A', 9 => 'S', 10 => 'O', 11 => 'N', 12 => 'D');
        // On récupère le mois actuel
        $date = date('m')-1+1;
        $year = date('Y');

        if(!$UserConecte->getBudget()){
          $newBudget = new Budget();
          $UserConecte->setBudget($newBudget);
          $this->getDoctrine()->getManager()->persist($newBudget);
          $this->getDoctrine()->getManager()->flush();
        }

        // Récupération de la money du user
        $mainRevenue = $mainRevenueRepo->findPreviousForDate($UserConecte->getBudget(),
            date("Y-m-t H:i:s", mktime(23, 59, 59, date('m'), date('t'), date('Y')))
        );
        if ($mainRevenue != null) {
            $currency = $currencyRepo->findOneById($mainRevenue->getCurrency()->getId());
        } else {
            $currency = ['symbol' => 'CHF'];
        }

        // On calcul le total des dépenses
        $totalSpendingPerMonthForLastTwelve = $SpendingService->calculateTotalPerMonthLastTwelve();
        $totalSpendingPerYear = $SpendingService->calculateTotalPerYear();
        $totalSpending = ['months' =>  $totalSpendingPerMonthForLastTwelve, 'years' => $totalSpendingPerYear];
        // revenus du mois actuel et des précédent
        $revenus = $revenueService->sortedRevenues($UserConecte->getBudget());

        // Excedent du mois actuel et des précédent
        $excedent = $budgetExcedent->excedent($totalSpending, $revenus);

        $excedentMonth = [];
        $excedentActualMonth = 0;
        if($excedent != null){
          $excedentMonth = $excedent['months'];
          $excedentActualMonth = $excedent['actualMonth'];
        }

        // Dépenses des 12 dernier mois
        $totalSpendingPerMonthForLastTwelve = $SpendingService->calculateTotalPerMonthLastTwelve();

        // Récupération d'une personalisation
        $personalisation = $userSlp->getPersonalisationId();

        if($personalisation) {
          $userCover =  $userSlp->getPersonalisationId()->getImage();
          $avatar = $userSlp->getPersonalisationId()->getAvatar();
          // Si la valeur est à null dans la BDD, afficher une image par défaut   
          if($userCover === null) {
            $userCover = "images/dashboard/soleil-levant3.jpg";
          } else if ($avatar === null) {
            $avatar = "images/dashboard/avatar.png";
          }
        }

        // Si user n'a aucune personalisation, afficher des images par défaut
        if(!$personalisation) {
          $userCover = "images/dashboard/soleil-levant3.jpg";
          $avatar = "images/dashboard/avatar.png";
        }
          
         




        // $userCover =  $userSlp->getPersonalisationId() ? $userSlp->getPersonalisationId()->getImage()  : "images/dashboard/soleil-levant3.jpg";
        // $avatar = $userSlp->getPersonalisationId() ? $userSlp->getPersonalisationId()->getAvatar() : "images/dashboard/avatar.png";

        $reponduSocio = true;
        $reponduPyscho= true;
        $reponduEcolo= true;
        $reponduFamille= false;
        $reponduAmi= false;
        $reponduMenage= false;

        return $this->render('site/site_dashboard.html.twig',array(
          'formPsycho' => $formPsycho,
          'reponduSocio' => $reponduSocio,
          'reponduPyscho' => $reponduPyscho,
          'reponduEcolo' => $reponduEcolo,
          'reponduFamille' => $reponduFamille,
          'reponduAmi' => $reponduAmi,
          'reponduMenage' => $reponduMenage,
          'nombreReponses' => $nombreReponses,
          'mobileFeedOne' => $mobileFeedOne,
          'mobileFeedTwo' => $mobileFeedTwo,
          'desktopFeed' => $desktopFeed,
          'user' => $userSlp,
          'counter' => $counter,
          'image' => $ImagesPerso,
          'excedentMonth' => $excedentMonth,
          'letterMonths' => $letterMonths,
          'litteralMonths' => $litteralMonths,
          'date' => $date,
          'excedentActualMonth' => $excedentActualMonth,
          'currency' => $currency,
          'year' => $year,
          'revenus' => $revenus,
          'spending' => $totalSpendingPerMonthForLastTwelve,
          'userCover' => $userCover,
          'avatar' => $avatar
        ));
    }

    /**
    * @Route("/dashboard_personalisation", name="dashboard_personalisation")
    */
    public function DashboardPersonalisation()
    {
      return $this->render('site/site_dashboard_personalisation.html.twig');
    }


    /**
     * @Route("/Mini profil U are what you eat!", name="profil_manger")
     */
    public function profilManger()
    {

        $answerRepo = $this->getDoctrine()->getRepository(Answer::class);
        $userSlpRepo = $this->getDoctrine()->getRepository(UserSlp::class);
        $questionRepo = $this->getDoctrine()->getRepository(Question_master::class);
        $reponseMangerRepo = $this->getDoctrine()->getRepository(Reponse_thematique::class);
        $userSlp = $userSlpRepo->findOneByGaeaUserId($this->getUser()->getId());
        $answers = $answerRepo->findBy(["userSlp" => $userSlp], ["question" => "ASC"]);
        $miniprofilRepo = $this->getDoctrine()->getRepository(Miniprofile::class);
        $answerRepo2 = $this->getDoctrine()->getRepository(Answer_choice::class);
        $miniprofil = $miniprofilRepo->findAll();
        $MangerRepo= $this->getDoctrine()->getRepository(Manger::class);

        //Requetes mini profil par Theme
        $totalQuoi = $reponseMangerRepo->getQuoi($userSlp);
        $totalOu = $reponseMangerRepo->getOu($userSlp);
        $totalComment = $reponseMangerRepo->getComment($userSlp);
        $totalCondition = $reponseMangerRepo->getCondition($userSlp);
        $totalDechet = $reponseMangerRepo->getDechet($userSlp);
        $totalTrouble = $reponseMangerRepo->getTrouble($userSlp);
        $totalInconscient = $reponseMangerRepo->getInconscient($userSlp);
        $totalAllergies = $reponseMangerRepo->getAllergies($userSlp);
        $totalRegimes= $reponseMangerRepo->getRegimes($userSlp);

        //Requetes mini profil Generale
        $totalManger = $totalQuoi[0]['totValue']+$totalOu +$totalComment +$totalCondition +$totalDechet+
                        $totalTrouble +$totalInconscient +$totalAllergies+ $totalRegimes;


        return $this->render('site/profil_manger.html.twig',array(
          'answers' => $answers,'miniprofil' => $miniprofil,'totalQuoi' => $totalQuoi,
          'totalOu' => $totalOu,'totalComment' => $totalComment,'totalCondition' => $totalCondition,
          'totalDechet' => $totalDechet,'totalTrouble' => $totalTrouble,
          'totalInconscient' =>$totalInconscient ,'totalAllergies'=>$totalAllergies,'totalRegimes'=>$totalRegimes,
          'totalManger' => $totalManger

        ));
    }

    /**
     * @Route("/mini-profil-socio-economique", name="profil_socio-economique")
     */
    public function profilSocioeconomique()
    {
        $user = $this->getUser();
        if ($user) {
          $gaeaUserId = $user->getId();
          $userSlp = $this->getDoctrine()->getRepository('AppBundle:UserSlp')->findOneBy(array('gaeaUserId' => $gaeaUserId));
          $miniprofilRepo = $this->getDoctrine()->getRepository(Miniprofile::class);
          $questionRepo = $this->getDoctrine()->getRepository(Question::class);
          $answerRepo = $this->getDoctrine()->getRepository(Answer::class);
          $userSlpRepo = $this->getDoctrine()->getRepository(UserSlp::class);
          $userSlp = $userSlpRepo->findOneBy(array('gaeaUserId' => $gaeaUserId));
          $miniprofil = $miniprofilRepo->findAll();

          //récupération age, id 2
          //récupération la question id 2
          $birthYearQuestion = "Quelle est votre année de naissance ?";
          $birthYearQuestionResult = $questionRepo->findQuestion($birthYearQuestion);

          //récupération de la réponse lié à l'utilisateur et à la question trouvée précédemment
          $answers = $answerRepo->findByUserSlp($userSlp, 2);
          $answer = $answerRepo->findOneByUserSlpAndQuestion($userSlp, $birthYearQuestionResult);
          //récupérer la réponse dans la bonne table(answer_free) Année de naissance
          $age = 0;
          if($answer){
            $birthYear = $answer[0]->getFreeAswere();
            //récupérer l'année en cours
            // et soustraire l'année en cours à l'année

            $age = date("Y") - $birthYear;
          }





          //récupération santé, n°19

          $healthQuestion = "Comment estimez-vous votre niveau de santé ?";
          $healthQuestionResult = $questionRepo->findQuestion($healthQuestion);
          $healthAnswer = $answerRepo->findOneByUserSlpAndQuestion($userSlp, $healthQuestionResult);

          if($healthAnswer){
            $health = $healthAnswer[0]->getScale();
          } else {
              $health = "none";
          }
          
          //récupération situation professionelle
          $professionalSituationQuestion = "Quelle est votre situation professionnelle actuelle? (Vous pouvez choisir plusieurs réponses)";
          $professionalSituationQuestionResult = $questionRepo->findQuestion($professionalSituationQuestion);
          $professionalSituationAnswer = $answerRepo->findOneByUserSlpAndQuestion($userSlp, $professionalSituationQuestionResult);

          if($professionalSituationAnswer){
            //$professionalSituation = $professionalSituationAnswer[0]->getChoices();
            $professionalSituation = $professionalSituationAnswer[0]->getChoices()[0]->getName();
          } else {
              $professionalSituation = "Indéfinie";
          }

          //récupération statut de la situation
            $professionalStatutQuestion = "Quel statut avez-vous actuellement dans votre activité professionnelle ?";
            $professionalStatutResult = $questionRepo->findQuestion($professionalStatutQuestion);
            $professionalStatutAnswer = $answerRepo->findOneByUserSlpAndQuestion($userSlp, $professionalStatutResult);
            if ($professionalStatutAnswer) {
                $professionalStatut = $professionalStatutAnswer[0]->getChoices()[0]->getName();
            } else {
                $professionalStatut = "Indéfinie";
            }

          //récupération de la question n°12
          $wageQuestion = "Quel est le revenu net annuel de l'ensemble de votre ménage en CHF?";
          $wageQuestionResult = $questionRepo->findQuestion($wageQuestion);

          //récupération de la réponse liée à l'utilisateur et l'id de la question trouvée précédemment
          $annualWageAnswer = $answerRepo->findOneByUserSlpAndQuestion($userSlp, $wageQuestionResult);

          if($annualWageAnswer){
            $annualWage = $annualWageAnswer[0]->getChoices();
            //$monthlyWage = $annualWage / 12;
            $annualWageValue = $annualWage[0]->getName();
            if ($annualWageValue=="Moins de 12'000") {
              //$min=50000;$max=70000;
              $maxMonthlyWage = 12000 / 12;
            } else if ($annualWageValue=="12'000 - 30'000") {
              //$min=50000;$max=70000;
              $minMonthlyWage = 12000 / 12;$maxMonthlyWage = 30000 / 12;
            } else if ($annualWageValue == "30'000 - 50'000") {
              //$min=70000;$max=90000;
              $minMonthlyWage = 30000 / 12;$maxMonthlyWage = 50000 / 12;
            } else if ($annualWageValue == "50'000 - 70'000") {
              //$min=50000;$max=70000;
              $minMonthlyWage = 50000 / 12;$maxMonthlyWage = 70000 / 12;
            } else if ($annualWageValue == "70'000 - 90'000") {
              //$min=70000;$max=90000;
              $minMonthlyWage = 70000 / 12;$maxMonthlyWage = 90000 / 12;
            } else if ($annualWageValue == "90'000 - 110'000") {
              //$min=90000;$max=110000;
              $minMonthlyWage = 90000 / 12;$maxMonthlyWage = 110000 / 12;
            } else if ($annualWageValue == "110'000 - 150'000") {
              //$min=110000;$max=150000;
              $minMonthlyWage = 110000 / 12;$maxMonthlyWage = 150000 / 12;
            } else if ($annualWageValue == "Plus de 150'000") {
              //$min=150000;$max="infinity";
              $minMonthlyWage = 150000 / 12;$maxMonthlyWage = "infinity";
            }
          } else {
              $annualWage = 0;
              $minMonthlyWage = 0;
              $maxMonthlyWage = 0;
          }
          


          //récupération sexe biologique

          $biologicalGenderQuestion = "Quel est votre sexe biologique ?";
          $biologicalGenderQuestionResult = $questionRepo->findQuestion($biologicalGenderQuestion);
          $biologicalGenderAnswer = $answerRepo->findOneByUserSlpAndQuestion($userSlp, $biologicalGenderQuestionResult);
          if ($biologicalGenderAnswer) {
              $biologicalGender = $biologicalGenderAnswer[0]->getChoices()[0]->getName();
          }

          //handicap
          $handicapQuestion = "Avez-vous un ou plusieurs handicap(s) physique(s)?";
          $handicapQuestionResult = $questionRepo->findQuestion($handicapQuestion);
          $handicapAnswer = $answerRepo->findOneByUserSlpAndQuestion($userSlp, $handicapQuestionResult);
          $disabled = 'Non';
          if( $handicapAnswer){
            $disabled = $handicapAnswer[0]->getChoices()[0]->getName();
          }

          if (/*$professionalStatut == "Cadres dirigeants / Professions intellectuelles" AND */
              $professionalStatut == "Cadre supérieur" AND $minMonthlyWage > 8000 AND $health >= 6 or
              $professionalStatut == "Cadre moyen" AND $minMonthlyWage > 8000 AND $health >= 6
          ) {
            $socioEconomicMiniProfileText = "Vous êtes cadre dirigeant ou profession intellectuelle et vous exercez une activité de management ou de réflexion. Vous êtes donc amené à régulièrement prendre des décisions ou réfléchir sur un sujet en particulier. Pourquoi ne pas appliquer votre activité professionnelle à votre vie de tous les jours et au développement durable ? 
            Vous n’avez probablement pas beaucoup de temps, mais disposez d’une expertise dans votre domaine (diplôme) et de revenus assez conséquents. Vous avez reçu une bonne éducation et vous avez donc un meilleur accès aux informations et à la réflexion concernant la situation d’urgence climatique dans laquelle nous sommes. Vous devez vous renseigner sur les pratiques favorisant le développement durable et les appliquer dans votre quotidien!
            Étant en bon état de santé, vous pouvez vous permettre de faire des activités bénéfiques et pour la nature et pour vous ! Grâce au programme SLP, vous pouvez aussi faire attention à ce que vous consommez et donc vous orienter vers des produits éco-responsables. 
            Communiquez aussi autour de vous sur ce que vous faîtes pour le développement durable et ainsi donner l’exemple pour un changement de comportement général.
            Vous pouvez également soutenir des projets et des initiatives écologiquement positifs. Si c’est le cas, jetez un coup d’oeil à notre page “projets” et réduisez ainsi votre empreinte écologique!
            ";
            $condition = "Revenu > 8000";
          } else if (/*$professionalSituation == "Cadres dirigeants / Professions intellectuelles" AND */
          $professionalStatut == "Cadre supérieur" AND $minMonthlyWage > 8000  AND $health <= 5 or
          $professionalStatut == "Cadre moyen" AND $minMonthlyWage > 8000  AND $health <= 5
          ) {
            $socioEconomicMiniProfileText = "Vous êtes cadre dirigeant ou profession intellectuelle et vous exercez une activité de management ou de réflexion. Vous êtes donc amené à régulièrement prendre des décisions ou réfléchir sur un sujet en particulier. Pourquoi ne pas appliquer votre activité professionnelle à votre vie de tous les jours et au développement durable ? 
            Vous n’avez probablement pas beaucoup de temps, mais disposez d’une expertise dans votre domaine (diplôme) et de revenus assez conséquents. Vous avez reçu une bonne éducation et vous avez donc un meilleur accès aux informations et à la réflexion concernant la situation d’urgence climatique dans laquelle nous sommes. Vous devez vous renseigner sur les pratiques favorisant le développement durable et les appliquer dans votre quotidien. Votre état de santé vous empêche sûrement de pratiquer une activité physique régulière mais il doit être pour vous une motivation à une consommation durable et pour l’environnement et pour vous. Grâce au programme SLP, vous pouvez faire attention à ce que vous consommez et donc vous orienter vers des produits éco-responsables. Avec de meilleures habitudes, vous pourrez améliorer votre état de santé !
            Communiquez aussi autour de vous sur ce que vous faîtes pour le développement durable et ainsi donner l’exemple.
            Vous pouvez également soutenir des projets et des initiatives écologiquement positifs. Si c’est le cas, jetez un coup d’oeil à notre page “projets” et réduisez ainsi votre empreinte écologique!            
            ";
            $condition = "Revenu > 8000";
          } else if (/*$professionalSituation == "Professions intermédiaires / Employés et salariés qualifiés" AND */
              $professionalStatut == "Employé-e" AND $minMonthlyWage > 6000 AND $maxMonthlyWage < 8000 AND $health >= 6
          ) {
            $socioEconomicMiniProfileText = "Vous disposez de revenus suffisants pour faire attention à votre consommation. Il ne tient qu’à vous de vous sensibiliser aux enjeux environnementaux et modifier durablement vos habitudes de consommation. Mais pour cela vous n’êtes pas seul ! Suivez nos conseils pour chercher les bonnes informations et mieux allouer vos revenus vers une consommation durable. 
            Vous avez sûrement reçu une éducation spécialisée, ouvrez-vous donc au développement durable et impliquez-vous comme durant votre formation. Si vous mettez à profit autant d’énergie, vous pourrez facilement devenir un véritable consom’acteur et ainsi optimiser votre impact sur l’environnement.
            Grâce à votre bon état de santé, agissez concrètement en faveur du développement durable en mettant en place et en entretenant un potage personnel, par exemple.
            ";
            $condition = "6000 < Revenu < 8000";
          } else if (/*$professionalSituation == "Professions intermédiaires / Employés et salariés qualifiés" AND */
              $professionalStatut == "Employé-e" AND $minMonthlyWage > 6000 AND $maxMonthlyWage < 8000 AND $health <= 5
          ) {
            $socioEconomicMiniProfileText = "Vous disposez de revenus suffisants pour faire attention à votre consommation. D’autant plus que votre état de santé devrait vous pousser à faire attention à vous et votre environnement. Il est donc de votre responsabilité de vous sensibiliser aux enjeux environnementaux et modifier durablement vos habitudes de consommation. Mais pour cela vous n’êtes pas seul ! Suivez nos conseils pour chercher les bonnes informations et mieux allouer vos revenus vers une consommation durable. 
            De plus, profitez du programme SLP pour rejoindre une communauté qui peut avoir les mêmes préoccupations que vous et qui peut aussi vous apporter des solutions ! 
            Vous avez sûrement reçu une éducation spécialisée, ouvrez-vous donc au développement durable et impliquez-vous comme durant votre formation. Si vous mettez à profit autant d’énergie, vous pourrez facilement devenir un véritable consom’acteur et ainsi optimiser votre impact sur l’environnement.";
            $condition = "6000 < Revenu < 8000";
          } else if (/*$professionalSituation == "Professions salariées peu qualifiées" AND */$minMonthlyWage > 4000 AND $maxMonthlyWage < 6000 AND $health >= 6) {
            $socioEconomicMiniProfileText = "Vous n’avez pas de très gros revenus… Profitez des différents outils de SLP pour adapter votre consommation à ces derniers. Grâce à SLP, vous aurez toutes les clés pour mettre en place un potager par exemple. Cela vous permettra de produire vous-même vos fruits et légumes toute l’année à un coût réduit.
            Ce n’est pas parce que vous avez un revenu faible que nous n’avez pas un rôle important dans la prise de conscience allant dans le sens du développement durable ! En effet, vous faites partie d’une grande communauté qui peut faire bouger les choses et surtout les décisions des politiques.
            Alors regroupez votre entourage autour de notre projet de Sustainable Living Community! Cela vous permettra de mieux gérer votre consommation et de rejoindre une communauté désireuse d’agir ensemble pour le développement durable.
            ";
            $condition = "4000 < Revenu < 6000";
          } else if (/*$professionalSituation == "Professions salariées peu qualifiées" AND */$minMonthlyWage > 4000 AND $maxMonthlyWage < 6000 AND $health <= 5) {
            $socioEconomicMiniProfileText = "Vous n’avez pas de très gros revenus et votre santé vous joue des tours… Profitez des différents outils de SLP pour adapter votre consommation à ces contraintes. Grâce à SLP, vous aurez toutes les clés pour mettre en place une consommation durable qui sera bénéfique et à votre santé et au développement durable. 
            Ce n’est pas parce que vous avez un revenu faible que nous n’avez pas un rôle important dans la prise de conscience allant dans le sens du développement durable ! En effet, vous faites partie d’une vaste communauté qui peut faire bouger les choses et surtout les décisions des politiques.
            Alors regroupez votre entourage autour de notre projet de Sustainable Living Community! Cela vous permettra de mieux gérer votre consommation et de rejoindre une communauté désireuse d’agir ensemble pour le développement durable.
            ";
            $condition = "4000 < Revenu < 6000";
          } else if ($professionalSituation == "Retraité-e" AND $age >= 60 AND $health >= 6) {
            $socioEconomicMiniProfileText = "Vous êtes retraité et en bonne santé, c’est donc le moment pour prendre le temps d’agir en faveur du développement durable ! Vous êtes plus disponible que lorsque vous travailliez, c’est donc l’occasion pour vous de prendre le temps. Le temps d’aller au marché acheter des produits locaux, le temps de cuisiner sainement, et pourquoi pas le temps de faire un potager pour avoir vos propre fuits et légumes ? N’hésitez pas à utiliser tous les outils de SLP pour bien vous aiguiller dans vos choix. 
            Prenez aussi le temps d’éduquer vos petits enfants et tout votre entourage au tri des déchets et à une consommation respectueuse de la nature. Grâce à tous ces efforts, vous leur donnerez toutes les clés pour avoir les capacités de répondre à leurs besoins tout en conservant celles des générations suivantes.
            Profitez également du programme SLP pour entrer et rester en contact avec une communauté unie autour de valeurs communes. Ce sera aussi l’occasion de communiquer et partager vos expériences et vos réussites !            
            ";
            $condition = null;
          } else if ($professionalSituation == "Retraité-e" AND $age >= 60 AND $health <= 5) {
            $socioEconomicMiniProfileText = "Vous êtes retraité et votre état de santé vous contraint dans vos choix... N’hésitez pas à utiliser tous les outils de SLP pour bien les aiguiller et ainsi augmenter votre espérance de vie.
            Vous avez tout de même le temps d’éduquer vos petits enfants et tout votre entourage au tri des déchets et à une consommation respectueuse de la nature. Grâce à tous ces efforts, vous leur donnerez toutes les clés pour avoir les capacités de répondre à leurs besoins tout en conservant celles des générations suivantes. 
            Profitez également du programme SLP pour entrer et rester en contact avec une communauté unie autour de valeurs communes. Ce sera aussi l’occasion de passer du temps à communiquer et partager vos expériences et vos réussites !
            ";
            $condition = null;
          } else if ($professionalSituation == "Etudiant-e" AND $maxMonthlyWage < 4000 AND $health >= 6) {
            $socioEconomicMiniProfileText = "Vous êtes étudiant et vous avez surement du mal pour vos fins de mois. Vous pouvez cependant faire les bons choix ! Profitez des différents outils de SLP pour adapter votre consommation à vos revenus. Renseignez vous auprès de votre université pour voir s’ils proposent des paniers de fruits/légumes locaux à un prix étudiant. Pensez à emporter votre repas plutôt que de manger au fast food, ou manger au restaurant universitaire qui propose souvent des repas équilibrés.
            Vous êtes jeune et en bonne santé, c’est sur vous que repose la prise de conscience générale sur la situation environnementale. N’hésitez pas à participer aux green-walks qui s’organisent de partout dans le monde pour influencer les décideurs politiques. Profitez aussi de votre temps libre et de votre réseau très vaste pour faire avancer les choses. Pourquoi ne pas parler de la communauté SLP autour de vous ? Vous permettrez ainsi de faire vivre une communauté dont l’intérêt commun est le développement durable !
            Enfin, n’oubliez pas que gaea21 est aussi un organisme post-formation qui peut vous aider à compléter votre formation de base et vous apporter des compétences indispensables à l’entrée au marché du travail. 
            ";
            $condition = null;
          } else if ($professionalSituation == "Etudiant-e" AND $maxMonthlyWage < 4000 AND $health <= 5) {
            $socioEconomicMiniProfileText = "Vous êtes étudiant et vous avez surement du mal pour vos fins de mois. De plus, vous ne semblez pas être en bon état de santé… Vous pouvez cependant faire des bons choix qui seront bénéfiques et à votre santé et à votre porte-monnaie! Profitez des différents outils de SLP pour adapter votre consommation à vos revenus et les contraintes liées à votre état de santé. Vous pouvez vous renseigner vous auprès de votre université pour voir s’ils proposent des paniers de fruits/légumes locaux à un prix étudiant. Pensez à emporter votre repas plutôt que de manger au fast food, ou manger au restaurant universitaire qui propose souvent des repas équilibrés. 
            Vous êtes jeune et vous avez accès à l’information, c’est sur vous que repose la prise de conscience générale sur la situation environnementale. Profitez de votre temps libre et de votre réseau pour faire avancer les choses. Pourquoi ne pas parler de la communauté SLP autour de vous ? Vous permettrez ainsi de faire vivre une communauté dont l’intérêt commun est le développement durable !
            Enfin, n’oubliez pas que gaea21 est aussi un organisme post-formation qui peut vous aider à compléter votre formation de base et vous apporter des compétences indispensables à l’entrée au marché du travail.
            ";
            $condition = null;
          } else if ($professionalSituation == "Au chômage" AND $maxMonthlyWage < 4000 AND $age >= 30 AND $age <= 60 AND $health >= 6) {
            $socioEconomicMiniProfileText = "Vous êtes à la recherche d’un emploi. Mettez à profit votre temps libre pour vous engager concrètement et activement dans une activité associative. Cela vous permettra d’avoir un impact positif sur l’environnement tout en remplissant votre emploi du temps. 
            Vous pouvez rejoindre la communauté SLP afin de communiquer et de passer du temps avec des personnes investies dans le développement durable.
            Profitez aussi des différents outils de SLP pour vous préparer à un changement dans votre mode de consommation qui pourra s’adapter à votre faible niveau de revenus. 
            Enfin, avez-vous pensé à intégrer gaea21 pour votre projet professionnel ? En effet, nous sommes un organisme de post-formation où vous pourrez apprendre des compétences utiles à votre intégration au marché du travail!
            ";
            $condition = null;
          } else if ($professionalSituation == "Au chômage" AND $maxMonthlyWage < 4000 AND $age >= 30 AND $age <= 60 AND $health <= 5) {
            $socioEconomicMiniProfileText = "Vous êtes à la recherche d’un emploi. Mettez à contribution votre temps libre pour prendre soin de vous et de l’environnement. Vous pouvez vous engager dans une activité associative comme celle de gaea21. Cela vous permettra d’avoir un impact positif sur l’environnement et de conserver voire améliorer votre état de santé. 
            Ayant plus de temps libre, vous pouvez aussi vous renseigner sur la situation d’urgence climatique et communiquer en connaissance de cause. Profitez de la communauté SLP pour en savoir plus et pouvoir passer à l’action à votre échelle.
            Utilisez aussi les différents outils de SLP pour vous préparer à des changements dans votre consommation qui seront plus adaptés à vos faibles revenus et votre état de santé.
            Enfin, avez-vous pensé à intégrer gaea21 pour votre projet professionnel ? En effet, nous sommes un organisme de post-formation où vous pourrez apprendre des compétences utiles à votre intégration au marché du travail!
            ";
            $condition = null;
          } else if ($professionalSituation == "Femme/homme au foyer" AND $health >= 6 AND $maxMonthlyWage < 4000) {
            $socioEconomicMiniProfileText = "Vous êtes père ou mère au foyer. C’est à vous que revient la responsabilité de rendre la consommation de votre foyer durable. Même si vos journées sont bien chargées, vous pouvez prendre le temps de cuisiner de bon plats équilibrés et sains à vos enfants. Apprenez leur les valeurs écologiques, en faisant des balades en forêts, en allant au marché local ensemble acheter des fruits et légumes, en les éduquant au tri des déchets et au développement durable. Toutes ces choses s'apprennent et passent par l’éducation et c’est ainsi que l’on aura une future génération qui pourra changer les choses. Visitez le site et remplissez le profil famille pour prendre connaissance des différents outils mis à votre disposition dans ce but.";
            $condition = null;
          } else if ($health < 4 AND $disabled == "Oui") {
            $socioEconomicMiniProfileText = "Vous êtes père ou mère au foyer. C’est à vous que revient la responsabilité de rendre la consommation de votre foyer durable. Même si vos journées sont bien chargées, vous pouvez prendre le temps de cuisiner de bon plats équilibrés et sains à vos enfants. Apprenez leur les valeurs écologiques, en faisant des balades en forêts, en allant au marché local ensemble acheter des fruits et légumes, en les éduquant au tri des déchets et au développement durable. Toutes ces choses s'apprennent et passent par l’éducation et c’est ainsi que l’on aura une future génération qui pourra changer les choses. Visitez le site et remplissez le profil famille pour prendre connaissance des différents outils mis à votre disposition dans ce but.";
            $condition = null;
          } else {
            $socioEconomicMiniProfileText = "Votre profil n'a pas pu être défini.";
            $condition = null;
          }

          /*
          foreach($answers as $answer){
            $choice = $this->getDoctrine()->getRepository(choice::class)->findOneById($answer->getId());
            $freeAnswer = $this->getDoctrine()->getRepository(answer_free::class)->findOneById($answer->getId());
            $answersAndChoices[] = $answer.$choice.$freeAnswer;
          }

          $discr = $answersAndChoices->getDiscr();

          if ($discr == "answer"){
            $monthlyWage = $answersAndChoices->getQuestion();
          } elseif ($discr == "answer_choice"){
            $monthlyWage = $answersAndChoices->getName();
          } elseif ($discr == "choice"){
            $monthlyWage = $answersAndChoices->getFreeAswere();
          }

          $annualWage = $monthlyWage*12;

          if($healthvalue <= 5){
            $health = 'bad';
          }else{
            $health = 'good';
          }
          */
        } else {
          $answersAndChoices = null;
          $miniprofil = null;
          $health = null;
          $annualWage = null;
          $socioEconomicMiniProfileText = null;
        }

        return $this->render('site/profil_socio-economique.html.twig', [
            'miniproText'=> $socioEconomicMiniProfileText,
            'miniprofil' => $miniprofil,
            'health' => $health,
            'maxwage' => $maxMonthlyWage,
            'minwage' => $minMonthlyWage,
            'professionalsituation' => $professionalSituation,
            'age' => $age,
            'condition' => $condition
        ]);

    }
    /**
     * @Route("/Mini profil Psychologique", name="profil_psychologique")
     */
     public function profilPsychologiqueAction()
     {
        return $this->render('site/profil_psychologique.html.twig');
     }

     
}
