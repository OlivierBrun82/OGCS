<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Matchs : équipe domicile / extérieur ; fin de la table teams_matches.
 */
final class Version20260504090520 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Matches: home_team_id, away_team_id — migration depuis teams_matches';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE matches ADD home_team_id INT DEFAULT NULL, ADD away_team_id INT DEFAULT NULL');

        $this->addSql(<<<'SQL'
            UPDATE matches m
            INNER JOIN (
                SELECT matches_id, MIN(teams_id) AS t1, MAX(teams_id) AS t2
                FROM teams_matches
                GROUP BY matches_id
                HAVING COUNT(DISTINCT teams_id) = 2
            ) x ON m.id = x.matches_id
            SET m.home_team_id = LEAST(x.t1, x.t2), m.away_team_id = GREATEST(x.t1, x.t2)
            SQL);

        $this->addSql('DELETE FROM matches WHERE home_team_id IS NULL OR away_team_id IS NULL');

        $this->addSql('ALTER TABLE matches MODIFY home_team_id INT NOT NULL, MODIFY away_team_id INT NOT NULL');

        $this->addSql(<<<'SQL'
            ALTER TABLE
              matches
            ADD
              CONSTRAINT FK_62615BA9C4C13F6 FOREIGN KEY (home_team_id) REFERENCES teams (id) ON DELETE RESTRICT
            SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE
              matches
            ADD
              CONSTRAINT FK_62615BA45185D02 FOREIGN KEY (away_team_id) REFERENCES teams (id) ON DELETE RESTRICT
            SQL);
        $this->addSql('CREATE INDEX IDX_62615BA9C4C13F6 ON matches (home_team_id)');
        $this->addSql('CREATE INDEX IDX_62615BA45185D02 ON matches (away_team_id)');

        $this->addSql('ALTER TABLE teams_matches DROP FOREIGN KEY `FK_AEE9590E4B30DD19`');
        $this->addSql('ALTER TABLE teams_matches DROP FOREIGN KEY `FK_AEE9590ED6365F12`');
        $this->addSql('DROP TABLE teams_matches');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE matches DROP FOREIGN KEY FK_62615BA9C4C13F6');
        $this->addSql('ALTER TABLE matches DROP FOREIGN KEY FK_62615BA45185D02');
        $this->addSql('DROP INDEX IDX_62615BA9C4C13F6 ON matches');
        $this->addSql('DROP INDEX IDX_62615BA45185D02 ON matches');
        $this->addSql('ALTER TABLE matches DROP home_team_id, DROP away_team_id');

        $this->addSql(<<<'SQL'
            CREATE TABLE teams_matches (
              teams_id INT NOT NULL,
              matches_id INT NOT NULL,
              INDEX IDX_AEE9590E4B30DD19 (matches_id),
              INDEX IDX_AEE9590ED6365F12 (teams_id),
              PRIMARY KEY (teams_id, matches_id)
            ) DEFAULT CHARACTER SET utf8mb3 COLLATE `utf8mb3_general_ci` ENGINE = InnoDB
            SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE teams_matches
            ADD CONSTRAINT FK_AEE9590E4B30DD19 FOREIGN KEY (matches_id) REFERENCES matches (id) ON DELETE CASCADE
            SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE teams_matches
            ADD CONSTRAINT FK_AEE9590ED6365F12 FOREIGN KEY (teams_id) REFERENCES teams (id) ON DELETE CASCADE
            SQL);

        $this->addSql(<<<'SQL'
            INSERT INTO teams_matches (matches_id, teams_id)
            SELECT id, home_team_id FROM matches
            SQL);
        $this->addSql(<<<'SQL'
            INSERT INTO teams_matches (matches_id, teams_id)
            SELECT id, away_team_id FROM matches
            SQL);
    }
}
