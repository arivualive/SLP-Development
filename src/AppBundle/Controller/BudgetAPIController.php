<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

use AppBundle\Entity\Additive;
use AppBundle\Entity\Brand;
use AppBundle\Entity\Bulk;
use AppBundle\Entity\Composition;
use AppBundle\Entity\Cost;
use AppBundle\Entity\Currency;
use AppBundle\Entity\Detail;
use AppBundle\Entity\Frequency;
use AppBundle\Entity\HiddenSugar;
use AppBundle\Entity\Origin;
use AppBundle\Entity\Packing;
use AppBundle\Entity\PalmOil;
use AppBundle\Entity\Product;
use AppBundle\Entity\QualityLabel;
use AppBundle\Entity\Quantity;
use AppBundle\Entity\Spending;
use AppBundle\Entity\Subcategory;
use AppBundle\Entity\UserSlp;

class BudgetAPIController extends Controller
{
    /**
     * @Route("/test", name="test")
    */
    public function index()
    {
        return $this->render('site/site_index.html.twig');
    }

    /**
    * @Route("/ajax_spendings_update", name="ajax_spendings_update")
    * @Method({"GET", "POST"})
    */
    public function updateSpendings(Request $request)
    {
        
        
        if ($request->isXMLHttpRequest()) {
            
            $entityManager = $this->getDoctrine()->getManager();

            $userSlpRepo = $this->getDoctrine()->getRepository(UserSlp::class);

            $UserConecte = $userSlpRepo->findOneByGaeaUserId($this->getUser()->getId());


            $myJSON = $request->request->get('myJSON');
            //echo $myJSON;
        
            $data = json_decode($myJSON);

        
            foreach ($data as $iteration) {


                $periodName = $iteration->period;
                //echo "periodName";

                //$testQuantity = $iteration->quantity;
                $formQuantity = $iteration->quantity;
                //$formQuantity = 1;
                
                $formCurrency = $iteration->currency;

                $formBrand = $iteration->brand;

                $formSubCategory = $iteration->subcategory;

                $formPrice = $iteration->amount;

                // récupération du nom du produit entré dans le formulaire
                $name = $iteration->name;

                // récupération du prix entré dans le formulaire
                $price = $iteration->amount;


                //$spendingId = null; /* commenter ici */
                // Récupération de la dépense
                $spendingId = $iteration->id; /*décommenter ici */

                //if($formQuantity != 0 and $formBrand != 0 and $formPrice != 0 and $name != null) {
                    $em = $this->getDoctrine()->getManager();


                    $period = $em->getRepository(Frequency::class)->findOneBy(['name' => $periodName]);
//                    dump($period);exit();


                    $currency = $em->getRepository(Currency::class)->findOneBy(['isoCode' => $formCurrency]);

                    //$quantity = $em->getRepository(Quantity::class)->findOneBy(['number' => $formQuantity]);
                    //$quantity = $em->getRepository(Quantity::class)->findOneBy(['number' => $testQuantity]);

                    $subcategory = $em->getRepository(Subcategory::class)->findOneBy(['name' => $formSubCategory]);

                

                    // cost
                    $cost = $em->getRepository(Cost::class)->findOneBy(['price' => $formPrice]);


                    if ($cost) {
                        //echo 'cost found';

                    } else {
                        //echo 'no cost found';
                        // Création de l'entité Cost
                        $cost = new Cost();
                        $cost->setPrice($formPrice);
                        // On « persiste » l'entité cost
                        $entityManager->persist($cost);
                    }

                    //echo $name;

                    $produit = $em->getRepository(Product::class)->findOneBy(['name' => $name]);

                    //echo serialize($produit);

                    if ($produit) {
                        //echo 'product found';
                    
                    } else {
                        //echo 'no product found';
                        // Création de l'entité Produit
                        $produit = new Product();
                        $produit->setName($name);
                        //echo serialize($produit);
                    }

                    // Ajout de la sous catégorie du produit
                    $produit->setSubcategory($subcategory);

                    $brand = $em->getRepository(Brand::class)->findOneBy(['name' => $formBrand]);

                    if ($brand) {
                        echo 'brand found';

                    } else {
                        echo 'no brand found';
                        // Création de l'entité Brand
                        $brand = new Brand();
                        $brand->setName($formBrand);
                    }

                    $entityManager->persist($brand);

                    // lien entre le produit et la marque
                    $produit->setBrand($brand);

                    $depense = $entityManager->getRepository(Spending::class)->findOneBy(['id' => $spendingId]); /*décommenter ici */

                    if($depense) {
                        echo 'spending found';
                    } else {
                        echo 'no spending found';
                        $depense = new Spending();
                    }

                    echo serialize($depense);

                    $depense->setUserSlp($UserConecte);

                    $depense->setDate(new \DateTime());

                    // Ajout de la marque du produit
                    $depense->setBrand($brand);

                    // On lie le coût à la dépense
                    $depense->setCost($cost);

                    // On lie la devise à la dépense
                    $depense->setCurrency($currency);

                    // On « persiste » l'entité produit
                    $entityManager->persist($produit);

                    // On lie le coût au produit
                    $depense->setProduct($produit);

                    // On lie la dépense à la quantité

                    // quantity
                    $quantity = $em->getRepository(Quantity::class)->findOneBy(['number' => $formQuantity]);

                    if ($quantity) {
                        //echo 'quantity found';

                    } else {
                        //echo 'no quantity found';
                        // Création de l'entité Quantity

                        // à lier à l'unité
                        $quantity = new Quantity();
                        $quantity->setNumber($formQuantity);
                        $entityManager->persist($quantity);
                    }
                    $depense->setQuantity($quantity);

                    // On lie la période au produit
                    $depense->setFrequency($period);

                    // on lie l'utilisateur au produit
                    //$depense->setUserSlp($userSlp);

                    // Pour cette relation pas de cascade lorsqu'on persiste Cost, car la relation est définie dans l'entité Depense et non Cost. On doit donc tout persister à la main ici.
                    $entityManager->persist($depense); /* a décommenter */
                //}
            }
            $entityManager->flush(); /* a décommenter */
        
            //return new JsonResponse(array('data' => $myJSON));
            //$status = "done";
            //return $status;
            exit;
        }
        echo 'this is not ajax';
        
        //return new Response('This is not ajax!', 400);
    }

    
    /**
    * @Route("/ajax_themes_spendings_record_update", name="ajax_themes_spendings_record_update")
    * @Method({"GET", "POST"})
    */
    public function recordUpdateThemesSpendings(Request $request)
    {
        if ($request->isXMLHttpRequest()) {
            
            $entityManager = $this->getDoctrine()->getManager();

            $userSlpRepo = $this->getDoctrine()->getRepository(UserSlp::class);

            $UserConecte = $userSlpRepo->findOneByGaeaUserId($this->getUser()->getId());


            $myJSON = $request->request->get('myJSON');
            //echo $myJSON;
        
            $data = json_decode($myJSON);
        
            foreach ($data as $iteration) {
                //echo "periodName";

                //$testQuantity = $iteration->quantity;
                $formQuantity = $iteration->quantity;
                //$formQuantity = 1;
                
                $formCurrency = $iteration->currency;

                $formBrand = $iteration->brand;

                $formSubCategory = $iteration->subcategory;

                $formPrice = $iteration->amount;

                // récupération du nom du produit entré dans le formulaire
                $name = $iteration->name;

                // récupération du prix entré dans le formulaire
                $price = $iteration->amount;

                //$spendingId = null; /* commenter ici */
                // Récupération de la dépense
                $spendingId = $iteration->id; /*décommenter ici */

                $em = $this->getDoctrine()->getManager();


                $currency = $em->getRepository(Currency::class)->findOneBy(['isoCode' => $formCurrency]);

                //$quantity = $em->getRepository(Quantity::class)->findOneBy(['number' => $formQuantity]);
                //$quantity = $em->getRepository(Quantity::class)->findOneBy(['number' => $testQuantity]);

                $subcategory = $em->getRepository(Subcategory::class)->findOneBy(['name' => $formSubCategory]);


                // cost
                $cost = $em->getRepository(Cost::class)->findOneBy(['price' => $formPrice]);


                if ($cost) {
                    //echo 'cost found';

                } else {
                    //echo 'no cost found';
                    // Création de l'entité Cost
                    $cost = new Cost();
                    $cost->setPrice($formPrice);
                    // On « persiste » l'entité cost
                    $entityManager->persist($cost);
                }

                //echo $name;

                $produit = $em->getRepository(Product::class)->findOneBy(['name' => $name]);

                //echo serialize($produit);

                if ($produit) {
                    //echo 'product found';
                
                } else {
                    //echo 'no product found';
                    // Création de l'entité Produit
                    $produit = new Product();
                    $produit->setName($name);
                    //echo serialize($produit);
                }

                // Ajout de la sous catégorie du produit
                $produit->setSubcategory($subcategory);

                $brand = $em->getRepository(Brand::class)->findOneBy(['name' => $formBrand]);

                if ($brand) {
                    echo 'brand found';

                } else {
                    echo 'no brand found';
                    // Création de l'entité Brand
                    $brand = new Brand();
                    $brand->setName($formBrand);
                }

                $entityManager->persist($brand);

                // lien entre le produit et la marque
                $produit->setBrand($brand);

                $depense = $entityManager->getRepository(Spending::class)->findOneBy(['id' => $spendingId]); /*décommenter ici */

                if($depense) {
                    echo 'spending found';
                } else {
                    echo 'no spending found';
                    $depense = new Spending();
                }

                echo serialize($depense);

                $depense->setUserSlp($UserConecte);

                $depense->setDate(new \DateTime());

                // Ajout de la marque du produit
                $depense->setBrand($brand);

                // On lie le coût à la dépense
                $depense->setCost($cost);

                // On lie la devise à la dépense
                $depense->setCurrency($currency);

                // On « persiste » l'entité produit
                $entityManager->persist($produit);

                // On lie le coût au produit
                $depense->setProduct($produit);

                // On lie la dépense à la quantité
            

                // quantity
                $quantity = $em->getRepository(Quantity::class)->findOneBy(['number' => $formQuantity]);

                if ($quantity) {
                    //echo 'quantity found';

                } else {
                    //echo 'no quantity found';
                    // Création de l'entité Quantity

                    // à lier à l'unité
                    $quantity = new Quantity();
                    $quantity->setNumber($formQuantity);
                    $entityManager->persist($quantity);
                }
                $depense->setQuantity($quantity);

                // on lie l'utilisateur au produit
                //$depense->setUserSlp($userSlp);

                // Pour cette relation pas de cascade lorsqu'on persiste Cost, car la relation est définie dans l'entité Depense et non Cost. On doit donc tout persister à la main ici.
                $entityManager->persist($depense); /* a décommenter */
            
            }
            $entityManager->flush(); /* a décommenter */
        
            //return new JsonResponse(array('data' => $myJSON));

            exit;
        }
        echo 'this is not ajax';
        
        //return new Response('This is not ajax!', 400);
    }
    
