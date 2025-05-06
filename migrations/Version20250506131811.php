<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250506131811 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE lj_comment_meta (id INT AUTO_INCREMENT NOT NULL, commentator_id INT DEFAULT NULL, user_id INT DEFAULT NULL, lj_name VARCHAR(32) NOT NULL, poster_id INT NOT NULL, UNIQUE INDEX UNIQ_2A1928A0BD312CD3 (lj_name), UNIQUE INDEX UNIQ_2A1928A05BB66C05 (poster_id), INDEX IDX_2A1928A0506AFCC0 (commentator_id), INDEX IDX_2A1928A0A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE lj_comment_meta ADD CONSTRAINT FK_2A1928A0506AFCC0 FOREIGN KEY (commentator_id) REFERENCES commentators (id)');
        $this->addSql('ALTER TABLE lj_comment_meta ADD CONSTRAINT FK_2A1928A0A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE lj_comment_meta DROP FOREIGN KEY FK_2A1928A0506AFCC0');
        $this->addSql('ALTER TABLE lj_comment_meta DROP FOREIGN KEY FK_2A1928A0A76ED395');
        $this->addSql('DROP TABLE lj_comment_meta');
    }
}
