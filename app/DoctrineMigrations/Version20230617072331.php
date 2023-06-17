<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230617072331 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tracking ADD ip_long INT UNSIGNED DEFAULT NULL AFTER ip_addr');
        $this->addSql('ALTER TABLE tracking_archive ADD ip_long INT UNSIGNED DEFAULT NULL AFTER ip_addr');
        $this->addSql('UPDATE tracking AS t, geo_location AS g SET t.ip_long = g.ip_long WHERE t.ip_addr IS NOT NULL AND g.ip_long = INET_ATON(t.ip_addr)');
        $this->addSql('ALTER TABLE tracking ADD CONSTRAINT FK_A87C621C28F0F5E7 FOREIGN KEY (ip_long) REFERENCES geo_location (ip_long) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_A87C621C28F0F5E7 ON tracking (ip_long)');
        $this->addSql("UPDATE tracking_archive AS t, geo_location AS g SET t.ip_long = g.ip_long WHERE t.ip_addr IS NOT NULL AND REGEXP_LIKE(t.ip_addr, '^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$') AND g.ip_long = INET_ATON(t.ip_addr)");
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tracking DROP FOREIGN KEY FK_A87C621C28F0F5E7');
        $this->addSql('DROP INDEX IDX_A87C621C28F0F5E7 ON tracking');
        $this->addSql('ALTER TABLE tracking DROP ip_long');
        $this->addSql('ALTER TABLE tracking_archive DROP ip_long');
    }
}
