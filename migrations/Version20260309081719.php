<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260309081719 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_login_histories (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, ip_addr VARCHAR(15) DEFAULT NULL, time_created DATETIME(3) DEFAULT CURRENT_TIMESTAMP(3) NOT NULL COMMENT \'(DC2Type:milliseconds_dt)\', INDEX IDX_BDB388D9A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_login_histories ADD CONSTRAINT FK_BDB388D9A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE users DROP last_login, DROP login_count, DROP ip_last');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_login_histories DROP FOREIGN KEY FK_BDB388D9A76ED395');
        $this->addSql('DROP TABLE user_login_histories');
        $this->addSql('ALTER TABLE users ADD last_login DATETIME DEFAULT NULL, ADD login_count INT NOT NULL, ADD ip_last VARCHAR(15) DEFAULT NULL');
    }
}
