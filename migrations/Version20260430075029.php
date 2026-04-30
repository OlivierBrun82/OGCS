<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260430075029 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_mailling (user_id INT NOT NULL, mailling_id INT NOT NULL, INDEX IDX_8EE7074AA76ED395 (user_id), INDEX IDX_8EE7074AC378E521 (mailling_id), PRIMARY KEY (user_id, mailling_id)) DEFAULT CHARACTER SET utf8');
        $this->addSql('ALTER TABLE user_mailling ADD CONSTRAINT FK_8EE7074AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_mailling ADD CONSTRAINT FK_8EE7074AC378E521 FOREIGN KEY (mailling_id) REFERENCES mailling (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_mailling DROP FOREIGN KEY FK_8EE7074AA76ED395');
        $this->addSql('ALTER TABLE user_mailling DROP FOREIGN KEY FK_8EE7074AC378E521');
        $this->addSql('DROP TABLE user_mailling');
    }
}
