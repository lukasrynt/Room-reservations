<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211127134931 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create table for M:N relation between GroupMember and Group';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE members_groups (group_member_id INT NOT NULL, group_id INT NOT NULL, PRIMARY KEY(group_member_id, group_id))');
        $this->addSql('CREATE INDEX IDX_18731E7EB5248F1F ON members_groups (group_member_id)');
        $this->addSql('CREATE INDEX IDX_18731E7EFE54D947 ON members_groups (group_id)');
        $this->addSql('ALTER TABLE members_groups ADD CONSTRAINT FK_18731E7EB5248F1F FOREIGN KEY (group_member_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE members_groups ADD CONSTRAINT FK_18731E7EFE54D947 FOREIGN KEY (group_id) REFERENCES "group" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT fk_8d93d6499897aad');
        $this->addSql('DROP INDEX idx_8d93d6499897aad');
        $this->addSql('ALTER TABLE "user" DROP member_group_id');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE members_groups');
        $this->addSql('ALTER TABLE "user" ADD member_group_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT fk_8d93d6499897aad FOREIGN KEY (member_group_id) REFERENCES "group" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_8d93d6499897aad ON "user" (member_group_id)');
    }
}
