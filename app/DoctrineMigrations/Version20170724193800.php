<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170724193800 extends AbstractMigration implements ContainerAwareInterface
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
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('SELECT NOW()'); // Hi phantom :)
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('DROP VIEW `v_comments`');
        $this->addSql('DROP VIEW `v_commentators`');
    }

    /**
     * @param Schema $schema
     */
    public function postUp(Schema $schema)
    {
        parent::postUp($schema);

        $sql1 = <<<'SQL'
CREATE VIEW `v_comments` AS
SELECT
    c.id,
    c.parent_id,
    c.post_id,
    IF (c.user_id IS NULL, c.commentator_id, 10000000 + c.user_id) AS uid,
    IF (c.user_id IS NULL, t.name, u.username) AS username,
    IF (c.user_id IS NULL, t.mail, u.mail) AS mail,
    t.website,
    c.text,
    c.ip_addr,
    gci.city,
    gci.region,
    gco.country_name,
    IF (c.user_id IS NULL, t.email_hash, u.email_hash) AS email_hash,
    c.disqus_id,
    c.deleted,
    c.time_created
FROM comments AS c
LEFT JOIN geo_location AS gl ON c.geo_location_id = gl.id
LEFT JOIN geo_location_city AS gci ON gl.city_id = gci.id
LEFT JOIN geo_location_country AS gco ON gci.country_id = gco.id
LEFT JOIN commentators AS t ON c.commentator_id = t.id
LEFT JOIN users AS u ON c.user_id = u.id
SQL;

        $sql2 = <<<'SQL'
CREATE VIEW `v_commentators` AS
SELECT
  c.id,
  c.name,
  c.mail,
  c.website,
  c.disqus_id,
  c.email_hash
FROM commentators AS c
UNION ALL
SELECT
  10000000 + u.id AS id,
  u.username AS name,
  u.mail,
  NULL AS website,
  NULL AS disqus_id,
  u.email_hash
FROM users AS u
SQL;

        $em = $this->container->get('doctrine.orm.entity_manager');
        $stmt = $em->getConnection()->prepare($sql1);
        $stmt->execute();

        $this->write('     <comment>-></comment> CREATE VIEW `v_comments`');

        $stmt = $em->getConnection()->prepare($sql2);
        $stmt->execute();

        $this->write('     <comment>-></comment> CREATE VIEW `v_commentators`');
    }
}
