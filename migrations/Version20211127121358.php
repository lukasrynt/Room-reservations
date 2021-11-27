<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211127121358 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add member_group_id field to user table as a foreign key from group table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE "user" ADD member_group_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT FK_8D93D6499897AAD FOREIGN KEY (member_group_id) REFERENCES "group" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_8D93D6499897AAD ON "user" (member_group_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT FK_8D93D6499897AAD');
        $this->addSql('DROP INDEX IDX_8D93D6499897AAD');
        $this->addSql('ALTER TABLE "user" DROP member_group_id');
    }
}
