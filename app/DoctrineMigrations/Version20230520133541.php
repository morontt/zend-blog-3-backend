<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230520133541 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE pygments_code (id INT AUTO_INCREMENT NOT NULL, language_id INT DEFAULT NULL, source_code TEXT NOT NULL, source_html TEXT NOT NULL, time_created DATETIME(3) DEFAULT NOW(3) NOT NULL COMMENT \'(DC2Type:milliseconds_dt)\', last_update DATETIME(3) DEFAULT NOW(3) NOT NULL COMMENT \'(DC2Type:milliseconds_dt)\', INDEX IDX_FB83C7B382F1BAF4 (language_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pygments_language (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(32) NOT NULL, lexer VARCHAR(16) DEFAULT NULL, time_created DATETIME(3) DEFAULT NOW(3) NOT NULL COMMENT \'(DC2Type:milliseconds_dt)\', last_update DATETIME(3) DEFAULT NOW(3) NOT NULL COMMENT \'(DC2Type:milliseconds_dt)\', UNIQUE INDEX UNIQ_D076DE485E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE pygments_code ADD CONSTRAINT FK_FB83C7B382F1BAF4 FOREIGN KEY (language_id) REFERENCES pygments_language (id) ON DELETE SET NULL');

        $this->addSql("INSERT INTO `pygments_language` (`name`, `lexer`) VALUES
                         ('PHP', 'php'),
                         ('JavaScript', 'javascript'),
                         ('SQL', 'sql'),
                         ('MySQL', 'mysql'),
                         ('PostgreSQL', 'postgresql'),
                         ('Java', 'java'),
                         ('Python', 'python'),
                         ('Golang', 'go'),
                         ('HTML', 'html'),
                         ('XML', 'xml'),
                         ('Shell', 'sh'),
                         ('Plain Text', 'text'),
                         ('C', 'c'),
                         ('Common Lisp', 'cl'),
                         ('Clojure', 'clojure'),
                         ('Lua', 'lua'),
                         ('CoffeeScript', 'coffee-script'),
                         ('Elixir', 'elixir');");
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE pygments_code DROP FOREIGN KEY FK_FB83C7B382F1BAF4');
        $this->addSql('DROP TABLE pygments_code');
        $this->addSql('DROP TABLE pygments_language');
    }
}
