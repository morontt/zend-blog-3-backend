<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260329152921 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('DROP VIEW IF EXISTS `v_tags`');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP VIEW IF EXISTS `v_tags`');
    }

    public function postUp(Schema $schema): void
    {
        parent::postUp($schema);

        $sql = file_get_contents(__DIR__ . '/sql/view_tags_01.sql');

        $stmt = $this->connection->prepare($sql);
        $stmt->executeQuery();

        $this->write('     <comment>-></comment> CREATE VIEW `v_tags`');
    }
}
