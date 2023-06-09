<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230609101954 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql(<<<SQL
CREATE TABLE `tracking_agent_temp` (
  `id` int unsigned NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE InnoDB;
SQL
        );

        $this->addSql(<<<SQL
INSERT INTO tracking_agent_temp (id, created_at)
SELECT
  ta.id,
  MIN(tar.time_created) AS tar_time_created
FROM `tracking_agent` AS ta
LEFT JOIN tracking_archive AS tar ON ta.id = tar.user_agent_id
WHERE ta.created_at IS NULL
GROUP BY ta.id
SQL
        );

        $this->addSql(<<<SQL
UPDATE tracking_agent, tracking_agent_temp
SET tracking_agent.created_at = tracking_agent_temp.created_at
WHERE
    tracking_agent.created_at IS NULL
    AND tracking_agent.id = tracking_agent_temp.id
SQL
        );

        $this->addSql('DROP TABLE tracking_agent_temp');
        $this->addSql('ALTER TABLE tracking_agent CHANGE created_at created_at DATETIME(3) DEFAULT CURRENT_TIMESTAMP(3) NOT NULL COMMENT \'(DC2Type:milliseconds_dt)\'');
        $this->addSql('ALTER TABLE comments DROP last_update_copy');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE comments ADD last_update_copy DATETIME(3) DEFAULT NULL COMMENT \'(DC2Type:milliseconds_dt)\'');
        $this->addSql('ALTER TABLE tracking_agent CHANGE created_at created_at DATETIME(3) DEFAULT NULL COMMENT \'(DC2Type:milliseconds_dt)\'');
    }
}
