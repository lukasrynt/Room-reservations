<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211230170522 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add last_access, locked and access_counter fields into Room entity';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE room ADD last_access INT DEFAULT NULL');
        $this->addSql('ALTER TABLE room ADD locked BOOLEAN NOT NULL');
        $this->addSql('ALTER TABLE room ADD access_counter INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE room DROP last_access');
        $this->addSql('ALTER TABLE room DROP locked');
        $this->addSql('ALTER TABLE room DROP access_counter');
    }
}
