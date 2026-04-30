<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260430085026 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE teams_matches (teams_id INT NOT NULL, matches_id INT NOT NULL, INDEX IDX_AEE9590ED6365F12 (teams_id), INDEX IDX_AEE9590E4B30DD19 (matches_id), PRIMARY KEY (teams_id, matches_id)) DEFAULT CHARACTER SET utf8');
        $this->addSql('ALTER TABLE teams_matches ADD CONSTRAINT FK_AEE9590ED6365F12 FOREIGN KEY (teams_id) REFERENCES teams (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE teams_matches ADD CONSTRAINT FK_AEE9590E4B30DD19 FOREIGN KEY (matches_id) REFERENCES matches (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE teams_matches DROP FOREIGN KEY FK_AEE9590ED6365F12');
        $this->addSql('ALTER TABLE teams_matches DROP FOREIGN KEY FK_AEE9590E4B30DD19');
        $this->addSql('DROP TABLE teams_matches');
    }
}
