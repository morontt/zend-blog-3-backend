<?php

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160511221425 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE UNIQUE INDEX UNIQ_6AB0FB33E46417AB ON commentators (disqus_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5F9E962AE46417AB ON comments (disqus_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_885DBAFAC71A1E10 ON posts (disqus_thread)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX UNIQ_6AB0FB33E46417AB ON commentators');
        $this->addSql('DROP INDEX UNIQ_5F9E962AE46417AB ON comments');
        $this->addSql('DROP INDEX UNIQ_885DBAFAC71A1E10 ON posts');
    }
}
