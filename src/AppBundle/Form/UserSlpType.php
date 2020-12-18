<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use AppBundle\Entity\HomeSituation;
use AppBundle\Entity\SocialSituation;
use Symfony\Component\Form\Extension\Core\Type\RadioType;

class UserSlpType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('job')
        ->add('liveAlone', RadioType::class)
        ->add('homePeopleNumber', IntegerType::class)
        ->add('child', RadioType::class)
        ->add('childNumber', IntegerType::class)
        ->add('zipcode')
        ->add('socialSituation', EntityType::class, array('class' => SocialSituation::class, 'choice_label' => 'situation',))
        ->add('homeSituation', EntityType::class, array('class' => HomeSituation::class, 'choice_label' => 'situation',));
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\UserSlp'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_userslp';
    }


}
