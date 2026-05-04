<?php

namespace App\Form;

use App\Entity\Matches;
use App\Entity\Teams;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MatchesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var Matches|null $match */
        $match = $options['data'] ?? null;

        $builder
            ->add('date', DateType::class, [
                'label' => 'Date du match',
                'widget' => 'single_text',
            ])
            ->add('type', TextType::class, [
                'label' => 'Type (ex. championnat, amical)',
            ])
            ->add('location', TextType::class, [
                'label' => 'Lieu',
            ])
            ->add('homeTeam', EntityType::class, [
                'class' => Teams::class,
                'label' => 'Équipe à domicile',
                'choice_label' => 'team_name',
                'placeholder' => '—',
            ])
            ->add('awayTeam', EntityType::class, [
                'class' => Teams::class,
                'label' => 'Équipe extérieure',
                'choice_label' => 'team_name',
                'placeholder' => '—',
            ])
            ->add('compositions', CollectionType::class, [
                'label' => 'Composition (feuille de match)',
                'entry_type' => CompositionType::class,
                'entry_options' => [
                    'match' => $match,
                    'label' => false,
                ],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'prototype' => true,
                'required' => false,
                'attr' => [
                    'class' => 'match-compositions-collection',
                ],
                'help' => 'Ajoutez une ligne par joueur présent, avec son rôle pour cette rencontre. Enregistrez d’abord les deux équipes pour restreindre la liste des joueurs.',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Matches::class,
        ]);
    }
}
