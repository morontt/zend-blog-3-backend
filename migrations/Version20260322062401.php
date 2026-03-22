<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260322062401 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE subscription_settings CHANGE subs_type subs_type SMALLINT DEFAULT 1 NOT NULL COMMENT \'0: none, 1: reply, 2: system\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE subscription_settings CHANGE subs_type subs_type SMALLINT DEFAULT 1 NOT NULL COMMENT \'0: none, 1: reply\'');
    }
}
