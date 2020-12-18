<?php

namespace AppBundle\Form;

use AppBundle\Entity\Budget;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
// use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\NotBlank;
// use Symfony\Bridge\Doctrine\Form\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

use AppBundle\Entity\MainRevenue;
use AppBundle\Entity\OtherRevenue;

class BudgetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('userSlp', HiddenType::class);
        $builder->add('mainRevenues', EntityType::class, [
            'label' => 'amount',
            'class' => MainRevenue::class,
            'choice_label' => 'amount'
        ]);
        $builder->add('otherRevenues', EntityType::class, [
            'label' => 'amount',
            'class' => OtherRevenue::class,
            'choice_label' => 'amount'
        ]);
        // $builder->add('save', SubmitType::class, ['label' => 'Modifier mes revenus']);
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => Budget::class]);
    }

}