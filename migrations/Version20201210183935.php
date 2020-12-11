<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201210183935 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user CHANGE valid active TINYINT(1) NOT NULL, CHANGE valid_at active_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE wallet ADD limit_amount_per_week INT NOT NULL, DROP limit_per_week, CHANGE balance balance INT NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user CHANGE active valid TINYINT(1) NOT NULL, CHANGE active_at valid_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE wallet ADD limit_per_week DOUBLE PRECISION NOT NULL, DROP limit_amount_per_week, CHANGE balance balance DOUBLE PRECISION NOT NULL');
    }
}
