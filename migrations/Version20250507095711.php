<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250507095711 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comments ADD force_created_at DATETIME(3) DEFAULT NULL');
        $this->addSql('ALTER TABLE posts ADD force_created_at DATETIME(3) DEFAULT NULL, ADD timestamp_sort INT UNSIGNED DEFAULT NULL');
        $this->addSql('CREATE INDEX IDX_885DBAFABB709B14 ON posts (timestamp_sort)');
        $this->addSql('UPDATE posts SET timestamp_sort = CAST(UNIX_TIMESTAMP(time_created) AS UNSIGNED)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comments DROP force_created_at');
        $this->addSql('DROP INDEX IDX_885DBAFABB709B14 ON posts');
        $this->addSql('ALTER TABLE posts DROP force_created_at, DROP timestamp_sort');
    }
}
