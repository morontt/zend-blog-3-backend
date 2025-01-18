<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250118091212 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commentators ADD time_created DATETIME(3) DEFAULT CURRENT_TIMESTAMP(3) NOT NULL, ADD last_update DATETIME(3) DEFAULT CURRENT_TIMESTAMP(3) NOT NULL');
        $this->addSql(<<<SQL
CREATE TEMPORARY TABLE `commentators_temp` (
  `id` int unsigned NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
);
SQL
        );

        $this->addSql(<<<SQL
INSERT INTO commentators_temp (id, created_at)
SELECT
  c.id,
  MIN(ci.time_created) AS tar_time_created
FROM `commentators` AS c
INNER JOIN comments AS ci ON c.id = ci.commentator_id
GROUP BY c.id
SQL
        );

        $this->addSql(<<<SQL
UPDATE commentators, commentators_temp
SET
    commentators.time_created = commentators_temp.created_at,
    commentators.last_update = commentators_temp.created_at
WHERE
  commentators.id = commentators_temp.id
SQL
        );

        $this->addSql('DELETE FROM commentators WHERE id NOT IN (SELECT id FROM commentators_temp)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commentators DROP time_created, DROP last_update');
    }
}
