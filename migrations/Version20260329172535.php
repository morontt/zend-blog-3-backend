<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260329172535 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE media_file CHANGE default_image default_image TINYINT(1) DEFAULT 0 NOT NULL, CHANGE backed_up backed_up TINYINT(1) DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE posts CHANGE hide hide TINYINT(1) DEFAULT 0 NOT NULL, CHANGE comments_count comments_count INT DEFAULT 0 NOT NULL, CHANGE views_count views_count INT DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE subscription_settings CHANGE subs_type subs_type SMALLINT DEFAULT 0 NOT NULL COMMENT \'0: none, 1: reply, 2: system\'');
        $this->addSql('ALTER TABLE sys_parameters CHANGE encrypted encrypted TINYINT(1) DEFAULT 0 NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE media_file CHANGE default_image default_image TINYINT(1) NOT NULL, CHANGE backed_up backed_up TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE posts CHANGE hide hide TINYINT(1) NOT NULL, CHANGE comments_count comments_count INT NOT NULL, CHANGE views_count views_count INT NOT NULL');
        $this->addSql('ALTER TABLE subscription_settings CHANGE subs_type subs_type SMALLINT DEFAULT 1 NOT NULL COMMENT \'0: none, 1: reply, 2: system\'');
        $this->addSql('ALTER TABLE sys_parameters CHANGE encrypted encrypted TINYINT(1) NOT NULL');
    }
}
