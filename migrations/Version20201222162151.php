<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201222162151 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cart (id INT AUTO_INCREMENT NOT NULL, sum INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE item (id INT AUTO_INCREMENT NOT NULL, bet_id INT DEFAULT NULL, cart_id INT DEFAULT NULL, payment_id INT DEFAULT NULL, expected_bet_result INT NOT NULL, recorded_odds INT NOT NULL, amount INT NOT NULL, item_status_id INT NOT NULL, INDEX IDX_1F1B251ED871DC26 (bet_id), INDEX IDX_1F1B251E1AD5CDBF (cart_id), INDEX IDX_1F1B251E4C3A3BB (payment_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251ED871DC26 FOREIGN KEY (bet_id) REFERENCES bet (id)');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251E1AD5CDBF FOREIGN KEY (cart_id) REFERENCES cart (id)');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251E4C3A3BB FOREIGN KEY (payment_id) REFERENCES payment (id)');
        $this->addSql('DROP TABLE `order`');
        $this->addSql('ALTER TABLE bet CHANGE type_of_bet_id type_of_bet_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE bet ADD CONSTRAINT FK_FBF0EC9B7BBF562C FOREIGN KEY (type_of_bet_id) REFERENCES type_of_bet (id)');
        $this->addSql('CREATE INDEX IDX_FBF0EC9B7BBF562C ON bet (type_of_bet_id)');
        $this->addSql('ALTER TABLE payment ADD wallet_id INT DEFAULT NULL, ADD website_wallet_id INT DEFAULT NULL, CHANGE amount sum INT NOT NULL');
        $this->addSql('ALTER TABLE payment ADD CONSTRAINT FK_6D28840D712520F3 FOREIGN KEY (wallet_id) REFERENCES wallet (id)');
        $this->addSql('ALTER TABLE payment ADD CONSTRAINT FK_6D28840DE2FDC827 FOREIGN KEY (website_wallet_id) REFERENCES website_wallet (id)');
        $this->addSql('CREATE INDEX IDX_6D28840D712520F3 ON payment (wallet_id)');
        $this->addSql('CREATE INDEX IDX_6D28840DE2FDC827 ON payment (website_wallet_id)');
        $this->addSql('ALTER TABLE user ADD cart_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6491AD5CDBF FOREIGN KEY (cart_id) REFERENCES cart (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D6491AD5CDBF ON user (cart_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE item DROP FOREIGN KEY FK_1F1B251E1AD5CDBF');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6491AD5CDBF');
        $this->addSql('CREATE TABLE `order` (id INT AUTO_INCREMENT NOT NULL, bet_id INT NOT NULL, recorded_odds INT NOT NULL, amount INT NOT NULL, order_at DATETIME NOT NULL, order_status_id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE cart');
        $this->addSql('DROP TABLE item');
        $this->addSql('ALTER TABLE bet DROP FOREIGN KEY FK_FBF0EC9B7BBF562C');
        $this->addSql('DROP INDEX IDX_FBF0EC9B7BBF562C ON bet');
        $this->addSql('ALTER TABLE bet CHANGE type_of_bet_id type_of_bet_id INT NOT NULL');
        $this->addSql('ALTER TABLE payment DROP FOREIGN KEY FK_6D28840D712520F3');
        $this->addSql('ALTER TABLE payment DROP FOREIGN KEY FK_6D28840DE2FDC827');
        $this->addSql('DROP INDEX IDX_6D28840D712520F3 ON payment');
        $this->addSql('DROP INDEX IDX_6D28840DE2FDC827 ON payment');
        $this->addSql('ALTER TABLE payment DROP wallet_id, DROP website_wallet_id, CHANGE sum amount INT NOT NULL');
        $this->addSql('DROP INDEX UNIQ_8D93D6491AD5CDBF ON user');
        $this->addSql('ALTER TABLE user DROP cart_id');
    }
}
