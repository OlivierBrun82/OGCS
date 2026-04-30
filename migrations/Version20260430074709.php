<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260430074709 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE blames ADD players_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE blames ADD CONSTRAINT FK_BA2FE2CFF1849495 FOREIGN KEY (players_id) REFERENCES players (id)');
        $this->addSql('CREATE INDEX IDX_BA2FE2CFF1849495 ON blames (players_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE blames DROP FOREIGN KEY FK_BA2FE2CFF1849495');
        $this->addSql('DROP INDEX IDX_BA2FE2CFF1849495 ON blames');
        $this->addSql('ALTER TABLE blames DROP players_id');
    }
}
