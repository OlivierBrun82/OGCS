<?php

namespace App\Form;

use App\Entity\Blames;
use App\Entity\Matches;
use App\Entity\Players;
use App\Enum\BlameCardType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BlamesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('start_date', DateType::class, [
                'label' => 'Date de début (attribution)',
                'widget' => 'single_text',
            ])
            ->add('card_type', EnumType::class, [
                'class' => BlameCardType::class,
                'label' => 'Type de carton',
                'choice_label' => fn (BlameCardType $c) => $c->label(),
            ])
            ->add('duration_minutes', IntegerType::class, [
                'label' => 'Durée (minutes) — carton blanc',
                'required' => false,
                'attr' => ['min' => 1],
                'help' => 'Obligatoire pour un carton blanc.',
            ])
            ->add('suspension_matches', IntegerType::class, [
                'label' => 'Matchs de suspension — carton jaune ou rouge',
                'required' => false,
                'attr' => ['min' => 0],
            ])
            ->add('players', EntityType::class, [
                'class' => Players::class,
                'label' => 'Joueur',
                'choice_label' => fn (Players $p) => trim($p->getFirstName().' '.$p->getLastName()),
                'placeholder' => '—',
            ])
            ->add('related_match', EntityType::class, [
                'class' => Matches::class,
                'label' => 'Match (pour liaison 2 cartons jaunes, etc.)',
                'required' => false,
                'placeholder' => '—',
                'choice_label' => function (Matches $m): string {
                    $d = $m->getDate()?->format('d/m/Y') ?? '?';
                    $h = $m->getHomeTeam()?->getTeamName() ?? '?';
                    $a = $m->getAwayTeam()?->getTeamName() ?? '?';

                    return sprintf('%s — %s vs %s · %s', $d, $h, $a, $m->getLocation());
                },
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Blames::class,
        ]);
    }
}
