<?php

namespace App\Form;

use App\Entity\Inventory;
use App\Entity\Teams;
use App\Repository\TeamsRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InventoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('item_type', TextType::class, [
                'label' => 'Type d’article',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('quantity', IntegerType::class, [
                'label' => 'Quantité',
                'attr' => ['class' => 'form-control', 'min' => 0],
            ])
            ->add('team', EntityType::class, [
                'class' => Teams::class,
                'label' => 'Équipe',
                'required' => false,
                'placeholder' => '— Aucune',
                'choice_label' => fn (Teams $t) => $t->getTeamName() ?? (string) $t->getId(),
                'attr' => ['class' => 'form-select'],
                'query_builder' => static fn (TeamsRepository $repo): QueryBuilder => $repo->createQueryBuilder('t')
                    ->orderBy('t.team_name', 'ASC'),
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Inventory::class,
        ]);
    }
}
