<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260504124514 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Inventaires : plusieurs par équipe (team_id sur inventory, fin de inventory_id sur teams).';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE inventory ADD team_id INT DEFAULT NULL');
        $this->addSql('UPDATE inventory i INNER JOIN teams t ON t.inventory_id = i.id SET i.team_id = t.id');
        $this->addSql('ALTER TABLE inventory ADD CONSTRAINT FK_B12D4A36296CD8AE FOREIGN KEY (team_id) REFERENCES teams (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_B12D4A36296CD8AE ON inventory (team_id)');
        $this->addSql('ALTER TABLE teams DROP FOREIGN KEY `FK_96C222589EEA759`');
        $this->addSql('DROP INDEX UNIQ_96C222589EEA759 ON teams');
        $this->addSql('ALTER TABLE teams DROP inventory_id');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE teams ADD inventory_id INT DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_96C222589EEA759 ON teams (inventory_id)');
        $this->addSql('ALTER TABLE teams ADD CONSTRAINT `FK_96C222589EEA759` FOREIGN KEY (inventory_id) REFERENCES inventory (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('UPDATE teams t INNER JOIN inventory i ON i.team_id = t.id SET t.inventory_id = i.id');
        $this->addSql('ALTER TABLE inventory DROP FOREIGN KEY FK_B12D4A36296CD8AE');
        $this->addSql('DROP INDEX IDX_B12D4A36296CD8AE ON inventory');
        $this->addSql('ALTER TABLE inventory DROP team_id');
    }
}
