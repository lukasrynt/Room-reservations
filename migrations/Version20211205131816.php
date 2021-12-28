<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20211205131816 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add decomposition of ManyToMany association between User-Reservation and Reservation-User';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE reservation_user (reservation_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(reservation_id, user_id))');
        $this->addSql('CREATE INDEX IDX_9BAA1B21B83297E7 ON reservation_user (reservation_id)');
        $this->addSql('CREATE INDEX IDX_9BAA1B21A76ED395 ON reservation_user (user_id)');
        $this->addSql('ALTER TABLE reservation_user ADD CONSTRAINT FK_9BAA1B21B83297E7 FOREIGN KEY (reservation_id) REFERENCES reservation (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE reservation_user ADD CONSTRAINT FK_9BAA1B21A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER INDEX idx_ee973c2d54177093 RENAME TO IDX_DA5227D154177093');
        $this->addSql('ALTER INDEX idx_ee973c2da76ed395 RENAME TO IDX_DA5227D1A76ED395');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE reservation_user');
        $this->addSql('ALTER INDEX idx_da5227d1a76ed395 RENAME TO idx_ee973c2da76ed395');
        $this->addSql('ALTER INDEX idx_da5227d154177093 RENAME TO idx_ee973c2d54177093');
    }
}
