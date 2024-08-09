<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240809184435 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE users ADD gender SMALLINT DEFAULT 1 NOT NULL COMMENT \'1: male, 2: female\'');
        $this->addSql('ALTER TABLE user_extra_info ADD user_agent_id INT DEFAULT NULL, ADD ip_long INT UNSIGNED DEFAULT NULL, ADD ip_addr VARCHAR(15) DEFAULT NULL');
        $this->addSql('ALTER TABLE user_extra_info ADD CONSTRAINT FK_F89334A5D499950B FOREIGN KEY (user_agent_id) REFERENCES tracking_agent (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE user_extra_info ADD CONSTRAINT FK_F89334A528F0F5E7 FOREIGN KEY (ip_long) REFERENCES geo_location (ip_long) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_F89334A5D499950B ON user_extra_info (user_agent_id)');
        $this->addSql('CREATE INDEX IDX_F89334A528F0F5E7 ON user_extra_info (ip_long)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user_extra_info DROP FOREIGN KEY FK_F89334A5D499950B');
        $this->addSql('ALTER TABLE user_extra_info DROP FOREIGN KEY FK_F89334A528F0F5E7');
        $this->addSql('DROP INDEX IDX_F89334A5D499950B ON user_extra_info');
        $this->addSql('DROP INDEX IDX_F89334A528F0F5E7 ON user_extra_info');
        $this->addSql('ALTER TABLE user_extra_info DROP user_agent_id, DROP ip_long, DROP ip_addr');
        $this->addSql('ALTER TABLE users DROP gender');
    }
}
