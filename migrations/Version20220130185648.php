<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220130185648 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE media_file CHANGE time_created time_created DATETIME(3) DEFAULT NOW(3) NOT NULL COMMENT \'(DC2Type:milliseconds_dt)\', CHANGE last_update last_update DATETIME(3) DEFAULT NOW(3) NOT NULL COMMENT \'(DC2Type:milliseconds_dt)\'');
        $this->addSql('ALTER TABLE posts CHANGE time_created time_created DATETIME(3) DEFAULT NOW(3) NOT NULL COMMENT \'(DC2Type:milliseconds_dt)\', CHANGE last_update last_update DATETIME(3) DEFAULT NOW(3) NOT NULL COMMENT \'(DC2Type:milliseconds_dt)\'');
        $this->addSql('ALTER TABLE comments CHANGE time_created time_created DATETIME(3) DEFAULT NOW(3) NOT NULL COMMENT \'(DC2Type:milliseconds_dt)\', CHANGE last_update last_update DATETIME(3) DEFAULT NOW(3) NOT NULL COMMENT \'(DC2Type:milliseconds_dt)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE comments CHANGE time_created time_created DATETIME(3) NOT NULL COMMENT \'(DC2Type:milliseconds_dt)\', CHANGE last_update last_update DATETIME(3) DEFAULT NULL COMMENT \'(DC2Type:milliseconds_dt)\'');
        $this->addSql('ALTER TABLE media_file CHANGE time_created time_created DATETIME(3) NOT NULL COMMENT \'(DC2Type:milliseconds_dt)\', CHANGE last_update last_update DATETIME(3) DEFAULT NULL COMMENT \'(DC2Type:milliseconds_dt)\'');
        $this->addSql('ALTER TABLE posts CHANGE time_created time_created DATETIME(3) NOT NULL COMMENT \'(DC2Type:milliseconds_dt)\', CHANGE last_update last_update DATETIME(3) DEFAULT NULL COMMENT \'(DC2Type:milliseconds_dt)\'');
    }
}
