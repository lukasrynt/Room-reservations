<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20211127100619 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Modify user as a parent class, add field type as a discriminator';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE "user" ADD discr VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE "user" DROP discr');
    }
}
