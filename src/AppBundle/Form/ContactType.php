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


class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options){
        $builder
            ->add("name", TextType::class, [
                'label' => 'Votre nom :',
                //'required' => false,
                'constraints' => [
                    new NotBlank([ "message" => "Vous devez obligatoirement renseigner votre nom"])
                ]
            ])
            ->add("email", EmailType::class, [
                'label' => "Votre email :",
                //'required' => false,
                 'constraints' => [
                    new NotBlank([ "message" => "Vous devez obligatoirement renseigner votre email"]),
                    new Email([ "message" => "Vous devez obligatoirement renseigner une adresse mail valide"])
                ]
            ])
            ->add("message", TextareaType::class, [
                'label' => "Votre message :",
                //'required' => false,
                 'constraints' => [
                    new NotBlank([ "message" => "Vous devez obligatoirement renseigner votre message"])
                ]
            ])
            ->add("send", SubmitType::class, [
                'label' => "Envoyer",
                'attr' => ['class' => 'btn btn-primary']
            ])
        ;
    }

}
