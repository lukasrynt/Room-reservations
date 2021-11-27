<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211126115419 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Modify field role in user table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE "user" ALTER role TYPE VARCHAR(20)');
        $this->addSql('ALTER TABLE "user" ALTER role DROP DEFAULT');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "user" ALTER role TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE "user" ALTER role DROP DEFAULT');
    }
}
