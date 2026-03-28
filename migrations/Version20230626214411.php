<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230626214411 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commentators ADD gender SMALLINT DEFAULT 1 NOT NULL COMMENT \'1: male, 2: female\', DROP force_image');
        $this->addSql('DROP VIEW IF EXISTS `v_commentators`');
        $this->addSql('DROP VIEW IF EXISTS `v_comments`');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commentators ADD force_image TINYINT(1) DEFAULT 0 NOT NULL, DROP gender');
        $this->addSql('DROP VIEW IF EXISTS `v_commentators`');
        $this->addSql('DROP VIEW IF EXISTS `v_comments`');
    }

    /**
     * @param Schema $schema
     */
    public function postUp(Schema $schema): void
    {
        parent::postUp($schema);

        $sql = file_get_contents(__DIR__ . '/sql/view_commentators_04.sql');

        $stmt = $this->connection->prepare($sql);
        $stmt->executeQuery();

        $this->write('     <comment>-></comment> CREATE VIEW `v_commentators`');

        $sql = file_get_contents(__DIR__ . '/sql/view_comments_07.sql');

        $stmt = $this->connection->prepare($sql);
        $stmt->executeQuery();

        $this->write('     <comment>-></comment> CREATE VIEW `v_comments`');
    }

    /**
     * @param Schema $schema
     */
    public function postDown(Schema $schema): void
    {
        parent::postDown($schema);

        $sql = file_get_contents(__DIR__ . '/sql/view_commentators_03.sql');

        $stmt = $this->connection->prepare($sql);
        $stmt->executeQuery();

        $this->write('     <comment>-></comment> CREATE VIEW `v_commentators`');

        $sql = file_get_contents(__DIR__ . '/sql/view_comments_06.sql');

        $stmt = $this->connection->prepare($sql);
        $stmt->executeQuery();

        $this->write('     <comment>-></comment> CREATE VIEW `v_comments`');
    }
}
