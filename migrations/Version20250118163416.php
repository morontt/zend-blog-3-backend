<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250118163416 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('DROP VIEW IF EXISTS `v_commentators`');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP VIEW IF EXISTS `v_commentators`');
    }

    /**
     * @param Schema $schema
     */
    public function postUp(Schema $schema): void
    {
        parent::postUp($schema);

        $sql = file_get_contents(__DIR__ . '/sql/view_commentators_07.sql');

        $stmt = $this->connection->prepare($sql);
        $stmt->executeQuery();

        $this->write('     <comment>-></comment> CREATE VIEW `v_commentators`');
    }

    /**
     * @param Schema $schema
     */
    public function postDown(Schema $schema): void
    {
        parent::postDown($schema);

        $sql = file_get_contents(__DIR__ . '/sql/view_commentators_06.sql');

        $stmt = $this->connection->prepare($sql);
        $stmt->executeQuery();

        $this->write('     <comment>-></comment> CREATE VIEW `v_commentators`');
    }
}
