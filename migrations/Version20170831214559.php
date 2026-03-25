<?php

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170831214559 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('SELECT NOW()');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP PROCEDURE `tracking_to_archive`');
    }

    /**
     * @param Schema $schema
     */
    public function postUp(Schema $schema): void
    {
        parent::postUp($schema);

        $sql = file_get_contents(__DIR__ . '/sql/tracking_to_archive_01.sql');

        $stmt = $this->connection->prepare($sql);
        $stmt->executeQuery();

        $this->write('     <comment>-></comment> CREATE PROCEDURE `tracking_to_archive`');
    }
}
