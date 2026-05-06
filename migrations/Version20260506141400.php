<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260506141400 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Players: ajoute updated_at pour VichUploader.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE players ADD updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE players DROP updated_at');
    }
}
