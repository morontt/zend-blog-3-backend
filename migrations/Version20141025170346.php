<?php

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20141025170346 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE avatar (id INT AUTO_INCREMENT NOT NULL, hash VARCHAR(32) NOT NULL, `default` SMALLINT NOT NULL, src VARCHAR(48) NOT NULL, last_modified DATETIME NOT NULL, UNIQUE INDEX UNIQ_1677722FD1B862B8 (hash), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, parent_id INT DEFAULT NULL, name VARCHAR(100) NOT NULL, url VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_64C19C1F47645AE (url), INDEX IDX_64C19C1727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comments (id INT AUTO_INCREMENT NOT NULL, parent_id INT DEFAULT NULL, post_id INT DEFAULT NULL, commentator_id INT DEFAULT NULL, user_id INT DEFAULT NULL, user_agent_id INT DEFAULT NULL, text LONGTEXT NOT NULL, deleted TINYINT(1) NOT NULL, ip_addr VARCHAR(15) DEFAULT NULL, time_created DATETIME NOT NULL, disqus_id INT DEFAULT NULL, INDEX IDX_5F9E962A727ACA70 (parent_id), INDEX IDX_5F9E962A4B89032C (post_id), INDEX IDX_5F9E962A506AFCC0 (commentator_id), INDEX IDX_5F9E962AA76ED395 (user_id), INDEX IDX_5F9E962AD499950B (user_agent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commentators (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(80) NOT NULL, mail VARCHAR(80) DEFAULT NULL, website VARCHAR(160) DEFAULT NULL, disqus_id INT DEFAULT NULL, email_hash VARCHAR(32) DEFAULT NULL, UNIQUE INDEX UNIQ_6AB0FB335E237E065126AC48476F5DE7 (name, mail, website), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE posts (id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, user_id INT DEFAULT NULL, title VARCHAR(128) NOT NULL, url VARCHAR(255) NOT NULL, hide TINYINT(1) NOT NULL, text_post LONGTEXT NOT NULL, description VARCHAR(255) DEFAULT NULL, time_created DATETIME NOT NULL, last_update DATETIME DEFAULT NULL, disqus_thread INT DEFAULT NULL, UNIQUE INDEX UNIQ_885DBAFAF47645AE (url), INDEX IDX_885DBAFA12469DE2 (category_id), INDEX IDX_885DBAFAA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE relation_topictag (post_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_5D8668364B89032C (post_id), INDEX IDX_5D866836BAD26311 (tag_id), PRIMARY KEY(post_id, tag_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE posts_counts (id INT AUTO_INCREMENT NOT NULL, post_id INT DEFAULT NULL, comments INT NOT NULL, views INT NOT NULL, UNIQUE INDEX UNIQ_D23531924B89032C (post_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sys_parameters (id INT AUTO_INCREMENT NOT NULL, optionkey VARCHAR(128) NOT NULL, value LONGTEXT NOT NULL, UNIQUE INDEX UNIQ_275FC35B4B1573F6 (optionkey), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tags (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, url VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_6FBC94265E237E06 (name), UNIQUE INDEX UNIQ_6FBC9426F47645AE (url), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tracking (id INT AUTO_INCREMENT NOT NULL, post_id INT DEFAULT NULL, user_agent_id INT DEFAULT NULL, ip_addr VARCHAR(15) DEFAULT NULL, time_created DATETIME NOT NULL, INDEX IDX_A87C621C4B89032C (post_id), INDEX IDX_A87C621CD499950B (user_agent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tracking_agent (id INT AUTO_INCREMENT NOT NULL, user_agent VARCHAR(255) NOT NULL, bot_filter TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_CDD78530C44967C5 (user_agent), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tracking_archive (id INT AUTO_INCREMENT NOT NULL, post_id INT NOT NULL, user_agent_id INT DEFAULT NULL, ip_addr VARCHAR(15) DEFAULT NULL, time_created DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(128) NOT NULL, mail VARCHAR(64) NOT NULL, password VARCHAR(32) NOT NULL, password_salt VARCHAR(32) NOT NULL, user_type VARCHAR(16) NOT NULL, time_created DATETIME NOT NULL, time_last DATETIME DEFAULT NULL, ip_addr VARCHAR(15) DEFAULT NULL, ip_last VARCHAR(15) DEFAULT NULL, email_hash VARCHAR(32) DEFAULT NULL, UNIQUE INDEX UNIQ_1483A5E9F85E0677 (username), UNIQUE INDEX UNIQ_1483A5E95126AC48 (mail), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE category ADD CONSTRAINT FK_64C19C1727ACA70 FOREIGN KEY (parent_id) REFERENCES category (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962A727ACA70 FOREIGN KEY (parent_id) REFERENCES comments (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962A4B89032C FOREIGN KEY (post_id) REFERENCES posts (id)');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962A506AFCC0 FOREIGN KEY (commentator_id) REFERENCES commentators (id)');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962AA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962AD499950B FOREIGN KEY (user_agent_id) REFERENCES tracking_agent (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE posts ADD CONSTRAINT FK_885DBAFA12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE posts ADD CONSTRAINT FK_885DBAFAA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE relation_topictag ADD CONSTRAINT FK_5D8668364B89032C FOREIGN KEY (post_id) REFERENCES posts (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE relation_topictag ADD CONSTRAINT FK_5D866836BAD26311 FOREIGN KEY (tag_id) REFERENCES tags (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE posts_counts ADD CONSTRAINT FK_D23531924B89032C FOREIGN KEY (post_id) REFERENCES posts (id)');
        $this->addSql('ALTER TABLE tracking ADD CONSTRAINT FK_A87C621C4B89032C FOREIGN KEY (post_id) REFERENCES posts (id)');
        $this->addSql('ALTER TABLE tracking ADD CONSTRAINT FK_A87C621CD499950B FOREIGN KEY (user_agent_id) REFERENCES tracking_agent (id) ON DELETE SET NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE category DROP FOREIGN KEY FK_64C19C1727ACA70');
        $this->addSql('ALTER TABLE posts DROP FOREIGN KEY FK_885DBAFA12469DE2');
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962A727ACA70');
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962A506AFCC0');
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962A4B89032C');
        $this->addSql('ALTER TABLE relation_topictag DROP FOREIGN KEY FK_5D8668364B89032C');
        $this->addSql('ALTER TABLE posts_counts DROP FOREIGN KEY FK_D23531924B89032C');
        $this->addSql('ALTER TABLE tracking DROP FOREIGN KEY FK_A87C621C4B89032C');
        $this->addSql('ALTER TABLE relation_topictag DROP FOREIGN KEY FK_5D866836BAD26311');
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962AD499950B');
        $this->addSql('ALTER TABLE tracking DROP FOREIGN KEY FK_A87C621CD499950B');
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962AA76ED395');
        $this->addSql('ALTER TABLE posts DROP FOREIGN KEY FK_885DBAFAA76ED395');
        $this->addSql('DROP TABLE avatar');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE comments');
        $this->addSql('DROP TABLE commentators');
        $this->addSql('DROP TABLE posts');
        $this->addSql('DROP TABLE relation_topictag');
        $this->addSql('DROP TABLE posts_counts');
        $this->addSql('DROP TABLE sys_parameters');
        $this->addSql('DROP TABLE tags');
        $this->addSql('DROP TABLE tracking');
        $this->addSql('DROP TABLE tracking_agent');
        $this->addSql('DROP TABLE tracking_archive');
        $this->addSql('DROP TABLE users');
    }
}
