<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230613040158 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        /* SQL-query for search
            SELECT
                src.user_agent,
                src.is_bot,
                (src.t_cnt + src.tt_cnt) AS cnt,
                src.created_at
            FROM (
            SELECT
                ta.user_agent,
                ta.is_bot,
                ta.created_at,
                COUNT(DISTINCT t.id) AS t_cnt,
                COUNT(DISTINCT tt.id) AS tt_cnt
            FROM tracking_agent AS ta
            LEFT JOIN tracking AS t ON ta.id = t.user_agent_id
            LEFT JOIN tracking_archive AS tt ON ta.id = tt.user_agent_id
            WHERE user_agent LIKE '%client%'
            GROUP BY ta.id) AS src
            ORDER BY cnt DESC
            SQL;
        */

        $this->addSql('UPDATE tracking_agent SET is_bot = 0 WHERE user_agent LIKE \'%yandex%\'');
        $this->addSql('UPDATE tracking_agent SET is_bot = 1 WHERE user_agent LIKE \'%yandex.com/bots%\'');
        $this->addSql('UPDATE tracking_agent SET is_bot = 1 WHERE user_agent = \'\'');
        $this->addSql('UPDATE tracking_agent SET is_bot = 1 WHERE user_agent LIKE \'java%\'');
        $this->addSql('UPDATE tracking_agent SET is_bot = 1 WHERE user_agent LIKE \'%yacy.net/bot%\'');
        $this->addSql('UPDATE tracking_agent SET is_bot = 1 WHERE user_agent LIKE \'hotjava%\'');
        $this->addSql('UPDATE tracking_agent SET is_bot = 1 WHERE user_agent LIKE \'Apache-HttpClient%\'');
        $this->addSql('UPDATE tracking_agent SET is_bot = 1 WHERE user_agent LIKE \'%femtosearchbot%\'');
        $this->addSql('UPDATE tracking_agent SET is_bot = 1 WHERE user_agent LIKE \'%crawler%\'');
        $this->addSql('UPDATE tracking_agent SET is_bot = 1 WHERE user_agent LIKE \'%inoreader.com%\'');
        $this->addSql('UPDATE tracking_agent SET is_bot = 1 WHERE user_agent LIKE \'%fetch%\'');
        $this->addSql('UPDATE tracking_agent SET is_bot = 1 WHERE user_agent LIKE \'%curl/%\'');
        $this->addSql('UPDATE tracking_agent SET is_bot = 1 WHERE user_agent LIKE \'%wget%\'');
        $this->addSql('UPDATE tracking_agent SET is_bot = 1 WHERE user_agent LIKE \'%python%\'');
        $this->addSql('UPDATE tracking_agent SET is_bot = 1 WHERE user_agent LIKE \'%parser%\'');
        $this->addSql('UPDATE tracking_agent SET is_bot = 1 WHERE user_agent LIKE \'%ruby/%\'');
        $this->addSql('UPDATE tracking_agent SET is_bot = 1 WHERE user_agent LIKE \'%Go-http-client%\'');
        $this->addSql('UPDATE tracking_agent SET is_bot = 1 WHERE user_agent = \'ALittle Client\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('SELECT NOW()');
    }
}
