<?php

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160402200756 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tracking DROP FOREIGN KEY FK_A87C621C4B89032C');
        $this->addSql('ALTER TABLE tracking ADD CONSTRAINT FK_A87C621C4B89032C FOREIGN KEY (post_id) REFERENCES posts (id) ON DELETE CASCADE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tracking DROP FOREIGN KEY FK_A87C621C4B89032C');
        $this->addSql('ALTER TABLE tracking ADD CONSTRAINT FK_A87C621C4B89032C FOREIGN KEY (post_id) REFERENCES posts (id)');
    }
}
