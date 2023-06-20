<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230620154440 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE telegram_users (id INT AUTO_INCREMENT NOT NULL, user_id BIGINT NOT NULL, is_bot TINYINT(1) NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) DEFAULT NULL, username VARCHAR(255) DEFAULT NULL, lang VARCHAR(8) DEFAULT NULL, raw_message TEXT NOT NULL, time_created DATETIME(3) DEFAULT CURRENT_TIMESTAMP(3) NOT NULL COMMENT \'(DC2Type:milliseconds_dt)\', last_update DATETIME(3) DEFAULT CURRENT_TIMESTAMP(3) NOT NULL COMMENT \'(DC2Type:milliseconds_dt)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE telegram_updates (id INT AUTO_INCREMENT NOT NULL, telegram_user_id INT DEFAULT NULL, chat_id BIGINT DEFAULT NULL, raw_message TEXT NOT NULL, time_created DATETIME(3) DEFAULT CURRENT_TIMESTAMP(3) NOT NULL COMMENT \'(DC2Type:milliseconds_dt)\', last_update DATETIME(3) DEFAULT CURRENT_TIMESTAMP(3) NOT NULL COMMENT \'(DC2Type:milliseconds_dt)\', INDEX IDX_1E0E72C6FC28B263 (telegram_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE telegram_updates ADD CONSTRAINT FK_1E0E72C6FC28B263 FOREIGN KEY (telegram_user_id) REFERENCES telegram_users (id) ON DELETE SET NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE telegram_updates DROP FOREIGN KEY FK_1E0E72C6FC28B263');
        $this->addSql('DROP TABLE telegram_users');
        $this->addSql('DROP TABLE telegram_updates');
    }
}