    /**                              
    * @Route("/ajax_spendings", name="ajax_spendings")
    */
    public function ajaxAction(Request $request)
    {
        if ($request->isXMLHttpRequest()) {
        
            //$userSlp = $this->searchUserSlpFromGaeaUser($user);

            /*$gaeaUserId = $user->getId();
            $userSlp = $this->getDoctrine()->getRepository('AppBundle:UserSlp')->findOneBy(array('gaeaUserId' => $gaeaUserId));
        
            $stringUserSlp = serialize($userSlp);*/
            // echo $stringUserSlp;

            //searchUserSlpFromGaeaUser();

            $myJSON = $request->request->get('myJSON');
            echo $myJSON;
        
            $data = json_decode($myJSON);
        
            foreach ($data as $iteration) {

                // recherche de la période

                /*$periodRepo = $em->getRepository(Period::class);
            
                $period = $periodRepo->findOneBy(['name' => $periodName]); */
            
                //echo "\n"

                $periodName = $iteration->period;

                // recherche de la devise

                $formCurrency = $iteration->currency;

                // recherche de la quantité

                $formQuantity = $iteration->quantity;

                // recherche de la marque

                $formBrand = $iteration->brand;

                // recherche de la sous categorie du produit

                $formSubCategory = $iteration->subcategory;

                $em = $this->getDoctrine()->getManager();

                $period = $em->getRepository(Frequency::class)->findOneBy(['name' => $periodName]);
                // $period = $em->getRepository(Period::class)->findOneBy(['name' => $periodName]);

                $currency = $em->getRepository(Currency::class)->findOneBy(['isoCode' => $formCurrency]);

                $quantity = $em->getRepository(Quantity::class)->findOneBy(['quantity' => $formQuantity]);

                $subcategory = $em->getRepository(Subcategory::class)->findOneBy(['name' => $formSubCategory]);
            
                $stringPeriod = $period->getName();
                //echo $stringPeriod;

                $formPrice = $iteration->amount;

                // cost
                $cost = $em->getRepository(Cost::class)->findOneBy(['price' => $formPrice]);

                if ($cost) {
                    //echo 'cost found';

                } else {
                    //echo 'no cost found';
                    // Création de l'entité Cost
                    $cost = new Cost();
                    $cost->setPrice($formPrice);
                }

                // récupération du nom du produit entré dans le formulaire
                $name = $iteration->name;

                //echo $name;

                $produit = $em->getRepository(Produit::class)->findOneBy(['name' => $name]);

                //echo serialize($produit);

                if ($produit) {
                    //echo 'product found';
                
                } else {
                    //echo 'no product found';
                    // Création de l'entité Produit
                    $produit = new Produit();
                    $produit->setName($name);
                    //echo serialize($produit);
                }

                // Ajout de la sous catégorie du produit
                $produit->setSubcategory($subcategory);

                $brand = $em->getRepository(Brand::class)->findOneBy(['name' => $formBrand]);

                if ($brand) {
                    echo 'brand found';

                } else {
                    echo 'no brand found';
                // Création de l'entité Brand
                    $brand = new Brand();
                    $brand->setName($formBrand);
                }

                // récupération du prix entré dans le formulaire
                $price = $iteration->amount;

                // Création d'une première dépense
                $depense = new Depenses();
                $depense->setDate(new \DateTime());

                // Ajout de la marque du produit
                $depense->setBrand($brand);

                // On lie le coût à la dépense
                $depense->setCost($cost);

                // On lie la devise à la dépense
                $depense->setCurrency($currency);

                // On « persiste » l'entité cost
                $entityManager->persist($cost);

                // On « persiste » l'entité produit
                $entityManager->persist($produit);

                // On lie le coût au produit
                $depense->setProduct($produit);

                // On lie la dépense à la quantité
            

                // quantity
                $quantity = $em->getRepository(Quantity::class)->findOneBy(['quantity' => $formQuantity]);

                if ($quantity) {
                    //echo 'quantity found';

                } else {
                    //echo 'no quantity found';
                    // Création de l'entité Quantity
                    $quantity = new Quantity();
                    $quantity->setQuantity($formQuantity);
                }
                $depense->setQuantity($quantity);

                // On lie la période au produit
                $depense->setPeriod($period);

                // on lie l'utilisateur au produit
                $depense->setUserSlp($userSlp);

                // Pour cette relation pas de cascade lorsqu'on persiste Cost, car la relation est définie dans l'entité Depense et non Cost. On doit donc tout persister à la main ici.
                $entityManager->persist($depense);
            
            }
            $entityManager->flush();
        
            return new JsonResponse(array('data' => $myJSON));
        }
        echo 'this is not ajax';
        return new Response('This is not ajax!', 400);
    }

