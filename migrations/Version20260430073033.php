<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260430073033 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE players_ratings (players_id INT NOT NULL, ratings_id INT NOT NULL, INDEX IDX_D8ECD6E3F1849495 (players_id), INDEX IDX_D8ECD6E3957CE84F (ratings_id), PRIMARY KEY (players_id, ratings_id)) DEFAULT CHARACTER SET utf8');
        $this->addSql('ALTER TABLE players_ratings ADD CONSTRAINT FK_D8ECD6E3F1849495 FOREIGN KEY (players_id) REFERENCES players (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE players_ratings ADD CONSTRAINT FK_D8ECD6E3957CE84F FOREIGN KEY (ratings_id) REFERENCES ratings (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE players_ratings DROP FOREIGN KEY FK_D8ECD6E3F1849495');
        $this->addSql('ALTER TABLE players_ratings DROP FOREIGN KEY FK_D8ECD6E3957CE84F');
        $this->addSql('DROP TABLE players_ratings');
    }
}
