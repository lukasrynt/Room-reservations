<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20211117154123 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add relation between User-Request and Request-Room';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE request_user (request_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(request_id, user_id))');
        $this->addSql('CREATE INDEX IDX_F234F1B3427EB8A5 ON request_user (request_id)');
        $this->addSql('CREATE INDEX IDX_F234F1B3A76ED395 ON request_user (user_id)');
        $this->addSql('ALTER TABLE request_user ADD CONSTRAINT FK_F234F1B3427EB8A5 FOREIGN KEY (request_id) REFERENCES request (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE request_user ADD CONSTRAINT FK_F234F1B3A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE request ADD room_id INT NOT NULL');
        $this->addSql('ALTER TABLE request ADD requestor_id INT NOT NULL');
        $this->addSql('ALTER TABLE request ADD CONSTRAINT FK_3B978F9F54177093 FOREIGN KEY (room_id) REFERENCES room (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE request ADD CONSTRAINT FK_3B978F9FA7F43455 FOREIGN KEY (requestor_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_3B978F9F54177093 ON request (room_id)');
        $this->addSql('CREATE INDEX IDX_3B978F9FA7F43455 ON request (requestor_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE request_user');
        $this->addSql('ALTER TABLE request DROP CONSTRAINT FK_3B978F9F54177093');
        $this->addSql('ALTER TABLE request DROP CONSTRAINT FK_3B978F9FA7F43455');
        $this->addSql('DROP INDEX IDX_3B978F9F54177093');
        $this->addSql('DROP INDEX IDX_3B978F9FA7F43455');
        $this->addSql('ALTER TABLE request DROP room_id');
        $this->addSql('ALTER TABLE request DROP requestor_id');
    }
}
