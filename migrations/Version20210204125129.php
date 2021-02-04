<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210204125129 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE address (id INT AUTO_INCREMENT NOT NULL, address_number_and_street VARCHAR(255) NOT NULL, zip_code INT NOT NULL, city VARCHAR(255) NOT NULL, country VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bank_account (id INT AUTO_INCREMENT NOT NULL, bank_account_file_id INT DEFAULT NULL, iban_code VARCHAR(255) NOT NULL, bic_code VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_53A23E0AA5785F28 (bank_account_file_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bank_account_file (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, valid TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bet (id INT AUTO_INCREMENT NOT NULL, type_of_bet_id INT DEFAULT NULL, event_id INT DEFAULT NULL, bet_limit_time DATETIME NOT NULL, list_of_odds LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', bet_opened TINYINT(1) NOT NULL, bet_result LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', INDEX IDX_FBF0EC9B7BBF562C (type_of_bet_id), INDEX IDX_FBF0EC9B71F7E88B (event_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE card_id_file (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, valid TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cart (id INT AUTO_INCREMENT NOT NULL, sum INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE competition (id INT AUTO_INCREMENT NOT NULL, sport_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, start_at DATETIME NOT NULL, end_at DATETIME NOT NULL, INDEX IDX_B50A2CB1AC78BCF8 (sport_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event (id INT AUTO_INCREMENT NOT NULL, competition_id INT DEFAULT NULL, sport_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, location VARCHAR(255) NOT NULL, event_date_time DATETIME NOT NULL, event_time_zone VARCHAR(255) NOT NULL, INDEX IDX_3BAE0AA77B39D312 (competition_id), INDEX IDX_3BAE0AA7AC78BCF8 (sport_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE item (id INT AUTO_INCREMENT NOT NULL, bet_id INT DEFAULT NULL, cart_id INT DEFAULT NULL, payment_id INT DEFAULT NULL, expected_bet_result INT NOT NULL, recorded_odds INT NOT NULL, amount INT NOT NULL, item_status_id INT NOT NULL, INDEX IDX_1F1B251ED871DC26 (bet_id), INDEX IDX_1F1B251E1AD5CDBF (cart_id), INDEX IDX_1F1B251E4C3A3BB (payment_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE payment (id INT AUTO_INCREMENT NOT NULL, type_of_payment_id INT DEFAULT NULL, wallet_id INT DEFAULT NULL, website_wallet_id INT DEFAULT NULL, payment_name VARCHAR(255) NOT NULL, date_payment DATETIME NOT NULL, sum INT NOT NULL, payment_status_id INT NOT NULL, INDEX IDX_6D28840D9D7AD51B (type_of_payment_id), INDEX IDX_6D28840D712520F3 (wallet_id), INDEX IDX_6D28840DE2FDC827 (website_wallet_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE player (id INT AUTO_INCREMENT NOT NULL, team_id INT DEFAULT NULL, sport_id INT DEFAULT NULL, last_name VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, player_status INT NOT NULL, ranking INT NOT NULL, INDEX IDX_98197A65296CD8AE (team_id), INDEX IDX_98197A65AC78BCF8 (sport_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reset_password_request (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7CE748AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sport (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, nb_of_teams INT NOT NULL, nb_of_players INT NOT NULL, nb_of_substitute_players INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE team (id INT AUTO_INCREMENT NOT NULL, sport_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, ranking INT NOT NULL, INDEX IDX_C4E0A61FAC78BCF8 (sport_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE team_event (team_id INT NOT NULL, event_id INT NOT NULL, INDEX IDX_FCA843E7296CD8AE (team_id), INDEX IDX_FCA843E771F7E88B (event_id), PRIMARY KEY(team_id, event_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_of_bet (id INT AUTO_INCREMENT NOT NULL, bet_type VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_of_payment (id INT AUTO_INCREMENT NOT NULL, type_of_payment VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, address_id INT DEFAULT NULL, bank_account_id INT DEFAULT NULL, cart_id INT DEFAULT NULL, wallet_id INT DEFAULT NULL, card_id_file_id INT DEFAULT NULL, last_name VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, roles JSON NOT NULL, birth_date DATETIME NOT NULL, create_at DATETIME NOT NULL, active TINYINT(1) NOT NULL, active_at DATETIME DEFAULT NULL, suspended TINYINT(1) NOT NULL, suspended_at DATETIME DEFAULT NULL, deleted TINYINT(1) NOT NULL, deleted_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), UNIQUE INDEX UNIQ_8D93D649F5B7AF75 (address_id), UNIQUE INDEX UNIQ_8D93D64912CB990C (bank_account_id), UNIQUE INDEX UNIQ_8D93D6491AD5CDBF (cart_id), UNIQUE INDEX UNIQ_8D93D649712520F3 (wallet_id), UNIQUE INDEX UNIQ_8D93D64921244D12 (card_id_file_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE wallet (id INT AUTO_INCREMENT NOT NULL, balance INT NOT NULL, limit_amount_per_week INT NOT NULL, real_money TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE website_wallet (id INT AUTO_INCREMENT NOT NULL, balance INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE bank_account ADD CONSTRAINT FK_53A23E0AA5785F28 FOREIGN KEY (bank_account_file_id) REFERENCES bank_account_file (id)');
        $this->addSql('ALTER TABLE bet ADD CONSTRAINT FK_FBF0EC9B7BBF562C FOREIGN KEY (type_of_bet_id) REFERENCES type_of_bet (id)');
        $this->addSql('ALTER TABLE bet ADD CONSTRAINT FK_FBF0EC9B71F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('ALTER TABLE competition ADD CONSTRAINT FK_B50A2CB1AC78BCF8 FOREIGN KEY (sport_id) REFERENCES sport (id)');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA77B39D312 FOREIGN KEY (competition_id) REFERENCES competition (id)');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7AC78BCF8 FOREIGN KEY (sport_id) REFERENCES sport (id)');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251ED871DC26 FOREIGN KEY (bet_id) REFERENCES bet (id)');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251E1AD5CDBF FOREIGN KEY (cart_id) REFERENCES cart (id)');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251E4C3A3BB FOREIGN KEY (payment_id) REFERENCES payment (id)');
        $this->addSql('ALTER TABLE payment ADD CONSTRAINT FK_6D28840D9D7AD51B FOREIGN KEY (type_of_payment_id) REFERENCES type_of_payment (id)');
        $this->addSql('ALTER TABLE payment ADD CONSTRAINT FK_6D28840D712520F3 FOREIGN KEY (wallet_id) REFERENCES wallet (id)');
        $this->addSql('ALTER TABLE payment ADD CONSTRAINT FK_6D28840DE2FDC827 FOREIGN KEY (website_wallet_id) REFERENCES website_wallet (id)');
        $this->addSql('ALTER TABLE player ADD CONSTRAINT FK_98197A65296CD8AE FOREIGN KEY (team_id) REFERENCES team (id)');
        $this->addSql('ALTER TABLE player ADD CONSTRAINT FK_98197A65AC78BCF8 FOREIGN KEY (sport_id) REFERENCES sport (id)');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE team ADD CONSTRAINT FK_C4E0A61FAC78BCF8 FOREIGN KEY (sport_id) REFERENCES sport (id)');
        $this->addSql('ALTER TABLE team_event ADD CONSTRAINT FK_FCA843E7296CD8AE FOREIGN KEY (team_id) REFERENCES team (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE team_event ADD CONSTRAINT FK_FCA843E771F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649F5B7AF75 FOREIGN KEY (address_id) REFERENCES address (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64912CB990C FOREIGN KEY (bank_account_id) REFERENCES bank_account (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6491AD5CDBF FOREIGN KEY (cart_id) REFERENCES cart (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649712520F3 FOREIGN KEY (wallet_id) REFERENCES wallet (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64921244D12 FOREIGN KEY (card_id_file_id) REFERENCES card_id_file (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649F5B7AF75');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64912CB990C');
        $this->addSql('ALTER TABLE bank_account DROP FOREIGN KEY FK_53A23E0AA5785F28');
        $this->addSql('ALTER TABLE item DROP FOREIGN KEY FK_1F1B251ED871DC26');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64921244D12');
        $this->addSql('ALTER TABLE item DROP FOREIGN KEY FK_1F1B251E1AD5CDBF');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6491AD5CDBF');
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA77B39D312');
        $this->addSql('ALTER TABLE bet DROP FOREIGN KEY FK_FBF0EC9B71F7E88B');
        $this->addSql('ALTER TABLE team_event DROP FOREIGN KEY FK_FCA843E771F7E88B');
        $this->addSql('ALTER TABLE item DROP FOREIGN KEY FK_1F1B251E4C3A3BB');
        $this->addSql('ALTER TABLE competition DROP FOREIGN KEY FK_B50A2CB1AC78BCF8');
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA7AC78BCF8');
        $this->addSql('ALTER TABLE player DROP FOREIGN KEY FK_98197A65AC78BCF8');
        $this->addSql('ALTER TABLE team DROP FOREIGN KEY FK_C4E0A61FAC78BCF8');
        $this->addSql('ALTER TABLE player DROP FOREIGN KEY FK_98197A65296CD8AE');
        $this->addSql('ALTER TABLE team_event DROP FOREIGN KEY FK_FCA843E7296CD8AE');
        $this->addSql('ALTER TABLE bet DROP FOREIGN KEY FK_FBF0EC9B7BBF562C');
        $this->addSql('ALTER TABLE payment DROP FOREIGN KEY FK_6D28840D9D7AD51B');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('ALTER TABLE payment DROP FOREIGN KEY FK_6D28840D712520F3');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649712520F3');
        $this->addSql('ALTER TABLE payment DROP FOREIGN KEY FK_6D28840DE2FDC827');
        $this->addSql('DROP TABLE address');
        $this->addSql('DROP TABLE bank_account');
        $this->addSql('DROP TABLE bank_account_file');
        $this->addSql('DROP TABLE bet');
        $this->addSql('DROP TABLE card_id_file');
        $this->addSql('DROP TABLE cart');
        $this->addSql('DROP TABLE competition');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE item');
        $this->addSql('DROP TABLE payment');
        $this->addSql('DROP TABLE player');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('DROP TABLE sport');
        $this->addSql('DROP TABLE team');
        $this->addSql('DROP TABLE team_event');
        $this->addSql('DROP TABLE type_of_bet');
        $this->addSql('DROP TABLE type_of_payment');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE wallet');
        $this->addSql('DROP TABLE website_wallet');
    }
}
