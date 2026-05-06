<?php

namespace App\Form;

use App\Entity\Players;
use App\Entity\Ratings;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RatingsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        if (!$options['hide_player']) {
            $builder->add('player', EntityType::class, [
                'class' => Players::class,
                'label' => 'Joueur',
                'choice_label' => fn (Players $p) => trim($p->getFirstName().' '.$p->getLastName()),
                'placeholder' => '—',
                'attr' => ['class' => 'form-select'],
            ]);
        }

        $builder
            ->add('rating', IntegerType::class, [
                'label' => 'Note (1 à 10)',
                'block_prefix' => 'rating_stars',
                'attr' => [
                    'min' => 1,
                    'max' => 10,
                ],
                'help' => 'Cliquez sur les étoiles pour choisir une note de 1 à 10.',
            ])
            ->add('message', TextareaType::class, [
                'label' => 'Commentaire (optionnel)',
                'required' => false,
                'attr' => ['rows' => 4, 'class' => 'form-control'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ratings::class,
            'hide_player' => false,
        ]);
        $resolver->setAllowedTypes('hide_player', 'bool');
    }
}