    public function searchUserSlpFromGaeaUser($user) {
        $gaeaUserId = $user->getId();
        $userSlp = $this->getDoctrine()->getRepository('AppBundle:UserSlp')->findOneBy(array('gaeaUserId' => $gaeaUserId));

        return $userSlp;
    }

    /**
    * @Route("/ajax_product_update", name="ajax_product_update")
    * @Method({"POST"})
    */
    public function updateProducts(Request $request) {

        if ($request->isXMLHttpRequest()) {

            $myJSON = $request->request->get('data');
            $results = $myJSON;

            $size = count($results);

            $productId = $results[0]['productId'];
            $product = $this->getDoctrine()->getRepository(Product::class)->findOneById($productId);

            // vider les collections :

            $product->removeAllCompositions();
            $product->removeAllPalmOils();
            $product->removeAllOrigins();
            $product->removeAllHiddenSugars();
            $product->removeAllAdditives();
            $product->removeAllQualityLabels();
            $product->removeAllPackings();

            $entityManager = $this->getDoctrine()->getManager();

            for($i = 0; $i < $size; ++$i) {
            
                // (le produit est le même à chaque result donc pas besoin de le chercher ici)
                $table = $results[$i]['table'];
                $propertyId = $results[$i]['propertyId'];

                if ($table == 'detail'){
                    $detail = $this->getDoctrine()->getRepository(Detail::class)->findOneById($propertyId);
                    $product->setDetail($detail);
                }

                if ($table == 'bulk'){
                    $bulk = $this->getDoctrine()->getRepository(Bulk::class)->findOneById($propertyId); 
                    $product->setBulk($bulk);
                }

                if ($table == 'packing'){
                    $packing = $this->getDoctrine()->getRepository(Packing::class)->findOneById($propertyId); 
                    $product->addPacking($packing);
                }

                if ($table == 'quality_label'){
                    $qualityLabel = $this->getDoctrine()->getRepository(QualityLabel::class)->findOneById($propertyId);
                    $product->addQualityLabel($qualityLabel);

                }

                if ($table == 'origin'){
                    $origin = $this->getDoctrine()->getRepository(Origin::class)->findOneById($propertyId);
                    $product->addOrigin ($origin);
                }

                if ($table == 'composition'){
                    $composition = $this->getDoctrine()->getRepository(Composition::class)->findOneById($propertyId);
                    $product->addComposition($composition);
                }

                if ($table == 'additive'){
                    $additive = $this->getDoctrine()->getRepository(Additive::class)->findOneById($propertyId);;
                    $product->addAdditive($additive);
                }

                if ($table == 'palm_oil'){
                    $palmOil = $this->getDoctrine()->getRepository(PalmOil::class)->findOneById($propertyId);
                    $product->addPalmOil($palmOil);
                }

                if ($table == 'hidden_sugar'){
                    $hiddenSugar = $this->getDoctrine()->getRepository(HiddenSugar::class)->findOneById($propertyId);
                    $product->addHiddenSugar($hiddenSugar);

                }

                $entityManager->persist($product);
                $entityManager->flush();
            }

            return new JsonResponse(true);
            // return new JsonResponse(array('results' => $myJSON);
        }
        
        return new JsonResponse(false);
    }

