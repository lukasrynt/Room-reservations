<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211127100619 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Modify user as a parent class, add field type as a discriminator';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE "user" ADD type VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE "user" ALTER role TYPE VARCHAR(20)');
        $this->addSql('ALTER TABLE "user" ALTER role DROP DEFAULT');
        $this->addSql('ALTER TABLE "user" ALTER role TYPE VARCHAR(20)');
        $this->addSql('COMMENT ON COLUMN "user".role IS \'(DC2Type:enum_roles_type)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "user" DROP type');
        $this->addSql('ALTER TABLE "user" ALTER role TYPE VARCHAR(20)');
        $this->addSql('ALTER TABLE "user" ALTER role DROP DEFAULT');
        $this->addSql('COMMENT ON COLUMN "user".role IS NULL');
    }
}
