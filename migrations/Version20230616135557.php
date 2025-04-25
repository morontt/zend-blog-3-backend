<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230616135557 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE geo_location ADD ip_long INT UNSIGNED NOT NULL AFTER id, CHANGE city_id city_id INT DEFAULT NULL');
        $this->addSql('UPDATE geo_location SET ip_long = INET_ATON(ip_addr)');
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962AC34F22E');
        $this->addSql('DROP INDEX IDX_5F9E962AC34F22E ON comments');
        $this->addSql('ALTER TABLE comments DROP geo_location_id');
        $this->addSql('ALTER TABLE geo_location MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE geo_location DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE geo_location CHANGE id id INT NOT NULL');
        $this->addSql('ALTER TABLE geo_location ADD PRIMARY KEY (ip_long)');
        $this->addSql('ALTER TABLE geo_location DROP id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE geo_location ADD id INT NOT NULL');
        $this->addSql('ALTER TABLE geo_location DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE geo_location ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE comments ADD geo_location_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962AC34F22E FOREIGN KEY (geo_location_id) REFERENCES geo_location (id) ON UPDATE NO ACTION ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_5F9E962AC34F22E ON comments (geo_location_id)');
        $this->addSql('ALTER TABLE geo_location DROP ip_long, CHANGE city_id city_id INT NOT NULL');
    }
}
