<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20211227220335 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add private column to rooms';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE room ADD private BOOLEAN NOT NULL DEFAULT TRUE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE room DROP private');
    }
}
