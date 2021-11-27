<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211127140736 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create new table for M:N decomposition between RoomUser and Room';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE members_rooms (room_id INT NOT NULL, room_user_id INT NOT NULL, PRIMARY KEY(room_id, room_user_id))');
        $this->addSql('CREATE INDEX IDX_DA5227D154177093 ON members_rooms (room_id)');
        $this->addSql('CREATE INDEX IDX_DA5227D14B4C6F75 ON members_rooms (room_user_id)');
        $this->addSql('ALTER TABLE members_rooms ADD CONSTRAINT FK_DA5227D154177093 FOREIGN KEY (room_id) REFERENCES room (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE members_rooms ADD CONSTRAINT FK_DA5227D14B4C6F75 FOREIGN KEY (room_user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE members_rooms');
    }
}
