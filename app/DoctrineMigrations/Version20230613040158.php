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
            WHERE ta.user_agent LIKE '%client%'
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
        $this->addSql('UPDATE tracking_agent SET is_bot = 1 WHERE user_agent LIKE \'%petalbot%\'');
        $this->addSql('UPDATE tracking_agent SET is_bot = 1 WHERE user_agent = \'ALittle Client\'');
        $this->addSql('UPDATE tracking_agent SET is_bot = 1 WHERE user_agent LIKE \'%dreamwidth.org%\'');
        $this->addSql('UPDATE tracking_agent SET is_bot = 1 WHERE user_agent LIKE \'%SemrushBot%\'');
        $this->addSql('UPDATE tracking_agent SET is_bot = 1 WHERE user_agent LIKE \'%liferea%\'');
        $this->addSql('UPDATE tracking_agent SET is_bot = 1 WHERE user_agent LIKE \'%zoominfobot%\'');
        $this->addSql('UPDATE tracking_agent SET is_bot = 1 WHERE user_agent LIKE \'%tt-rss.org%\'');
        $this->addSql('UPDATE tracking_agent SET is_bot = 1 WHERE user_agent LIKE \'%Datanyze%\'');
        $this->addSql('UPDATE tracking_agent SET is_bot = 1 WHERE user_agent LIKE \'%DataForSeoBot%\'');
        $this->addSql('UPDATE tracking_agent SET is_bot = 1 WHERE user_agent LIKE \'%DotBot%\'');
        $this->addSql('UPDATE tracking_agent SET is_bot = 1 WHERE user_agent LIKE \'%Applebot%\'');
        $this->addSql('UPDATE tracking_agent SET is_bot = 1 WHERE user_agent LIKE \'%FlipboardRSS%\'');
        $this->addSql('UPDATE tracking_agent SET is_bot = 1 WHERE user_agent LIKE \'%crawlson%\'');
        $this->addSql('UPDATE tracking_agent SET is_bot = 1 WHERE user_agent LIKE \'%Barkrowler%\'');
        $this->addSql('UPDATE tracking_agent SET is_bot = 1 WHERE user_agent LIKE \'%BorneoBot%\'');
        $this->addSql('UPDATE tracking_agent SET is_bot = 1 WHERE user_agent LIKE \'%seznam%\'');
        $this->addSql('UPDATE tracking_agent SET is_bot = 1 WHERE user_agent LIKE \'%SeekportBot%\'');
        $this->addSql('UPDATE tracking_agent SET is_bot = 1 WHERE user_agent LIKE \'%serpstatbot%\'');
        $this->addSql('UPDATE tracking_agent SET is_bot = 1 WHERE user_agent LIKE \'%AspiegelBot%\'');
        $this->addSql('UPDATE tracking_agent SET is_bot = 1 WHERE user_agent LIKE \'%amazonbot%\'');
        $this->addSql('UPDATE tracking_agent SET is_bot = 1 WHERE user_agent LIKE \'%IndeedBot%\'');
        $this->addSql('UPDATE tracking_agent SET is_bot = 1 WHERE user_agent LIKE \'%awario.com/bots%\'');
        $this->addSql('UPDATE tracking_agent SET is_bot = 1 WHERE user_agent LIKE \'%neevabot%\'');
        $this->addSql('UPDATE tracking_agent SET is_bot = 1 WHERE user_agent LIKE \'%Googlebot%\'');
        $this->addSql('UPDATE tracking_agent SET is_bot = 1 WHERE user_agent LIKE \'%LinkpadBot%\'');
        $this->addSql('UPDATE tracking_agent SET is_bot = 1 WHERE user_agent LIKE \'%Keybot%\'');
        $this->addSql('UPDATE tracking_agent SET is_bot = 1 WHERE user_agent LIKE \'%Timpibot%\'');
        $this->addSql('UPDATE tracking_agent SET is_bot = 1 WHERE user_agent LIKE \'%MojeekBot%\'');
        $this->addSql('UPDATE tracking_agent SET is_bot = 1 WHERE user_agent LIKE \'%SenutoBot%\'');
        $this->addSql('UPDATE tracking_agent SET is_bot = 1 WHERE user_agent LIKE \'%Cliqzbot%\'');
        $this->addSql('UPDATE tracking_agent SET is_bot = 1 WHERE user_agent LIKE \'%FlfBaldrBot%\'');
        $this->addSql('UPDATE tracking_agent SET is_bot = 1 WHERE user_agent LIKE \'%DuckDuckBot%\'');
        $this->addSql('UPDATE tracking_agent SET is_bot = 1 WHERE user_agent LIKE \'%RedirectBot%\'');
        $this->addSql('UPDATE tracking_agent SET is_bot = 1 WHERE user_agent LIKE \'%inetdex.com/bot%\'');
        $this->addSql('UPDATE tracking_agent SET is_bot = 1 WHERE user_agent LIKE \'%WebwikiBot%\'');
        $this->addSql('UPDATE tracking_agent SET is_bot = 1 WHERE user_agent LIKE \'%PaperLiBot%\'');
        $this->addSql('UPDATE tracking_agent SET is_bot = 1 WHERE user_agent LIKE \'%Clarabot%\'');
        $this->addSql('UPDATE tracking_agent SET is_bot = 1 WHERE user_agent LIKE \'%Discordbot%\'');
        $this->addSql('UPDATE tracking_agent SET is_bot = 1 WHERE user_agent LIKE \'%DuckDuckGo-Favicons-Bot%\'');
        $this->addSql('UPDATE tracking_agent SET is_bot = 1 WHERE user_agent LIKE \'%Mail.RU_Bot%\'');
        $this->addSql('UPDATE tracking_agent SET is_bot = 1 WHERE user_agent LIKE \'%BSbot%\'');
        $this->addSql('UPDATE tracking_agent SET is_bot = 1 WHERE user_agent LIKE \'%Twitterbot%\'');
        $this->addSql('UPDATE tracking_agent SET is_bot = 1 WHERE user_agent LIKE \'%Pandalytics%\'');
        $this->addSql('UPDATE tracking_agent SET is_bot = 1 WHERE user_agent = \'labjs.pro/bot\'');
        $this->addSql('UPDATE tracking_agent SET is_bot = 1 WHERE user_agent LIKE \'%2ip bot%\'');
        $this->addSql('UPDATE tracking_agent SET is_bot = 1 WHERE user_agent LIKE \'%Qwantify%\'');
        $this->addSql('UPDATE tracking_agent SET is_bot = 1 WHERE user_agent LIKE \'%bot@linkfluence.com%\'');
        $this->addSql('UPDATE tracking_agent SET is_bot = 1 WHERE user_agent LIKE \'%BUbiNG%\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('SELECT NOW()');
    }
}
