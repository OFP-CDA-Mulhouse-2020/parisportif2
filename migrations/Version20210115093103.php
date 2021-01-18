<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210115093103 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE card_id_file DROP FOREIGN KEY FK_50A5A064A76ED395');
        $this->addSql('DROP INDEX UNIQ_50A5A064A76ED395 ON card_id_file');
        $this->addSql('ALTER TABLE card_id_file DROP user_id');
        $this->addSql('ALTER TABLE user ADD card_id_file_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64921244D12 FOREIGN KEY (card_id_file_id) REFERENCES card_id_file (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D64921244D12 ON user (card_id_file_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE card_id_file ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE card_id_file ADD CONSTRAINT FK_50A5A064A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_50A5A064A76ED395 ON card_id_file (user_id)');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64921244D12');
        $this->addSql('DROP INDEX UNIQ_8D93D64921244D12 ON user');
        $this->addSql('ALTER TABLE user DROP card_id_file_id');
    }
}
