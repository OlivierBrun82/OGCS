<?php

namespace App\Form;

use App\Entity\Abscences;
use App\Entity\Players;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AbscencesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('motive', TextType::class, [
                'label' => 'Motif',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('absence_start', DateType::class, [
                'label' => 'Du',
                'widget' => 'single_text',
                'input' => 'datetime_immutable',
            ])
            ->add('absence_end', DateType::class, [
                'label' => 'Au',
                'widget' => 'single_text',
                'input' => 'datetime_immutable',
            ]);

        if (!$options['preset_player']) {
            $builder->add('players', EntityType::class, [
                'class' => Players::class,
                'label' => 'Joueur',
                'choice_label' => static fn (Players $p): string => $p->getFirstName().' '.$p->getLastName(),
                'placeholder' => '—',
                'attr' => ['class' => 'form-select'],
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Abscences::class,
            'preset_player' => false,
        ]);
        $resolver->setAllowedTypes('preset_player', ['bool']);
    }
}
