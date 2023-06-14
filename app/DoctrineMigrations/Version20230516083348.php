<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230516083348 extends AbstractMigration implements ContainerAwareInterface
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

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP PROCEDURE IF EXISTS `update_comments_count`');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP PROCEDURE `update_comments_count`');
    }

    /**
     * @param Schema $schema
     */
    public function postUp(Schema $schema)
    {
        parent::postUp($schema);

        $sql = file_get_contents(__DIR__ . '/sql/update_comments_count_02.sql');

        $em = $this->container->get('doctrine.orm.entity_manager');
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute();

        $this->write('     <comment>-></comment> CREATE PROCEDURE `update_comments_count`');
    }

    /**
     * @param Schema $schema
     */
    public function postDown(Schema $schema)
    {
        parent::postDown($schema);

        $sql = file_get_contents(__DIR__ . '/sql/update_comments_count_01.sql');

        $em = $this->container->get('doctrine.orm.entity_manager');
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute();

        $this->write('     <comment>-></comment> CREATE PROCEDURE `update_comments_count`');
    }
}
