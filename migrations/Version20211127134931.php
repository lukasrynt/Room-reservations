<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20211127134931 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add M:N relation between User and Group';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE user_group (user_id INT NOT NULL, group_id INT NOT NULL, PRIMARY KEY(user_id, group_id))');
        $this->addSql('CREATE INDEX IDX_18731E7EB5248F1F ON user_group (user_id)');
        $this->addSql('CREATE INDEX IDX_18731E7EFE54D947 ON user_group (group_id)');
        $this->addSql('ALTER TABLE user_group ADD CONSTRAINT FK_18731E7EB5248F1F FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_group ADD CONSTRAINT FK_18731E7EFE54D947 FOREIGN KEY (group_id) REFERENCES "group" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE user_group');
    }
}
