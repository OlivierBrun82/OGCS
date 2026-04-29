<?php

namespace App\Form;

use App\Entity\Players;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class PlayerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('first_name', TextType::class, [
                'label' => 'Le prénom du joueur : ',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Entrer le nom du joueur',
                    'maxlength' => 255
                ]
            ])
            ->add('last_name', TextType::class, [
                'label' => 'Le nom du joueur : ',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Entrer le nom du joueur',
                    'maxlength' => 255
                ]
            ])
            ->add('email', TextType::class, [
                'label' => 'L\'email du joueur : ',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Entrer l\'email du joueur',
                    'maxlength' => 255
                ]
            ])
            ->add('birthdate', DateType::class, [
                'label' => 'La date de naissance du joueur : ',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Entrer la date de naissance du joueur',
                ]
            ])
            ->add('licence', DateType::class, [
                'label' => 'Date de création de la licence : ',
                'required' => true
            ])
            ->add('medical_certificate')
            ->add('categorie')
            ->add('statut')
            ->add('number', NumberType::class, [
                'label' => 'Le numéro du joueur : ',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Entrer le numéro du joueur',
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Envoyer',
                'attr' => [
                    'class' => 'btn btn-primary'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Players::class,
        ]);
    }
}
