<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230603113418 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP VIEW IF EXISTS `v_commentators`');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP VIEW IF EXISTS `v_commentators`');
    }

    /**
     * @param Schema $schema
     */
    public function postUp(Schema $schema): void
    {
        parent::postUp($schema);

        $sql = file_get_contents(__DIR__ . '/sql/view_commentators_02.sql');

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

        $sql = file_get_contents(__DIR__ . '/sql/view_commentators_01.sql');

        $stmt = $this->connection->prepare($sql);
        $stmt->executeQuery();

        $this->write('     <comment>-></comment> CREATE VIEW `v_commentators`');
    }
}
