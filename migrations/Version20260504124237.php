<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260504124237 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Inventaire : une équipe référence au plus un inventaire (inventory_id UNIQUE).';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE teams DROP INDEX IDX_96C222589EEA759, ADD UNIQUE INDEX UNIQ_96C222589EEA759 (inventory_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE teams DROP INDEX UNIQ_96C222589EEA759, ADD INDEX IDX_96C222589EEA759 (inventory_id)');
    }
}
