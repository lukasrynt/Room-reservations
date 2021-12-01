<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211201184934 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add OneToMany association between groups and rooms and between users and groups';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE room ADD group_id INT NOT NULL');
        $this->addSql('ALTER TABLE room ADD CONSTRAINT FK_729F519BFE54D947 FOREIGN KEY (group_id) REFERENCES "group" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_729F519BFE54D947 ON room (group_id)');

        $this->addSql('ALTER TABLE "user" ADD group_id INT NOT NULL');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT FK_8D93D649FE54D947 FOREIGN KEY (group_id) REFERENCES "group" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_8D93D649FE54D947 ON "user" (group_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE room DROP CONSTRAINT FK_729F519BFE54D947');
        $this->addSql('DROP INDEX IDX_729F519BFE54D947');
        $this->addSql('ALTER TABLE room DROP group_id');

        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT FK_8D93D649FE54D947');
        $this->addSql('DROP INDEX IDX_8D93D649FE54D947');
        $this->addSql('ALTER TABLE "user" DROP group_id');
    }
}
