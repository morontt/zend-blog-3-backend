<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250506220156 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE lj_posts (id INT AUTO_INCREMENT NOT NULL, post_id INT NOT NULL, lj_item_id INT NOT NULL, UNIQUE INDEX UNIQ_7E078EF3E219EBF8 (lj_item_id), UNIQUE INDEX UNIQ_7E078EF34B89032C (post_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE lj_posts ADD CONSTRAINT FK_7E078EF34B89032C FOREIGN KEY (post_id) REFERENCES posts (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE lj_posts DROP FOREIGN KEY FK_7E078EF34B89032C');
        $this->addSql('DROP TABLE lj_posts');
    }
}
