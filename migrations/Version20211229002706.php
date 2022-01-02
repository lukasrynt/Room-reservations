<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20211229002706 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add children and parent attributes to Group table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE "group" ADD parent_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE "group" ADD CONSTRAINT FK_6DC044C5727ACA70 FOREIGN KEY (parent_id) REFERENCES "group" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_6DC044C5727ACA70 ON "group" (parent_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE "group" DROP CONSTRAINT FK_6DC044C5727ACA70');
        $this->addSql('DROP INDEX IDX_6DC044C5727ACA70');
        $this->addSql('ALTER TABLE "group" DROP parent_id');
    }
}
