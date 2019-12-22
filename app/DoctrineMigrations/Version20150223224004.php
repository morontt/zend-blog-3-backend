<?php

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150223224004 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX UNIQ_CDD78530C44967C5 ON tracking_agent');
        $this->addSql('ALTER TABLE tracking_agent ADD hash VARCHAR(32) NOT NULL AFTER user_agent, CHANGE user_agent user_agent TEXT NOT NULL');
        $this->addSql('UPDATE tracking_agent SET hash = MD5(user_agent)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_CDD78530D1B862B8 ON tracking_agent (hash)');
        $this->addSql('ALTER TABLE tracking ADD timestamp_created INT NOT NULL');
        $this->addSql('UPDATE tracking SET timestamp_created = UNIX_TIMESTAMP( time_created )');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX UNIQ_CDD78530D1B862B8 ON tracking_agent');
        $this->addSql('ALTER TABLE tracking_agent DROP hash, CHANGE user_agent user_agent VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_CDD78530C44967C5 ON tracking_agent (user_agent)');
        $this->addSql('ALTER TABLE tracking DROP timestamp_created');
    }
}
