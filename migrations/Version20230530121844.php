<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230530121844 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tracking ADD is_cdn TINYINT(1) DEFAULT NULL, ADD request_uri VARCHAR(128) DEFAULT NULL');
        $this->addSql('UPDATE tracking SET is_cdn = 0');
        $this->addSql('ALTER TABLE tracking CHANGE is_cdn is_cdn TINYINT(1) DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE tracking ADD status_code SMALLINT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tracking DROP is_cdn, DROP request_uri');
    }
}
