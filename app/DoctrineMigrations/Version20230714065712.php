<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230714065712 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE media_file ADD picture_tag TEXT DEFAULT NULL');
        $this->addSql('DROP INDEX UNIQ_885DBAFAC71A1E10 ON posts');
        $this->addSql('ALTER TABLE posts DROP disqus_thread');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE media_file DROP picture_tag');
        $this->addSql('ALTER TABLE posts ADD disqus_thread BIGINT DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_885DBAFAC71A1E10 ON posts (disqus_thread)');
    }
}
