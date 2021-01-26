<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210126111508 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE bank_account_file (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, valid TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE bank_account ADD bank_account_file_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE bank_account ADD CONSTRAINT FK_53A23E0AA5785F28 FOREIGN KEY (bank_account_file_id) REFERENCES bank_account_file (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_53A23E0AA5785F28 ON bank_account (bank_account_file_id)');
        $this->addSql('ALTER TABLE card_id_file CHANGE is_card_id_valid valid TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bank_account DROP FOREIGN KEY FK_53A23E0AA5785F28');
        $this->addSql('DROP TABLE bank_account_file');
        $this->addSql('DROP INDEX UNIQ_53A23E0AA5785F28 ON bank_account');
        $this->addSql('ALTER TABLE bank_account DROP bank_account_file_id');
        $this->addSql('ALTER TABLE card_id_file CHANGE valid is_card_id_valid TINYINT(1) NOT NULL');
    }
}
