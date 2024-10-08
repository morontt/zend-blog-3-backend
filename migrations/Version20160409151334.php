<?php

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160409151334 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE media_file (id INT AUTO_INCREMENT NOT NULL, post_id INT DEFAULT NULL, path VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, file_size INT NOT NULL, default_image TINYINT(1) NOT NULL, backuped TINYINT(1) NOT NULL, time_created DATETIME NOT NULL, last_update DATETIME NOT NULL, UNIQUE INDEX UNIQ_4FD8E9C3B548B0F (path), INDEX IDX_4FD8E9C34B89032C (post_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE media_file ADD CONSTRAINT FK_4FD8E9C34B89032C FOREIGN KEY (post_id) REFERENCES posts (id) ON DELETE SET NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE media_file');
    }
}
