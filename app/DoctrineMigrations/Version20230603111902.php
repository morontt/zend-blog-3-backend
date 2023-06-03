<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230603111902 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE commentators ADD force_image TINYINT(1) DEFAULT NULL');
        $this->addSql('UPDATE commentators SET force_image = 0');
        $this->addSql('ALTER TABLE commentators CHANGE force_image force_image TINYINT(1) DEFAULT 0 NOT NULL');
        $this->addSql('DROP INDEX UNIQ_6AB0FB33E46417AB ON commentators');
        $this->addSql('ALTER TABLE commentators DROP disqus_id, DROP email_hash');
        $this->addSql('ALTER TABLE users DROP email_hash');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE users ADD email_hash VARCHAR(32) DEFAULT NULL');
        $this->addSql('ALTER TABLE commentators ADD disqus_id BIGINT DEFAULT NULL, ADD email_hash VARCHAR(32) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6AB0FB33E46417AB ON commentators (disqus_id)');
        $this->addSql('ALTER TABLE commentators DROP force_image');
    }
}
