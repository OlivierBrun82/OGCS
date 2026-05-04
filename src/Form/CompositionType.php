<?php

namespace App\Form;

use App\Entity\Composition;
use App\Entity\Matches;
use App\Entity\Players;
use App\Enum\MatchPlayerRole;
use App\Repository\PlayersRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompositionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('player', EntityType::class, [
                'class' => Players::class,
                'label' => 'Joueur',
                'placeholder' => '—',
                'choice_label' => fn (Players $p) => sprintf(
                    '%s %s (%s)',
                    $p->getFirstName() ?? '',
                    $p->getLastName() ?? '',
                    $p->getTeams()?->getTeamName() ?? '?',
                ),
                'query_builder' => static function (PlayersRepository $repo) use ($options) {
                    $qb = $repo->createQueryBuilder('p')
                        ->orderBy('p.last_name', 'ASC')
                        ->addOrderBy('p.first_name', 'ASC');

                    $match = $options['match'] ?? null;
                    if ($match instanceof Matches && $match->getHomeTeam() && $match->getAwayTeam()) {
                        $qb->andWhere('p.Teams IN (:home, :away)')
                            ->setParameter('home', $match->getHomeTeam())
                            ->setParameter('away', $match->getAwayTeam());
                    }

                    return $qb;
                },
            ])
            ->add('role', EnumType::class, [
                'class' => MatchPlayerRole::class,
                'label' => 'Rôle sur ce match',
                'choice_label' => fn (MatchPlayerRole $r) => $r->label(),
                'placeholder' => '—',
                'attr' => ['class' => 'form-select'],
            ]);

        $builder->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event): void {
            $composition = $event->getData();
            $match = $event->getForm()->getRoot()->getData();
            if ($match instanceof Matches && $composition instanceof Composition && $composition->getMatch() === null) {
                $composition->setMatch($match);
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Composition::class,
            'match' => null,
            'label' => false,
            'row_attr' => ['class' => 'composition-entry border rounded p-3 mb-2'],
        ]);
        $resolver->setAllowedTypes('match', [Matches::class, 'null']);
    }
}
