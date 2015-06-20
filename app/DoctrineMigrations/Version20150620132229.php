<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150620132229 extends AbstractMigration implements ContainerAwareInterface
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

        $this->addSql('DROP PROCEDURE IF EXISTS `update_comments_count`');
    }

    public function postUp(Schema $schema)
    {
        parent::postUp($schema);

        $sql = "
        CREATE PROCEDURE `update_comments_count`(IN topicID INT UNSIGNED)
        BEGIN
            DECLARE count_comments INT DEFAULT 0;

            SELECT COUNT( id ) INTO count_comments
                FROM `comments`
                WHERE (`post_id` = topicID) AND (`deleted` = 0);

            UPDATE `posts_counts`
                SET `comments` = count_comments
                WHERE `posts_counts`.`post_id` = topicID;

            SELECT count_comments;
        END";

        $em = $this->container->get('doctrine.orm.entity_manager');
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute();

        $this->write('     <comment>-></comment> CREATE PROCEDURE `update_comments_count`');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP PROCEDURE IF EXISTS `update_comments_count`');
    }
}
