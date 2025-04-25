<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230609090837 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tracking_agent ADD is_bot TINYINT(1) DEFAULT 0 NOT NULL AFTER bot_filter');
        $this->addSql('UPDATE tracking_agent SET is_bot = (1 - bot_filter)');
        $this->addSql('ALTER TABLE tracking CHANGE timestamp_created timestamp_created INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tracking_agent DROP is_bot');
        $this->addSql('ALTER TABLE tracking CHANGE timestamp_created timestamp_created INT NOT NULL');
    }
}
