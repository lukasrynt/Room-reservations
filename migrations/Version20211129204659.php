<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20211129204659 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add M:N association between groups and rooms';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE groups_rooms (group_id INT NOT NULL, room_id INT NOT NULL, PRIMARY KEY(group_id, room_id))');
        $this->addSql('CREATE INDEX IDX_2D027D32FE54D947 ON groups_rooms (group_id)');
        $this->addSql('CREATE INDEX IDX_2D027D3254177093 ON groups_rooms (room_id)');
        $this->addSql('ALTER TABLE groups_rooms ADD CONSTRAINT FK_2D027D32FE54D947 FOREIGN KEY (group_id) REFERENCES "group" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE groups_rooms ADD CONSTRAINT FK_2D027D3254177093 FOREIGN KEY (room_id) REFERENCES room (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE groups_rooms');
    }
}
