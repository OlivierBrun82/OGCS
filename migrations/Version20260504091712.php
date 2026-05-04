<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260504091712 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Table match_composition: joueur + rôle par match.';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE match_composition (
              id INT AUTO_INCREMENT NOT NULL,
              role VARCHAR(255) NOT NULL,
              match_id INT NOT NULL,
              player_id INT NOT NULL,
              INDEX IDX_3EE6326F2ABEACD6 (match_id),
              INDEX IDX_3EE6326F99E6F5DF (player_id),
              UNIQUE INDEX uniq_composition_match_player (match_id, player_id),
              PRIMARY KEY (id)
            ) DEFAULT CHARACTER SET utf8
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE
              match_composition
            ADD
              CONSTRAINT FK_3EE6326F2ABEACD6 FOREIGN KEY (match_id) REFERENCES matches (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE
              match_composition
            ADD
              CONSTRAINT FK_3EE6326F99E6F5DF FOREIGN KEY (player_id) REFERENCES players (id) ON DELETE CASCADE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE match_composition DROP FOREIGN KEY FK_3EE6326F2ABEACD6');
        $this->addSql('ALTER TABLE match_composition DROP FOREIGN KEY FK_3EE6326F99E6F5DF');
        $this->addSql('DROP TABLE match_composition');
    }
}
