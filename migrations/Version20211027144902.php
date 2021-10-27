<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20211027144902 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Creates new table building';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE building_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE building (id INT NOT NULL,
                                                  name VARCHAR(255) NOT NULL,
                                                  address VARCHAR(255) NOT NULL,
                                                  PRIMARY KEY(id))');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE building_id_seq CASCADE');
        $this->addSql('DROP TABLE building');
    }
}
