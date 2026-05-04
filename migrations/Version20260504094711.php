<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260504094711 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Ratings: coach_id + player_id, suppression des tables N-N ; repart de zéro.';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE players_ratings DROP FOREIGN KEY `FK_D8ECD6E3957CE84F`');
        $this->addSql('ALTER TABLE players_ratings DROP FOREIGN KEY `FK_D8ECD6E3F1849495`');
        $this->addSql('ALTER TABLE user_ratings DROP FOREIGN KEY `FK_96BB19C3957CE84F`');
        $this->addSql('ALTER TABLE user_ratings DROP FOREIGN KEY `FK_96BB19C3A76ED395`');
        $this->addSql('DROP TABLE players_ratings');
        $this->addSql('DROP TABLE user_ratings');
        $this->addSql('DELETE FROM ratings');
        $this->addSql(<<<'SQL'
            ALTER TABLE
              ratings
            ADD
              coach_id INT NOT NULL,
            ADD
              player_id INT NOT NULL,
            CHANGE
              rating rating INT NOT NULL,
            CHANGE
              message message VARCHAR(500) DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE
              ratings
            ADD
              CONSTRAINT FK_CEB607C93C105691 FOREIGN KEY (coach_id) REFERENCES user (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE
              ratings
            ADD
              CONSTRAINT FK_CEB607C999E6F5DF FOREIGN KEY (player_id) REFERENCES players (id) ON DELETE CASCADE
        SQL);
        $this->addSql('CREATE INDEX IDX_CEB607C93C105691 ON ratings (coach_id)');
        $this->addSql('CREATE INDEX IDX_CEB607C999E6F5DF ON ratings (player_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE players_ratings (
              players_id INT NOT NULL,
              ratings_id INT NOT NULL,
              INDEX IDX_D8ECD6E3957CE84F (ratings_id),
              INDEX IDX_D8ECD6E3F1849495 (players_id),
              PRIMARY KEY (players_id, ratings_id)
            ) DEFAULT CHARACTER SET utf8mb3 COLLATE `utf8mb3_general_ci` ENGINE = InnoDB COMMENT = ''
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE user_ratings (
              user_id INT NOT NULL,
              ratings_id INT NOT NULL,
              INDEX IDX_96BB19C3957CE84F (ratings_id),
              INDEX IDX_96BB19C3A76ED395 (user_id),
              PRIMARY KEY (user_id, ratings_id)
            ) DEFAULT CHARACTER SET utf8mb3 COLLATE `utf8mb3_general_ci` ENGINE = InnoDB COMMENT = ''
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE
              players_ratings
            ADD
              CONSTRAINT `FK_D8ECD6E3957CE84F` FOREIGN KEY (ratings_id) REFERENCES ratings (id) ON
            UPDATE
              NO ACTION ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE
              players_ratings
            ADD
              CONSTRAINT `FK_D8ECD6E3F1849495` FOREIGN KEY (players_id) REFERENCES players (id) ON
            UPDATE
              NO ACTION ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE
              user_ratings
            ADD
              CONSTRAINT `FK_96BB19C3957CE84F` FOREIGN KEY (ratings_id) REFERENCES ratings (id) ON
            UPDATE
              NO ACTION ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE
              user_ratings
            ADD
              CONSTRAINT `FK_96BB19C3A76ED395` FOREIGN KEY (user_id) REFERENCES user (id) ON
            UPDATE
              NO ACTION ON DELETE CASCADE
        SQL);
        $this->addSql('ALTER TABLE ratings DROP FOREIGN KEY FK_CEB607C93C105691');
        $this->addSql('ALTER TABLE ratings DROP FOREIGN KEY FK_CEB607C999E6F5DF');
        $this->addSql('DROP INDEX IDX_CEB607C93C105691 ON ratings');
        $this->addSql('DROP INDEX IDX_CEB607C999E6F5DF ON ratings');
        $this->addSql(<<<'SQL'
            ALTER TABLE
              ratings
            DROP
              coach_id,
            DROP
              player_id,
            CHANGE
              rating rating INT DEFAULT NULL,
            CHANGE
              message message VARCHAR(255) DEFAULT NULL
        SQL);
    }
}
