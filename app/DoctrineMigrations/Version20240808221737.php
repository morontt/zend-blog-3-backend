<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240808221737 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE user_extra_info (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, external_id VARCHAR(64) NOT NULL, data_provider VARCHAR(16) NOT NULL, username VARCHAR(64) DEFAULT NULL, display_name VARCHAR(64) DEFAULT NULL, first_name VARCHAR(64) DEFAULT NULL, last_name VARCHAR(64) DEFAULT NULL, gender SMALLINT DEFAULT 1 NOT NULL COMMENT \'1: male, 2: female, 3: n/a\', email VARCHAR(64) DEFAULT NULL, avatar VARCHAR(64) DEFAULT NULL, raw_data TEXT NOT NULL, time_created DATETIME(3) DEFAULT CURRENT_TIMESTAMP(3) NOT NULL COMMENT \'(DC2Type:milliseconds_dt)\', INDEX IDX_F89334A5A76ED395 (user_id), UNIQUE INDEX UNIQ_F89334A59F75D7B0581ABA40 (external_id, data_provider), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_extra_info ADD CONSTRAINT FK_F89334A5A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE users ADD display_name VARCHAR(64) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE user_extra_info');
        $this->addSql('ALTER TABLE users DROP display_name');
    }
}