    /**
    * @Route("/ajax_find_product", name="ajax_find_product")
    * @Method({"GET", "POST"})
    */
    public function findProduct(Request $request) {

        if ($request->isXMLHttpRequest()) {

           $selectedProduct = $request->request->all();

            if($selectedProduct['productId'] != 'no value'){ // si un produit est sélectionné dans le menu déroulant

                $product = $this->getDoctrine()->getRepository(Product::class)->findOneById($selectedProduct['productId']);

                $productData = [];

                $productData += [
                    'bulk' => $product->getBulk(),
                    'detail' => $product->getDetail(),
                    'compositions' => $product->getcompositions(),
                    'palmOils' => $product->getPalmOils(),
                    'origins' => $product->getOrigins(),
                    'hiddenSugars' => $product->getHiddenSugars(),
                    'additives' => $product->getAdditives(),
                    'qualityLabels' => $product->getQualityLabels(),
                    'packings' => $product->getPackings()
                ];

                $encoders = [new JsonEncoder()];
                $normalizers = [new ObjectNormalizer()];
                $serializer = new Serializer($normalizers, $encoders);

                $jsonContent = $serializer->serialize($productData, 'json');

                return new JsonResponse($jsonContent);

                } else {
                $product = null;
            }

            // return new JsonResponse($product);
        }

       return new JsonResponse(false);
    }
}