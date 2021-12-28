<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20211228172205 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add date and time for reservation instead of simply date';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reservation ADD date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE reservation ADD time_to TIME(0) WITHOUT TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE reservation ADD time_from TIME(0) WITHOUT TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE reservation DROP date_to');
        $this->addSql('ALTER TABLE reservation DROP date_from');
        $this->addSql('ALTER TABLE room ALTER private DROP DEFAULT');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE reservation ADD date_from TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE reservation DROP time_to');
        $this->addSql('ALTER TABLE reservation DROP time_from');
        $this->addSql('ALTER TABLE reservation RENAME COLUMN date TO date_to');
        $this->addSql('ALTER TABLE room ALTER private SET DEFAULT \'true\'');
    }
}
