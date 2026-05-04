<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260504121414 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Absences: période (du/au), FK user et joueur NOT NULL (anciennes lignes supprimées).';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('DELETE FROM abscences');
        $this->addSql('ALTER TABLE abscences DROP FOREIGN KEY `FK_7D0323DDA76ED395`');
        $this->addSql('ALTER TABLE abscences DROP FOREIGN KEY `FK_7D0323DDF1849495`');
        $this->addSql('ALTER TABLE abscences ADD absence_start DATE NOT NULL, ADD absence_end DATE NOT NULL, CHANGE players_id players_id INT NOT NULL, CHANGE user_id user_id INT NOT NULL');
        $this->addSql('ALTER TABLE abscences ADD CONSTRAINT FK_7D0323DDA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE abscences ADD CONSTRAINT FK_7D0323DDF1849495 FOREIGN KEY (players_id) REFERENCES players (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE abscences DROP FOREIGN KEY FK_7D0323DDF1849495');
        $this->addSql('ALTER TABLE abscences DROP FOREIGN KEY FK_7D0323DDA76ED395');
        $this->addSql('ALTER TABLE abscences DROP absence_start, DROP absence_end, CHANGE players_id players_id INT DEFAULT NULL, CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE abscences ADD CONSTRAINT `FK_7D0323DDF1849495` FOREIGN KEY (players_id) REFERENCES players (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE abscences ADD CONSTRAINT `FK_7D0323DDA76ED395` FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
