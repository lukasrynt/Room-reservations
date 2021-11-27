<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211127112039 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE "group" ADD group_manager_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE "group" ADD CONSTRAINT FK_6DC044C5AFF355D1 FOREIGN KEY (group_manager_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_6DC044C5AFF355D1 ON "group" (group_manager_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "group" DROP CONSTRAINT FK_6DC044C5AFF355D1');
        $this->addSql('DROP INDEX IDX_6DC044C5AFF355D1');
        $this->addSql('ALTER TABLE "group" DROP group_manager_id');
    }
}
