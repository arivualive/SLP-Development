<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Service\SurveyForFixtures;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use QuestionBundle\Entity\Choice;
use QuestionBundle\Entity\Conditional_question_choice;
use QuestionBundle\Entity\Question_choice;
use QuestionBundle\Entity\Survey;
use QuestionBundle\Entity\Theme;
use QuestionBundle\Entity\Question;
use Symfony\Component\HttpFoundation\File\File;




class QuestionnaireMangerFixtures extends AbstractFixture implements FixtureInterface
{

    public function load(ObjectManager $manager)
    {
//        $survey = new Survey();
//        $survey->setName("Questionnaire Manger");
//
//        $manager->persist($survey);
//        $survey = 'Questionnaire manger - 2';
//        $surveyForFixtures = new SurveyForFixtures();
//        $survey = $surveyForFixtures->getSurveyForFixtures('2');

        $theme1 = new Theme();
        $theme1->setName('Produits céréaliers');
        $theme1->setFavicon('test');
        $theme1->setSoustheme('Consommer');


        $manager->persist($theme1);


        $question1 = new Question_choice();
        $question1->setMaster(0);
        $question1->setNumber(1);
        $question1->setTheme($theme1);
        $question1->setQuestion("A quelle fréquence consommez-vous des féculents
         (pâtes, riz, pomme de terre, pain,..) ?");
        $question1->setIsUnique(1);
        $question1->setBackgroundLeft('#FFE059');
        $question1->setBackgroundRight('#B9B664');


        $manager->persist($question1);


        $choice1_1 = new Choice();
        $choice1_1->setConditional(1);
        $choice1_1->setName("J’en consomme à chaque repas (petit déjeuner, déjeuner, diner)");
        $choice1_1->setQuestionChoice($question1);
        $choice1_1->setComment('Commentaire test 1');
        $choice1_2 = new Choice();
        $choice1_2->setConditional(1);
        $choice1_2->setName("J’en consomme plus de trois fois par jour");
        $choice1_2->setQuestionChoice($question1);
        $choice1_2->setComment('Commentaire test 2');
        $choice1_3 = new Choice();
        $choice1_3->setConditional(1);
        $choice1_3->setName("J’en consomme deux fois par jour");
        $choice1_3->setQuestionChoice($question1);
        $choice1_3->setComment('Commentaire test 3');
        $choice1_4 = new Choice();
        $choice1_4->setConditional(1);
        $choice1_4->setName("J’en consomme une fois par jour");
        $choice1_4->setQuestionChoice($question1);
        $choice1_4->setComment('Commentaire test 4');
        $choice1_5 = new Choice();
        $choice1_5->setConditional(0);
        $choice1_5->setName("Je n’en consomme pas du tout");
        $choice1_5->setQuestionChoice($question1);
        $choice1_5->setComment('Commentaire test 5');


        $manager->persist($choice1_1);
        $manager->persist($choice1_2);
        $manager->persist($choice1_3);
        $manager->persist($choice1_4);
        $manager->persist($choice1_5);

        $question2 = new Conditional_question_choice();
        $question2->setTriggerQuestion($question1);
        $question2->setBackgroundLeft("#FFE059");
        $question2->setBackgroundRight("#B9B664");
        $question2->setIsUnique(1);
        $question2->setTheme($theme1);
        $question2->setNumber(2);
        $question2->setMaster(0);
        $question2->setQuestion("Est-ce que vous consommez différentes sortes
de féculents ?");

        $manager->persist($question2);

        $choice2_1 = new Choice();
        $choice2_1->setConditional(1);
        $choice2_1->setName("Oui, je ne mange jamais 2 fois la même chose dans la semaine");
        $choice2_1->setQuestionChoice($question2);
        $choice2_1->setComment('Commentaire test 1');
        $choice2_2 = new Choice();
        $choice2_2->setConditional(1);
        $choice2_2->setName("Oui, c'est très rare que je mange 2 fois la même chose dans la semaine");
        $choice2_2->setQuestionChoice($question2);
        $choice2_2->setComment('Commentaire test 2');
        $choice2_3 = new Choice();
        $choice2_3->setConditional(1);
        $choice2_3->setName("Oui c'est rare que je mange 2 fois la même chose dans la semaine");
        $choice2_3->setQuestionChoice($question2);
        $choice2_3->setComment('Commentaire test 3');
        $choice2_4 = new Choice();
        $choice2_4->setConditional(2);
        $choice2_4->setName("Non je consomme assez souvent la même chose");
        $choice2_4->setQuestionChoice($question2);
        $choice2_4->setComment('Commentaire test 4');
        $choice2_5 = new Choice();
        $choice2_5->setConditional(2);
        $choice2_5->setName("Non je consomme très souvent la même chose");
        $choice2_5->setQuestionChoice($question2);
        $choice2_5->setComment('Commentaire test 5');
        $choice2_6 = new Choice();
        $choice2_6->setConditional(2);
        $choice2_6->setName("Non je consomme toujours le même");
        $choice2_6->setQuestionChoice($question2);
        $choice2_6->setComment('Commentaire test 5');

        $manager->persist($choice2_1);
        $manager->persist($choice2_2);
        $manager->persist($choice2_3);
        $manager->persist($choice2_4);
        $manager->persist($choice2_5);
        $manager->persist($choice2_6);



        $question3 = new Conditional_question_choice();
        $question3->setTriggerQuestion($question2);
        $question3->setBackgroundLeft("#FFE059");
        $question3->setBackgroundRight("#B9B664");
        $question3->setIsUnique(0);
        $question3->setTheme($theme1);
        $question3->setNumber(3);
        $question3->setMaster(0);
        $question3->setQuestion("Lesquels parmi ceux-ci : Pâtes/boulghour/ebly/semoule,
riz, pomme de terre/patate douce, quinoa, pain, petits
pois/maïs/polenta ?");

        $manager->persist($question3);

        $choice3_1 = new Choice();
        $choice3_1->setConditional(1);
        $choice3_1->setName("Pâtes/boulghour/ebly/semoule");
        $choice3_1->setQuestionChoice($question3);
        $choice3_1->setComment('Commentaire test 1');
        $choice3_2 = new Choice();
        $choice3_2->setConditional(1);
        $choice3_2->setName("Riz");
        $choice3_2->setQuestionChoice($question3);
        $choice3_2->setComment('Commentaire test 2');
        $choice3_3 = new Choice();
        $choice3_3->setConditional(1);
        $choice3_3->setName("Quinoa");
        $choice3_3->setQuestionChoice($question3);
        $choice3_3->setComment('Commentaire test 3');
        $choice3_4 = new Choice();
        $choice3_4->setConditional(0);
        $choice3_4->setName("Pain");
        $choice3_4->setQuestionChoice($question3);
        $choice3_4->setComment('Commentaire test 4');
        $choice3_5 = new Choice();
        $choice3_5->setConditional(0);
        $choice3_5->setName("Pomme de terre/patate douce");
        $choice3_5->setQuestionChoice($question3);
        $choice3_5->setComment('Commentaire test 5');
        $choice3_6 = new Choice();
        $choice3_6->setConditional(0);
        $choice3_6->setName("Petits pois/maïs/polenta");
        $choice3_6->setQuestionChoice($question3);
        $choice3_6->setComment('Commentaire test 5');

        $manager->persist($choice3_1);
        $manager->persist($choice3_2);
        $manager->persist($choice3_3);
        $manager->persist($choice3_4);
        $manager->persist($choice3_5);
        $manager->persist($choice3_6);



        $question4 = new Conditional_question_choice();
        $question4->setTriggerQuestion($question2);
        $question4->setBackgroundLeft("#FF988C");
        $question4->setBackgroundRight("#99C9E3");
        $question4->setIsUnique(0);
        $question4->setTheme($theme1);
        $question4->setNumber(4);
        $question4->setMaster(0);
        $question4->setQuestion("Lesquels parmi les suivants ?");

        $manager->persist($question4);

        $choice4_1 = new Choice();
        $choice4_1->setConditional(1);
        $choice4_1->setName("Pâtes/boulghour/ebly/semoule");
        $choice4_1->setQuestionChoice($question4);
        $choice4_1->setComment('Commentaire test 1');
        $choice4_2 = new Choice();
        $choice4_2->setConditional(1);
        $choice4_2->setName("Riz");
        $choice4_2->setQuestionChoice($question4);
        $choice4_2->setComment('Commentaire test 2');
        $choice4_3 = new Choice();
        $choice4_3->setConditional(1);
        $choice4_3->setName("Quinoa");
        $choice4_3->setQuestionChoice($question4);
        $choice4_3->setComment('Commentaire test 3');
        $choice4_4 = new Choice();
        $choice4_4->setConditional(0);
        $choice4_4->setName("Pain");
        $choice4_4->setQuestionChoice($question4);
        $choice4_4->setComment('Commentaire test 4');
        $choice4_5 = new Choice();
        $choice4_5->setConditional(0);
        $choice4_5->setName("Pomme de terre/patate douce");
        $choice4_5->setQuestionChoice($question4);
        $choice4_5->setComment('Commentaire test 5');
        $choice4_6 = new Choice();
        $choice4_6->setConditional(0);
        $choice4_6->setName("Petits pois/maïs/polenta");
        $choice4_6->setQuestionChoice($question4);
        $choice4_6->setComment('Commentaire test 5');

        $manager->persist($choice4_1);
        $manager->persist($choice4_2);
        $manager->persist($choice4_3);
        $manager->persist($choice4_4);
        $manager->persist($choice4_5);
        $manager->persist($choice4_6);

        $question5 = new Question_choice();
        $question5->setMaster(0);
        $question5->setNumber(5);
        $question5->setTheme($theme1);
        $question5->setQuestion("Consommez-vous des féculents
complets ?");
        $question5->setIsUnique(1);
        $question5->setBackgroundLeft('#FF988C');
        $question5->setBackgroundRight('#99C9E3');


        $manager->persist($question5);


        $choice5_1 = new Choice();
        $choice5_1->setConditional(0);
        $choice5_1->setName("Toujours (Exemple 10 fois sur 10)");
        $choice5_1->setQuestionChoice($question5);
        $choice5_1->setComment('Commentaire test 1');
        $choice5_2 = new Choice();
        $choice5_2->setConditional(0);
        $choice5_2->setName("Très souvent (Exemple 9 fois sur 10)");
        $choice5_2->setQuestionChoice($question5);
        $choice5_2->setComment('Commentaire test 2');
        $choice5_3 = new Choice();
        $choice5_3->setConditional(0);
        $choice5_3->setName("Souvent (exemple 7 fois sur 10)");
        $choice5_3->setQuestionChoice($question5);
        $choice5_3->setComment('Commentaire test 3');
        $choice5_4 = new Choice();
        $choice5_4->setConditional(0);
        $choice5_4->setName("Pas de manière régulière (4 fois sur 10)");
        $choice5_4->setQuestionChoice($question5);
        $choice5_4->setComment('Commentaire test 4');
        $choice5_5 = new Choice();
        $choice5_5->setConditional(0);
        $choice5_5->setName("Quasiment jamais (Exemple 2 fois sur 10)");
        $choice5_5->setQuestionChoice($question5);
        $choice5_5->setComment('Commentaire test 5');
        $choice5_6 = new Choice();
        $choice5_6->setConditional(0);
        $choice5_6->setName("Jamais ( Exemple 0 fois sur 10)");
        $choice5_6->setQuestionChoice($question5);
        $choice5_6->setComment('Commentaire test 5');

        

        $manager->persist($choice5_1);
        $manager->persist($choice5_2);
        $manager->persist($choice5_3);
        $manager->persist($choice5_4);
        $manager->persist($choice5_5);
        $manager->persist($choice5_6);


        $theme2 = new Theme();
        $theme2->setName('Légumes secs');
        $theme2->setFavicon('test');
        $theme2->setSoustheme('Consommer');

        $manager->persist($theme2);


        $question6 = new Question_choice();
        $question6->setMaster(0);
        $question6->setNumber(6);
        $question6->setTheme($theme2);
        $question6->setQuestion("A quelle fréquence consommez-vous des légumes
secs (lentilles, fèves, soja, haricots blancs,...) ?");
        $question6->setIsUnique(1);
        $question6->setBackgroundLeft('#FF988C');
        $question6->setBackgroundRight('#99C9E3');


        $manager->persist($question6);


        $choice6_1 = new Choice();
        $choice6_1->setConditional(1);
        $choice6_1->setName("J’en consomme deux fois par jour");
        $choice6_1->setQuestionChoice($question6);
        $choice6_1->setComment('Commentaire test 1');
        $choice6_2 = new Choice();
        $choice6_2->setConditional(1);
        $choice6_2->setName("J’en consomme une fois par jour");
        $choice6_2->setQuestionChoice($question6);
        $choice6_2->setComment('Commentaire test 2');
        $choice6_3 = new Choice();
        $choice6_3->setConditional(1);
        $choice6_3->setName("J’en consomme plusieurs fois par semaine mais pas tous les jours");
        $choice6_3->setQuestionChoice($question6);
        $choice6_3->setComment('Commentaire test 3');
        $choice6_4 = new Choice();
        $choice6_4->setConditional(1);
        $choice6_4->setName("J’en consomme rarement");
        $choice6_4->setQuestionChoice($question6);
        $choice6_4->setComment('Commentaire test 4');
        $choice6_5 = new Choice();
        $choice6_5->setConditional(1);
        $choice6_5->setName("J’en consomme très rarement");
        $choice6_5->setQuestionChoice($question6);
        $choice6_5->setComment('Commentaire test 5');
        $choice6_6 = new Choice();
        $choice6_6->setConditional(0);
        $choice6_6->setName("Je n’en consomme jamais");
        $choice6_6->setQuestionChoice($question6);
        $choice6_6->setComment('Commentaire test 6');

        $manager->persist($choice6_1);
        $manager->persist($choice6_2);
        $manager->persist($choice6_3);
        $manager->persist($choice6_4);
        $manager->persist($choice6_5);
        $manager->persist($choice6_6);

        $question7 = new Conditional_question_choice();
        $question7->setTriggerQuestion($question6);
        $question7->setBackgroundLeft("#C78C5C");
        $question7->setBackgroundRight("#FFE059");
        $question7->setIsUnique(0);
        $question7->setTheme($theme2);
        $question7->setNumber(7);
        $question7->setMaster(0);
        $question7->setQuestion("Lesquels parmi les suivants ?");

        $manager->persist($question7);

        $choice7_1 = new Choice();
        $choice7_1->setConditional(0);
        $choice7_1->setName("Lentilles");
        $choice7_1->setQuestionChoice($question7);
        $choice7_1->setComment('Commentaire test 1');
        $choice7_2 = new Choice();
        $choice7_2->setConditional(0);
        $choice7_2->setName("Fèves");
        $choice7_2->setQuestionChoice($question7);
        $choice7_2->setComment('Commentaire test 2');
        $choice7_3 = new Choice();
        $choice7_3->setConditional(0);
        $choice7_3->setName("Pois cassés");
        $choice7_3->setQuestionChoice($question7);
        $choice7_3->setComment('Commentaire test 3');
        $choice7_4 = new Choice();
        $choice7_4->setConditional(0);
        $choice7_4->setName("Soja");
        $choice7_4->setQuestionChoice($question7);
        $choice7_4->setComment('Commentaire test 4');
        $choice7_5 = new Choice();
        $choice7_5->setConditional(0);
        $choice7_5->setName("Haricots blancs/rouges");
        $choice7_5->setQuestionChoice($question7);
        $choice7_5->setComment('Commentaire test 5');
        $choice7_6 = new Choice();
        $choice7_6->setConditional(0);
        $choice7_6->setName("Pois chiche");
        $choice7_6->setQuestionChoice($question7);
        $choice7_6->setComment('Commentaire test 6');

        $manager->persist($choice7_1);
        $manager->persist($choice7_2);
        $manager->persist($choice7_3);
        $manager->persist($choice7_4);
        $manager->persist($choice7_5);
        $manager->persist($choice7_6);


        $theme3 = new Theme();
        $theme3->setName('Végétaux');
        $theme3->setFavicon('test');
        $theme3->setSoustheme('Consommer');

        $manager->persist($theme3);


        $question8 = new Question_choice();
        $question8->setMaster(0);
        $question8->setNumber(8);
        $question8->setTheme($theme3);
        $question8->setQuestion("Consommez-vous des légumes ? (par 1/2 assiette)");
        $question8->setIsUnique(1);
        $question8->setBackgroundLeft('#C78C5C');
        $question8->setBackgroundRight('#FFE059');


        $manager->persist($question8);


        $choice8_1 = new Choice();
        $choice8_1->setConditional(1);
        $choice8_1->setName("Oui, 2x par jour et cela tous les jours");
        $choice8_1->setQuestionChoice($question8);
        $choice8_1->setComment('Commentaire test 1');
        $choice8_2 = new Choice();
        $choice8_2->setConditional(1);
        $choice8_2->setName("Oui, 1x par jour et cela tous les jours");
        $choice8_2->setQuestionChoice($question8);
        $choice8_2->setComment('Commentaire test 2');
        $choice8_3 = new Choice();
        $choice8_3->setConditional(1);
        $choice8_3->setName("Pas tous les jours, mais plusieurs fois par semaine");
        $choice8_3->setQuestionChoice($question8);
        $choice8_3->setComment('Commentaire test 3');
        $choice8_4 = new Choice();
        $choice8_4->setConditional(1);
        $choice8_4->setName("Une fois par semaine");
        $choice8_4->setQuestionChoice($question8);
        $choice8_4->setComment('Commentaire test 4');
        $choice8_5 = new Choice();
        $choice8_5->setConditional(1);
        $choice8_5->setName("Moins d'une fois par semaine mais quand même plusieurs fois par mois");
        $choice8_5->setQuestionChoice($question8);
        $choice8_5->setComment('Commentaire test 5');
        $choice8_6 = new Choice();
        $choice8_6->setConditional(0);
        $choice8_6->setName("Jamais");
        $choice8_6->setQuestionChoice($question8);
        $choice8_6->setComment('Commentaire test 6');

        $manager->persist($choice8_1);
        $manager->persist($choice8_2);
        $manager->persist($choice8_3);
        $manager->persist($choice8_4);
        $manager->persist($choice8_5);
        $manager->persist($choice8_6);

        $question9 = new Question_choice();
        $question9->setMaster(0);
        $question9->setNumber(9);
        $question9->setTheme($theme3);
        $question9->setQuestion("Consommez-vous des fruits ? (par unité pour les fruits
moyens, par tranche pour les gros fruits et par poignée
pour les petits fruits)");
        $question9->setIsUnique(1);
        $question9->setBackgroundLeft('#C78C5C');
        $question9->setBackgroundRight('#FFE059');


        $manager->persist($question9);


        $choice9_1 = new Choice();
        $choice9_1->setConditional(1);
        $choice9_1->setName("Oui, 3x par jour et cela tous les jours");
        $choice9_1->setQuestionChoice($question9);
        $choice9_1->setComment('Commentaire test 1');
        $choice9_2 = new Choice();
        $choice9_2->setConditional(1);
        $choice9_2->setName("Oui, 2x par jour et cela tous les jours");
        $choice9_2->setQuestionChoice($question9);
        $choice9_2->setComment('Commentaire test 2');
        $choice9_3 = new Choice();
        $choice9_3->setConditional(1);
        $choice9_3->setName("Oui, 1 par jour et cela tous les jours");
        $choice9_3->setQuestionChoice($question9);
        $choice9_3->setComment('Commentaire test 3');
        $choice9_4 = new Choice();
        $choice9_4->setConditional(1);
        $choice9_4->setName("Quelque fois par semaine");
        $choice9_4->setQuestionChoice($question9);
        $choice9_4->setComment('Commentaire test 4');
        $choice9_5 = new Choice();
        $choice9_5->setConditional(1);
        $choice9_5->setName("Rarement");
        $choice9_5->setQuestionChoice($question9);
        $choice9_5->setComment('Commentaire test 5');
        $choice9_6 = new Choice();
        $choice9_6->setConditional(0);
        $choice9_6->setName("Jamais");
        $choice9_6->setQuestionChoice($question9);
        $choice9_6->setComment('Commentaire test 6');


        $manager->persist($choice9_1);
        $manager->persist($choice9_2);
        $manager->persist($choice9_3);
        $manager->persist($choice9_4);
        $manager->persist($choice9_5);
        $manager->persist($choice9_6);

        $question10 = new Question_choice();
        $question10->setMaster(0);
        $question10->setNumber(10);
        $question10->setTheme($theme3);
        $question10->setQuestion("Quels types de fruits & légumes consommez-vous");
        $question10->setIsUnique(1);
        $question10->setBackgroundLeft('#99C9E3');
        $question10->setBackgroundRight('#E0B5F7');


        $manager->persist($question10);


        $choice10_1 = new Choice();
        $choice10_1->setConditional(1);
        $choice10_1->setName("Uniquement des produis frais, locaux et de saison");
        $choice10_1->setQuestionChoice($question10);
        $choice10_1->setComment('Commentaire test 1');
        $choice10_2 = new Choice();
        $choice10_2->setConditional(1);
        $choice10_2->setName("Beaucoup de produits frais et de saison avec quelques
conserves ou surgelés (de fruits/légumes non transformés)
pour ceux qui ne sont pas de saison");
        $choice10_2->setQuestionChoice($question10);
        $choice10_2->setComment('Commentaire test 2');
        $choice10_3 = new Choice();
        $choice10_3->setConditional(1);
        $choice10_3->setName("Beaucoup de conserves ou surgelés (de fruits/légumes non
transformés) pour ceux qui ne sont pas de saison et quelques
produits frais de saison");
        $choice10_3->setQuestionChoice($question10);
        $choice10_3->setComment('Commentaire test 3');
        $choice10_4 = new Choice();
        $choice10_4->setConditional(1);
        $choice10_4->setName("Beaucoup de produits frais mais pas forcément de saison et
quelques fruits et/ou légumes déjà préparés industriellement");
        $choice10_4->setQuestionChoice($question10);
        $choice10_4->setComment('Commentaire test 4');
        $choice10_5 = new Choice();
        $choice10_5->setConditional(1);
        $choice10_5->setName("Beaucoup de fruits et/ou légumes déjà préparés
industriellement et quelques produits frais mais pas forcément
de saison");
        $choice10_5->setQuestionChoice($question10);
        $choice10_5->setComment('Commentaire test 5');
        $choice10_6 = new Choice();
        $choice10_6->setConditional(0);
        $choice10_6->setName("Exclusivement des fruits et/ou légumes déjà préparés
industriellement");
        $choice10_6->setQuestionChoice($question10);
        $choice10_6->setComment('Commentaire test 6');

        $manager->persist($choice10_1);
        $manager->persist($choice10_2);
        $manager->persist($choice10_3);
        $manager->persist($choice10_4);
        $manager->persist($choice10_5);
        $manager->persist($choice10_6);

        $question11 = new Question_choice();
        $question11->setMaster(0);
        $question11->setNumber(11);
        $question11->setTheme($theme3);
        $question11->setQuestion("Comment consommez-vous vos fruits et/ou légumes ?");
        $question11->setIsUnique(1);
        $question11->setBackgroundLeft('#99C9E3');
        $question11->setBackgroundRight('#E0B5F7');


        $manager->persist($question11);


        $choice11_1 = new Choice();
        $choice11_1->setConditional(1);
        $choice11_1->setName("Toujours crus");
        $choice11_1->setQuestionChoice($question11);
        $choice11_1->setComment('Commentaire test 1');
        $choice11_2 = new Choice();
        $choice11_2->setConditional(1);
        $choice11_2->setName("Très souvent crus, très rarement cuits");
        $choice11_2->setQuestionChoice($question11);
        $choice11_2->setComment('Commentaire test 2');
        $choice11_3 = new Choice();
        $choice11_3->setConditional(1);
        $choice11_3->setName("Souvent crus et rarement cuits");
        $choice11_3->setQuestionChoice($question11);
        $choice11_3->setComment('Commentaire test 3');
        $choice11_4 = new Choice();
        $choice11_4->setConditional(1);
        $choice11_4->setName("Aussi souvent cuits que crus");
        $choice11_4->setQuestionChoice($question11);
        $choice11_4->setComment('Commentaire test 4');
        $choice11_5 = new Choice();
        $choice11_5->setConditional(1);
        $choice11_5->setName("Souvent cuits et rarement crus");
        $choice11_5->setQuestionChoice($question11);
        $choice11_5->setComment('Commentaire test 5');
        $choice11_6 = new Choice();
        $choice11_6->setConditional(0);
        $choice11_6->setName("Toujours cuits");
        $choice11_6->setQuestionChoice($question11);
        $choice11_6->setComment('Commentaire test 6');

        $manager->persist($choice11_1);
        $manager->persist($choice11_2);
        $manager->persist($choice11_3);
        $manager->persist($choice11_4);
        $manager->persist($choice11_5);
        $manager->persist($choice11_6);

        $question12 = new Question_choice();
        $question12->setMaster(0);
        $question12->setNumber(12);
        $question12->setTheme($theme3);
        $question12->setQuestion("Consommez-vous des aliments alternatifs aux produits
laitiers a base de fruit secs / graines / céréales (noix de
coco, amande, soja, avoine, etc.) ?");
        $question12->setIsUnique(1);
        $question12->setBackgroundLeft('#99C9E3');
        $question12->setBackgroundRight('#E0B5F7');


        $manager->persist($question12);


        $choice12_1 = new Choice();
        $choice12_1->setConditional(1);
        $choice12_1->setName("Oui plusieurs fois par jour");
        $choice12_1->setQuestionChoice($question12);
        $choice12_1->setComment('Commentaire test 1');
        $choice12_2 = new Choice();
        $choice12_2->setConditional(1);
        $choice12_2->setName("Oui une fois par jour");
        $choice12_2->setQuestionChoice($question12);
        $choice12_2->setComment('Commentaire test 2');
        $choice12_3 = new Choice();
        $choice12_3->setConditional(1);
        $choice12_3->setName("Plusieurs fois dans la semaine mais pas tous les jours");
        $choice12_3->setQuestionChoice($question12);
        $choice12_3->setComment('Commentaire test 3');
        $choice12_4 = new Choice();
        $choice12_4->setConditional(1);
        $choice12_4->setName("Plusieurs fois par mois mais pas toutes les semaines");
        $choice12_4->setQuestionChoice($question12);
        $choice12_4->setComment('Commentaire test 4');
        $choice12_5 = new Choice();
        $choice12_5->setConditional(1);
        $choice12_5->setName("Moins d'une fois par mois");
        $choice12_5->setQuestionChoice($question12);
        $choice12_5->setComment('Commentaire test 5');
        $choice12_6 = new Choice();
        $choice12_6->setConditional(0);
        $choice12_6->setName("Jamais");
        $choice12_6->setQuestionChoice($question12);
        $choice12_6->setComment('Commentaire test 6');

        $manager->persist($choice12_1);
        $manager->persist($choice12_2);
        $manager->persist($choice12_3);
        $manager->persist($choice12_4);
        $manager->persist($choice12_5);
        $manager->persist($choice12_6);

        $question13 = new Question_choice();
        $question13->setMaster(0);
        $question13->setNumber(13);
        $question13->setTheme($theme3);
        $question13->setQuestion("Consommez vous des «superaliments» comme la baie de
goji, la spiruline, l’açai, l’herbe de blé, la maca, etc.?");
        $question13->setIsUnique(1);
        $question13->setBackgroundLeft('#B9B664');
        $question13->setBackgroundRight('#C78C5C');

        $manager->persist($question13);

        $choice13_1 = new Choice();
        $choice13_1->setConditional(1);
        $choice13_1->setName("J'en consomme tout le temps (plusieurs fois par jour)");
        $choice13_1->setQuestionChoice($question13);
        $choice13_1->setComment('Commentaire test 1');
        $choice13_2 = new Choice();
        $choice13_2->setConditional(1);
        $choice13_2->setName("J'en consomme très souvent (une fois par jour)");
        $choice13_2->setQuestionChoice($question13);
        $choice13_2->setComment('Commentaire test 2');
        $choice13_3 = new Choice();
        $choice13_3->setConditional(1);
        $choice13_3->setName("J'en consomme souvent (plusieurs fois dans la semaine masi pas tous les jours)");
        $choice13_3->setQuestionChoice($question13);
        $choice13_3->setComment('Commentaire test 3');
        $choice13_4 = new Choice();
        $choice13_4->setConditional(1);
        $choice13_4->setName("J'en consomme de temps en temps (plusieurs fois par mois pas toutes les semaines)");
        $choice13_4->setQuestionChoice($question13);
        $choice13_4->setComment('Commentaire test 4');
        $choice13_5 = new Choice();
        $choice13_5->setConditional(1);
        $choice13_5->setName("Je n'en consomme que très rarement (moins d'une fois par mois)");
        $choice13_5->setQuestionChoice($question13);
        $choice13_5->setComment('Commentaire test 5');
        $choice13_6 = new Choice();
        $choice13_6->setConditional(0);
        $choice13_6->setName("Je n'en consomme jamais");
        $choice13_6->setQuestionChoice($question13);
        $choice13_6->setComment('Commentaire test 6');
        
        $manager->persist($choice13_1);
        $manager->persist($choice13_2);
        $manager->persist($choice13_3);
        $manager->persist($choice13_4);
        $manager->persist($choice13_5);
        $manager->persist($choice13_6);


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