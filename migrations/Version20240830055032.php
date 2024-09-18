<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240830055032 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE subscription_settings (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(64) NOT NULL, block_sending TINYINT(1) DEFAULT 0 NOT NULL, subs_type SMALLINT DEFAULT 1 NOT NULL COMMENT \'1: reply\', time_created DATETIME(3) DEFAULT CURRENT_TIMESTAMP(3) NOT NULL COMMENT \'(DC2Type:milliseconds_dt)\', last_update DATETIME(3) DEFAULT CURRENT_TIMESTAMP(3) NOT NULL COMMENT \'(DC2Type:milliseconds_dt)\', UNIQUE INDEX UNIQ_82EBFFE9E7927C744DD186C6 (email, subs_type), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE subscription_settings');
    }
}
