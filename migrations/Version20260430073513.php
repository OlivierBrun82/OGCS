<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260430073513 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_ratings (user_id INT NOT NULL, ratings_id INT NOT NULL, INDEX IDX_96BB19C3A76ED395 (user_id), INDEX IDX_96BB19C3957CE84F (ratings_id), PRIMARY KEY (user_id, ratings_id)) DEFAULT CHARACTER SET utf8');
        $this->addSql('ALTER TABLE user_ratings ADD CONSTRAINT FK_96BB19C3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_ratings ADD CONSTRAINT FK_96BB19C3957CE84F FOREIGN KEY (ratings_id) REFERENCES ratings (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_ratings DROP FOREIGN KEY FK_96BB19C3A76ED395');
        $this->addSql('ALTER TABLE user_ratings DROP FOREIGN KEY FK_96BB19C3957CE84F');
        $this->addSql('DROP TABLE user_ratings');
    }
}
