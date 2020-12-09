<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201209125310 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD valid TINYINT(1) NOT NULL, ADD valid_at DATETIME DEFAULT NULL, ADD suspended TINYINT(1) NOT NULL, ADD suspended_at DATETIME DEFAULT NULL, ADD deleted TINYINT(1) NOT NULL, ADD deleted_at DATETIME DEFAULT NULL, DROP is_valid, DROP is_valid_at, DROP is_suspended, DROP is_suspended_at, DROP is_deleted, DROP is_deleted_at');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD is_valid TINYINT(1) NOT NULL, ADD is_valid_at DATETIME DEFAULT NULL, ADD is_suspended TINYINT(1) NOT NULL, ADD is_suspended_at DATETIME DEFAULT NULL, ADD is_deleted TINYINT(1) NOT NULL, ADD is_deleted_at DATETIME DEFAULT NULL, DROP valid, DROP valid_at, DROP suspended, DROP suspended_at, DROP deleted, DROP deleted_at');
    }
}
