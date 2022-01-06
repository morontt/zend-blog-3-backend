<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211219205322 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE comments ADD tree_left_key INT UNSIGNED DEFAULT NULL, ADD tree_right_key INT UNSIGNED DEFAULT NULL, ADD tree_depth INT UNSIGNED DEFAULT 1 NOT NULL');
        $this->addSql('ALTER TABLE category ADD tree_left_key INT UNSIGNED DEFAULT NULL, ADD tree_right_key INT UNSIGNED DEFAULT NULL, ADD tree_depth INT UNSIGNED DEFAULT 1 NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE category DROP tree_left_key, DROP tree_right_key, DROP tree_depth');
        $this->addSql('ALTER TABLE comments DROP tree_left_key, DROP tree_right_key, DROP tree_depth');
    }
}
