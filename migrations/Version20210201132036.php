<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210201132036 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE competition_sport');
        $this->addSql('ALTER TABLE competition ADD sport_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE competition ADD CONSTRAINT FK_B50A2CB1AC78BCF8 FOREIGN KEY (sport_id) REFERENCES sport (id)');
        $this->addSql('CREATE INDEX IDX_B50A2CB1AC78BCF8 ON competition (sport_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE competition_sport (competition_id INT NOT NULL, sport_id INT NOT NULL, INDEX IDX_E932DD047B39D312 (competition_id), INDEX IDX_E932DD04AC78BCF8 (sport_id), PRIMARY KEY(competition_id, sport_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE competition_sport ADD CONSTRAINT FK_E932DD047B39D312 FOREIGN KEY (competition_id) REFERENCES competition (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE competition_sport ADD CONSTRAINT FK_E932DD04AC78BCF8 FOREIGN KEY (sport_id) REFERENCES sport (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE competition DROP FOREIGN KEY FK_B50A2CB1AC78BCF8');
        $this->addSql('DROP INDEX IDX_B50A2CB1AC78BCF8 ON competition');
        $this->addSql('ALTER TABLE competition DROP sport_id');
    }
}
