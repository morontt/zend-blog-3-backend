<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20141026012219 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        
        $this->addSql('ALTER TABLE commentators CHANGE disqus_id disqus_id BIGINT DEFAULT NULL');
        $this->addSql('ALTER TABLE comments CHANGE disqus_id disqus_id BIGINT DEFAULT NULL');
        $this->addSql('ALTER TABLE posts CHANGE disqus_thread disqus_thread BIGINT DEFAULT NULL');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        
        $this->addSql('ALTER TABLE commentators CHANGE disqus_id disqus_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE comments CHANGE disqus_id disqus_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE posts CHANGE disqus_thread disqus_thread INT DEFAULT NULL');
    }
}
