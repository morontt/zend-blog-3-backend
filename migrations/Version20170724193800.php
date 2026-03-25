<?php

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170724193800 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('SELECT NOW()'); // Hi phantom :)
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP VIEW `v_comments`');
        $this->addSql('DROP VIEW `v_commentators`');
    }

    /**
     * @param Schema $schema
     */
    public function postUp(Schema $schema): void
    {
        parent::postUp($schema);

        $sql1 = file_get_contents(__DIR__ . '/sql/view_comments_01.sql');
        $sql2 = file_get_contents(__DIR__ . '/sql/view_commentators_01.sql');

        $stmt = $this->connection->prepare($sql1);
        $stmt->executeQuery();

        $this->write('     <comment>-></comment> CREATE VIEW `v_comments`');

        $stmt = $this->connection->prepare($sql2);
        $stmt->executeQuery();

        $this->write('     <comment>-></comment> CREATE VIEW `v_commentators`');
    }
}
