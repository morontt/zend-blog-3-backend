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
final class Version20260301080532 extends AbstractMigration implements ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @param ContainerInterface|null $container
     */
    public function setContainer(?ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP PROCEDURE `tracking_to_archive`');
        $this->addSql('DROP TABLE tracking_archive');
        $this->addSql('ALTER TABLE comments CHANGE force_created_at force_created_at DATETIME(3) DEFAULT NULL COMMENT \'(DC2Type:milliseconds_dt)\'');
        $this->addSql('ALTER TABLE posts CHANGE force_created_at force_created_at DATETIME(3) DEFAULT NULL COMMENT \'(DC2Type:milliseconds_dt)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tracking_archive (id INT AUTO_INCREMENT NOT NULL, post_id INT NOT NULL, user_agent_id INT DEFAULT NULL, ip_addr VARCHAR(15) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, ip_long INT UNSIGNED DEFAULT NULL, time_created DATETIME(3) NOT NULL, is_cdn TINYINT(1) DEFAULT 0 NOT NULL, request_uri VARCHAR(128) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, status_code SMALLINT DEFAULT NULL, duration INT DEFAULT NULL, method VARCHAR(8) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_AA3EDCA4D499950B (user_agent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE comments CHANGE force_created_at force_created_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE posts CHANGE force_created_at force_created_at DATETIME DEFAULT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function postDown(Schema $schema): void
    {
        parent::postDown($schema);

        $sql = file_get_contents(__DIR__ . '/sql/tracking_to_archive_04.sql');

        $em = $this->container->get('doctrine.orm.entity_manager');
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute();

        $this->write('     <comment>-></comment> CREATE PROCEDURE `tracking_to_archive`');
    }
}
