<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211127111457 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Delete association attendee';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE request_user');
        $this->addSql('ALTER TABLE "user" ALTER role TYPE VARCHAR(20)');
        $this->addSql('ALTER TABLE "user" ALTER role DROP DEFAULT');
        $this->addSql('ALTER TABLE "user" ALTER role TYPE VARCHAR(20)');
        $this->addSql('COMMENT ON COLUMN "user".role IS \'(DC2Type:enum_roles_type)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE TABLE request_user (request_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(request_id, user_id))');
        $this->addSql('CREATE INDEX idx_f234f1b3427eb8a5 ON request_user (request_id)');
        $this->addSql('CREATE INDEX idx_f234f1b3a76ed395 ON request_user (user_id)');
        $this->addSql('ALTER TABLE request_user ADD CONSTRAINT fk_f234f1b3427eb8a5 FOREIGN KEY (request_id) REFERENCES request (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE request_user ADD CONSTRAINT fk_f234f1b3a76ed395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "user" ALTER role TYPE VARCHAR(20)');
        $this->addSql('ALTER TABLE "user" ALTER role DROP DEFAULT');
        $this->addSql('COMMENT ON COLUMN "user".role IS NULL');
    }
}
