<?php

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160425235303 extends AbstractMigration implements ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @param ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE posts ADD comments_count INT NOT NULL, ADD views_count INT NOT NULL');
        $this->addSql('UPDATE posts AS p JOIN posts_counts AS c ON p.id = c.post_id SET p.comments_count = c.comments, p.views_count = c.views');
        $this->addSql('DROP TABLE posts_counts');
        $this->addSql('DROP PROCEDURE IF EXISTS `update_comments_count`');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE posts_counts (id INT AUTO_INCREMENT NOT NULL, post_id INT DEFAULT NULL, comments INT NOT NULL, views INT NOT NULL, UNIQUE INDEX UNIQ_D23531924B89032C (post_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE posts_counts ADD CONSTRAINT FK_D23531924B89032C FOREIGN KEY (post_id) REFERENCES posts (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE posts DROP comments_count, DROP views_count');
    }

    /**
     * @param Schema $schema
     */
    public function postUp(Schema $schema)
    {
        parent::postUp($schema);

        $sql = file_get_contents(__DIR__ . '/sql/update_comments_count_01.sql');

        $em = $this->container->get('doctrine.orm.entity_manager');
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute();

        $this->write('     <comment>-></comment> CREATE PROCEDURE `update_comments_count`');
    }
}
