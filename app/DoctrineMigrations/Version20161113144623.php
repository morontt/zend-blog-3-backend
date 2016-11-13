<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161113144623 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE geo_location (id INT AUTO_INCREMENT NOT NULL, city_id INT NOT NULL, ip_addr VARCHAR(15) NOT NULL, time_created DATETIME NOT NULL, UNIQUE INDEX UNIQ_B027FE6A41FDCEBA (ip_addr), INDEX IDX_B027FE6A8BAC62AF (city_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE geo_location_city (id INT AUTO_INCREMENT NOT NULL, country_id INT NOT NULL, city VARCHAR(128) NOT NULL, region VARCHAR(128) NOT NULL, latitude DOUBLE PRECISION NOT NULL, longitude DOUBLE PRECISION NOT NULL, zip VARCHAR(30) NOT NULL, time_zone VARCHAR(8) NOT NULL, time_created DATETIME NOT NULL, INDEX IDX_1C82828AF92F3E70 (country_id), UNIQUE INDEX UNIQ_1C82828A2D5B0234F62F176 (city, region), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE geo_location_country (id INT AUTO_INCREMENT NOT NULL, country_code VARCHAR(2) NOT NULL, country_name VARCHAR(64) NOT NULL, time_created DATETIME NOT NULL, UNIQUE INDEX UNIQ_583FE876F026BB7C (country_code), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE geo_location ADD CONSTRAINT FK_B027FE6A8BAC62AF FOREIGN KEY (city_id) REFERENCES geo_location_city (id) ON DELETE RESTRICT');
        $this->addSql('ALTER TABLE geo_location_city ADD CONSTRAINT FK_1C82828AF92F3E70 FOREIGN KEY (country_id) REFERENCES geo_location_country (id) ON DELETE RESTRICT');
        $this->addSql('ALTER TABLE comments ADD geo_location_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962AC34F22E FOREIGN KEY (geo_location_id) REFERENCES geo_location (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_5F9E962AC34F22E ON comments (geo_location_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962AC34F22E');
        $this->addSql('ALTER TABLE geo_location DROP FOREIGN KEY FK_B027FE6A8BAC62AF');
        $this->addSql('ALTER TABLE geo_location_city DROP FOREIGN KEY FK_1C82828AF92F3E70');
        $this->addSql('DROP TABLE geo_location');
        $this->addSql('DROP TABLE geo_location_city');
        $this->addSql('DROP TABLE geo_location_country');
        $this->addSql('DROP INDEX IDX_5F9E962AC34F22E ON comments');
        $this->addSql('ALTER TABLE comments DROP geo_location_id');
    }
}
