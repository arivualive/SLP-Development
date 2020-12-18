<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class OtherRevenueType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('amount', IntegerType::class)
        ->add('currency', EntityType::class, [
            'class' => 'AppBundle:Currency',
            'choice_label' => 'name',
        ])
        ->add('frequency', EntityType::class, [
            'class' => 'AppBundle:Frequency',
            'choice_label' => 'name',
        ])
        ->add('date', HiddenType::class, [
            'disabled' => true,
            /* 'data' => $date, */
        ])
        ->add('budgetId', IntegerType::class);
        // ->add('save', SubmitType::class, ['label' => 'Enregistrer']);
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => OtherRevenue::class]);
    }

    public function __toString(){
        return $this->amount;
    }
}