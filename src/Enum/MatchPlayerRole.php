<?php

namespace App\Enum;

enum MatchPlayerRole: string
{
    case Gardien = 'gardien';
    case DefenseurCentral = 'defenseur_central';
    case LateralDroit = 'lateral_droit';
    case LateralGauche = 'lateral_gauche';
    case MilieuDefensif = 'milieu_defensif';
    case MilieuCentral = 'milieu_central';
    case MilieuOffensif = 'milieu_offensif';
    case AilierDroit = 'ailier_droit';
    case AilierGauche = 'ailier_gauche';
    case Attaquant = 'attaquant';
    case Remplacant = 'remplacant';

    public function label(): string
    {
        return match ($this) {
            self::Gardien => 'Gardien de but',
            self::DefenseurCentral => 'Défenseur central',
            self::LateralDroit => 'Latéral droit',
            self::LateralGauche => 'Latéral gauche',
            self::MilieuDefensif => 'Milieu défensif',
            self::MilieuCentral => 'Milieu central',
            self::MilieuOffensif => 'Milieu offensif / relayeur',
            self::AilierDroit => 'Ailier droit',
            self::AilierGauche => 'Ailier gauche',
            self::Attaquant => 'Attaquant / avant-centre',
            self::Remplacant => 'Remplaçant (non défini sur le terrain)',
        };
    }
}
