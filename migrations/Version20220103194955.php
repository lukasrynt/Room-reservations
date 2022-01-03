<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220103194955 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Remove discriminator column from user';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE "user" DROP discr');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE "user" ADD discr VARCHAR(255) NOT NULL');
    }
}
