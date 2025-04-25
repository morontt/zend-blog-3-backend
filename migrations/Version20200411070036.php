<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200411070036 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tracking CHANGE time_created time_created DATETIME(3) NOT NULL COMMENT \'(DC2Type:milliseconds_dt)\'');
        $this->addSql('ALTER TABLE geo_location CHANGE time_created time_created DATETIME(3) NOT NULL COMMENT \'(DC2Type:milliseconds_dt)\'');
        $this->addSql('ALTER TABLE tracking_agent CHANGE created_at created_at DATETIME(3) DEFAULT NULL COMMENT \'(DC2Type:milliseconds_dt)\'');
        $this->addSql('ALTER TABLE tracking_archive CHANGE time_created time_created DATETIME(3) NOT NULL COMMENT \'(DC2Type:milliseconds_dt)\'');
        $this->addSql('ALTER TABLE geo_location_city CHANGE time_created time_created DATETIME(3) NOT NULL COMMENT \'(DC2Type:milliseconds_dt)\'');
        $this->addSql('ALTER TABLE geo_location_country CHANGE time_created time_created DATETIME(3) NOT NULL COMMENT \'(DC2Type:milliseconds_dt)\'');
        $this->addSql('ALTER TABLE users CHANGE time_created time_created DATETIME(3) NOT NULL COMMENT \'(DC2Type:milliseconds_dt)\', CHANGE last_login last_login DATETIME(3) DEFAULT NULL COMMENT \'(DC2Type:milliseconds_dt)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE geo_location CHANGE time_created time_created DATETIME NOT NULL');
        $this->addSql('ALTER TABLE geo_location_city CHANGE time_created time_created DATETIME NOT NULL');
        $this->addSql('ALTER TABLE geo_location_country CHANGE time_created time_created DATETIME NOT NULL');
        $this->addSql('ALTER TABLE tracking CHANGE time_created time_created DATETIME NOT NULL');
        $this->addSql('ALTER TABLE tracking_agent CHANGE created_at created_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE tracking_archive CHANGE time_created time_created DATETIME NOT NULL');
        $this->addSql('ALTER TABLE users CHANGE time_created time_created DATETIME NOT NULL, CHANGE last_login last_login DATETIME DEFAULT NULL');
    }
}
