<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260430074838 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE blames ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE blames ADD CONSTRAINT FK_BA2FE2CFA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_BA2FE2CFA76ED395 ON blames (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE blames DROP FOREIGN KEY FK_BA2FE2CFA76ED395');
        $this->addSql('DROP INDEX IDX_BA2FE2CFA76ED395 ON blames');
        $this->addSql('ALTER TABLE blames DROP user_id');
    }
}
