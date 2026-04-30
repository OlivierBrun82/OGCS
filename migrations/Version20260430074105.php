<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260430074105 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE abscences ADD players_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE abscences ADD CONSTRAINT FK_7D0323DDF1849495 FOREIGN KEY (players_id) REFERENCES players (id)');
        $this->addSql('CREATE INDEX IDX_7D0323DDF1849495 ON abscences (players_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE abscences DROP FOREIGN KEY FK_7D0323DDF1849495');
        $this->addSql('DROP INDEX IDX_7D0323DDF1849495 ON abscences');
        $this->addSql('ALTER TABLE abscences DROP players_id');
    }
}
