<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Refonte blames : type de carton, durée / suspension, match lié ; migration des anciennes colonnes de dates.
 */
final class Version20260504085538 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Blames: card_type, duration_minutes, suspension_matches, related_match ; supprime white/yellow/red_card';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE blames ADD card_type VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE blames ADD duration_minutes INT DEFAULT NULL');
        $this->addSql('ALTER TABLE blames ADD suspension_matches INT DEFAULT NULL');
        $this->addSql('ALTER TABLE blames ADD related_match_id INT DEFAULT NULL');

        $this->addSql(<<<'SQL'
            UPDATE blames SET card_type = CASE
                WHEN red_card IS NOT NULL THEN 'red'
                WHEN yellow_card IS NOT NULL THEN 'yellow'
                WHEN white_card IS NOT NULL THEN 'white'
                ELSE 'yellow'
            END
            SQL);

        $this->addSql('UPDATE blames SET start_date = COALESCE(start_date, DATE(COALESCE(red_card, yellow_card, white_card)))');
        $this->addSql('UPDATE blames SET start_date = CURDATE() WHERE start_date IS NULL');

        $this->addSql("UPDATE blames SET duration_minutes = 10 WHERE card_type = 'white' AND (duration_minutes IS NULL OR duration_minutes < 1)");

        $this->addSql('ALTER TABLE blames MODIFY card_type VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE blames MODIFY start_date DATE NOT NULL');

        $this->addSql('ALTER TABLE blames DROP white_card, DROP yellow_card, DROP red_card');

        $this->addSql('ALTER TABLE blames ADD CONSTRAINT FK_BA2FE2CF8C3DE27B FOREIGN KEY (related_match_id) REFERENCES matches (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_BA2FE2CF8C3DE27B ON blames (related_match_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE blames DROP FOREIGN KEY FK_BA2FE2CF8C3DE27B');
        $this->addSql('DROP INDEX IDX_BA2FE2CF8C3DE27B ON blames');
        $this->addSql('ALTER TABLE blames ADD white_card DATETIME DEFAULT NULL, ADD yellow_card DATETIME DEFAULT NULL, ADD red_card DATETIME DEFAULT NULL');
        $this->addSql(<<<'SQL'
            UPDATE blames SET
                white_card = CASE WHEN card_type = 'white' THEN CAST(start_date AS DATETIME) ELSE NULL END,
                yellow_card = CASE WHEN card_type = 'yellow' THEN CAST(start_date AS DATETIME) ELSE NULL END,
                red_card = CASE WHEN card_type = 'red' THEN CAST(start_date AS DATETIME) ELSE NULL END
            SQL);
        $this->addSql('ALTER TABLE blames DROP card_type, DROP duration_minutes, DROP suspension_matches, DROP related_match_id');
        $this->addSql('ALTER TABLE blames MODIFY start_date DATE DEFAULT NULL');
    }
}
