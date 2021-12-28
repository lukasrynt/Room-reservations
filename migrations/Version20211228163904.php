<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20211228163904 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Remove requests from and merge state into reservations';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE request_user DROP CONSTRAINT fk_f234f1b3427eb8a5');
        $this->addSql('DROP SEQUENCE request_id_seq CASCADE');
        $this->addSql('DROP TABLE request_user');
        $this->addSql('DROP TABLE request');
        $this->addSql('ALTER TABLE reservation ADD state VARCHAR(20) DEFAULT \'PENDING\' NOT NULL');
        $this->addSql('COMMENT ON COLUMN reservation.state IS \'(DC2Type:enum_state_type)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE request_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE request_user (request_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(request_id, user_id))');
        $this->addSql('CREATE INDEX idx_f234f1b3a76ed395 ON request_user (user_id)');
        $this->addSql('CREATE INDEX idx_f234f1b3427eb8a5 ON request_user (request_id)');
        $this->addSql('CREATE TABLE request (id INT NOT NULL, room_id INT NOT NULL, requestor_id INT NOT NULL, date_from TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, date_to TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, state VARCHAR(20) DEFAULT \'PENDING\' NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_3b978f9fa7f43455 ON request (requestor_id)');
        $this->addSql('CREATE INDEX idx_3b978f9f54177093 ON request (room_id)');
        $this->addSql('ALTER TABLE request_user ADD CONSTRAINT fk_f234f1b3427eb8a5 FOREIGN KEY (request_id) REFERENCES request (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE request_user ADD CONSTRAINT fk_f234f1b3a76ed395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE request ADD CONSTRAINT fk_3b978f9f54177093 FOREIGN KEY (room_id) REFERENCES room (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE request ADD CONSTRAINT fk_3b978f9fa7f43455 FOREIGN KEY (requestor_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE reservation DROP state');
    }
}
