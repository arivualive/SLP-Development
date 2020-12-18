<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;
use AppBundle\Entity\UserSlp;
use AppBundle\Entity\Categories;
use AppBundle\Entity\SousCategories;
use AppBundle\Entity\Cost;


class PersonSpendingType extends AbstractType
{
    /*public function addAction(Request $request)
    {
        // On crée un objet Advert
        $advert = new Advert();

        // On crée le FormBuilder grâce au service form factory
        $formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $advert);

        // On ajoute les champs de l'entité que l'on veut à notre formulaire
        $formBuilder
        ->add("price", TextType::class)
        ->add("name", TextType::class)
      
        ;
        // Pour l'instant, pas de candidatures, catégories, etc., on les gérera plus tard

        // À partir du formBuilder, on génère le formulaire
        $form = $formBuilder->getForm();

        // On passe la méthode createView() du formulaire à la vue
        // afin qu'elle puisse afficher le formulaire toute seule
        return $this->render('OCPlatformBundle:Advert:add.html.twig', array(
            'form' => $form->createView(),
        ));
    }*/
    
    
    public function buildForm(FormBuilderInterface $builder, array $options){
        $builder
            //->add("depenseBoisson")
            ->add("price", TextType::class)
            ->add("name", TextType::class)
            //->add("cafePrix")
            //->add("cafeMarque")
            //->add("thePrix")
            //->add("theMarque")
            //->add("boissonVegetalePrix")
            //->add("boissonVegetaleMarque")
            //->add("sodasPrix")
            //->add("sodasMarque")
            //->add("depenseFruitsLegumes")
            //->add("prixFruitsFrais")
            //->add("MarqueFruitsFrais")
            //->add("prixFruitsSecs")
            //->add("MarqueFruitsSecs")
            //->add("prixFruitsTransformes")
            //->add("marqueFruitsTransformes")
            //->add("prixLegumes")
            //->add("marqueLegumes")
            //->add("prixOleagineux")
            //->add("marqueOleagineux")
            //->add("depenseProduitsLaitiers")
            //->add("prixLait")
            //->add("marqueLait")
            //->add("prixFromages")
            //->add("marqueFromages")
            //->add("prixProduitsLaitiersFraisNature")
            //->add("marqueProduitsLaitiersFraisNature")
            //->add("prixProduitsLaitiersFraisSucres")
            //->add("marqueProduitsLaitiersFraisSucres")
            //->add("depenseFeculents")
            //->add("prixFeculentsComplets")
            //->add("marqueFeculentsComplets")
            //->add("prixFeculentsRaffines")
            //->add("marqueFeculentsRaffines")
            //->add("prixLegumineuses")
            //->add("marqueLegumineuses")
            //->add("depensesMatieresGrasses")
            //->add("prixMatieresGrasses")
            //->add("marqueMatieresGrasses")
            //->add("marqueHuilesVegetales")
            //->add("prixHuilesVegetales")
            //->add("prixSauces")
            //->add("marqueSauces")
            //->add("depenseProduitsSucres")
            //->add("coutGateauxBiscuitsConfiseriesIndustrielles")
            //->add("marqueGateauxBiscuitsConfiseriesIndustrielles")
            //->add("coutChocolatNoir")
            //->add("marqueChocolatNoir")
            //->add("coutSucreBlanc")
            //->add("marqueSucreBlanc")
            //->add("coutAternativeSucre")
            //->add("marqueAternativeSucre")
            //->add("coutEdulcorants")
            //->add("marqueEdulcorants")
            //->add("depenseVPO")
            //->add("prixViandes")
            //->add("marqueViandes")
            //->add("marquprixVolailleseViandes")
            //->add("marqueVolailles")
            //->add("prixSucreBlanc")
            //->add("marqueSucreBlanc")
            //->add("prixCharcuterie")
            //->add("marqueCharcuterie")
            //->add("prixPoissonGras")
            //->add("marquePoissonGras")
            //->add("prixPoissonMaigre")
            //->add("marquePoissonMaigre")
            //->add("prixMollusquesCrustaces")
            //->add("marqueMollusquesCrustaces")
            //->add("prixOeuf")
            //->add("marqueOeuf")
            //->add("depenseAlcool")
            //->add("depenseTabac")
        ;
    }

}