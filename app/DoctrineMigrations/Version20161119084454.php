<?php

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161119084454 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX UNIQ_1C82828A2D5B0234F62F176 ON geo_location_city');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1C82828A2D5B0234F62F176F92F3E70 ON geo_location_city (city, region, country_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX UNIQ_1C82828A2D5B0234F62F176F92F3E70 ON geo_location_city');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1C82828A2D5B0234F62F176 ON geo_location_city (city, region)');
    }
}
