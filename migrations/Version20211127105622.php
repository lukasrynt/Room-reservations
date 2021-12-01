<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20211127105622 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add room_manager_id field to room as a foreign key from user table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE room ADD room_manager_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE room ADD CONSTRAINT FK_729F519B4CCC447F FOREIGN KEY (room_manager_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_729F519B4CCC447F ON room (room_manager_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE room DROP CONSTRAINT FK_729F519B4CCC447F');
        $this->addSql('DROP INDEX IDX_729F519B4CCC447F');
        $this->addSql('ALTER TABLE room DROP room_manager_id');
    }
}
