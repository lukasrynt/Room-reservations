<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20211027140621 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Creates the rooms table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE room_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE room (id INT NOT NULL, capacity INT NOT NULL, name VARCHAR(255) NOT NULL, floor INT NOT NULL, opened_from TIME(0) WITHOUT TIME ZONE NOT NULL, opened_to TIME(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE room_id_seq CASCADE');
        $this->addSql('DROP TABLE room');
    }
}
