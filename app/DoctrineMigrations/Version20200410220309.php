<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200410220309 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE comments CHANGE time_created time_created DATETIME(3) NOT NULL COMMENT \'(DC2Type:milliseconds_dt)\', CHANGE last_update last_update DATETIME(3) DEFAULT NULL COMMENT \'(DC2Type:milliseconds_dt)\'');
        $this->addSql('ALTER TABLE media_file CHANGE time_created time_created DATETIME(3) NOT NULL COMMENT \'(DC2Type:milliseconds_dt)\', CHANGE last_update last_update DATETIME(3) DEFAULT NULL COMMENT \'(DC2Type:milliseconds_dt)\'');
        $this->addSql('ALTER TABLE posts CHANGE time_created time_created DATETIME(3) NOT NULL COMMENT \'(DC2Type:milliseconds_dt)\', CHANGE last_update last_update DATETIME(3) DEFAULT NULL COMMENT \'(DC2Type:milliseconds_dt)\'');

        $this->addSql('UPDATE posts SET time_created = DATE_ADD(time_created, INTERVAL TRUNCATE(RAND() * 999, 0) * 1000 MICROSECOND)');
        $this->addSql('UPDATE comments SET time_created = DATE_ADD(time_created, INTERVAL TRUNCATE(RAND() * 999, 0) * 1000 MICROSECOND)');
        $this->addSql('UPDATE media_file SET time_created = DATE_ADD(time_created, INTERVAL TRUNCATE(RAND() * 999, 0) * 1000 MICROSECOND)');
        $this->addSql('UPDATE posts SET last_update = DATE_ADD(last_update, INTERVAL TRUNCATE(RAND() * 999, 0) * 1000 MICROSECOND) WHERE last_update IS NOT NULL');
        $this->addSql('UPDATE comments SET last_update = DATE_ADD(last_update, INTERVAL TRUNCATE(RAND() * 999, 0) * 1000 MICROSECOND) WHERE last_update IS NOT NULL');
        $this->addSql('UPDATE media_file SET last_update = DATE_ADD(last_update, INTERVAL TRUNCATE(RAND() * 999, 0) * 1000 MICROSECOND) WHERE last_update IS NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE comments CHANGE time_created time_created DATETIME NOT NULL, CHANGE last_update last_update DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE media_file CHANGE time_created time_created DATETIME NOT NULL, CHANGE last_update last_update DATETIME NOT NULL');
        $this->addSql('ALTER TABLE posts CHANGE time_created time_created DATETIME NOT NULL, CHANGE last_update last_update DATETIME DEFAULT NULL');
    }
}
