<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20211117124547 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add ManyToMany association between User and Room';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE members_rooms (room_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(room_id, user_id))');
        $this->addSql('CREATE INDEX IDX_EE973C2D54177093 ON members_rooms (room_id)');
        $this->addSql('CREATE INDEX IDX_EE973C2DA76ED395 ON members_rooms (user_id)');
        $this->addSql('ALTER TABLE members_rooms ADD CONSTRAINT FK_EE973C2D54177093 FOREIGN KEY (room_id) REFERENCES room (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE members_rooms ADD CONSTRAINT FK_EE973C2DA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE members_rooms');
    }
}
