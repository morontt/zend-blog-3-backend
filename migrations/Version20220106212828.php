<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220106212828 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE INDEX left_key_idx ON comments (tree_left_key)');
        $this->addSql('CREATE INDEX right_key_idx ON comments (tree_right_key)');
        $this->addSql('CREATE INDEX left_key_idx ON category (tree_left_key)');
        $this->addSql('CREATE INDEX right_key_idx ON category (tree_right_key)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX left_key_idx ON category');
        $this->addSql('DROP INDEX right_key_idx ON category');
        $this->addSql('DROP INDEX left_key_idx ON comments');
        $this->addSql('DROP INDEX right_key_idx ON comments');
    }
}
