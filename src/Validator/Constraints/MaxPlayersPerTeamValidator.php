<?php

namespace App\Validator\Constraints;

use App\Entity\Players;
use App\Repository\PlayersRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class MaxPlayersPerTeamValidator extends ConstraintValidator
{
    public function __construct(
        private readonly PlayersRepository $playersRepository,
    ) {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof MaxPlayersPerTeam) {
            throw new UnexpectedTypeException($constraint, MaxPlayersPerTeam::class);
        }

        if (!$value instanceof Players) {
            return;
        }

        $team = $value->getTeams();
        if ($team === null || $team->getId() === null) {
            return;
        }

        $count = $this->playersRepository->countByTeamIdExcludingPlayer(
            $team->getId(),
            $value->getId(),
        );

        if ($count >= $constraint->max) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ max }}', (string) $constraint->max)
                ->atPath('teams')
                ->addViolation();
        }
    }
}
