<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230616153217 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX UNIQ_5F9E962AE46417AB ON comments');
        $this->addSql('ALTER TABLE comments ADD ip_long INT UNSIGNED DEFAULT NULL AFTER ip_addr, DROP disqus_id');
        $this->addSql('UPDATE comments AS c, geo_location AS g SET c.ip_long = g.ip_long WHERE c.ip_addr IS NOT NULL AND g.ip_long = INET_ATON(c.ip_addr)');
        $this->addSql('CREATE INDEX IDX_5F9E962A28F0F5E7 ON comments (ip_long)');
        $this->addSql('ALTER TABLE geo_location ADD count_of_check SMALLINT UNSIGNED DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962A28F0F5E7 FOREIGN KEY (ip_long) REFERENCES geo_location (ip_long) ON DELETE SET NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962A28F0F5E7');
        $this->addSql('ALTER TABLE geo_location DROP count_of_check');
        $this->addSql('DROP INDEX IDX_5F9E962A28F0F5E7 ON comments');
        $this->addSql('ALTER TABLE comments ADD disqus_id BIGINT DEFAULT NULL, DROP ip_long');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5F9E962AE46417AB ON comments (disqus_id)');
    }
}
