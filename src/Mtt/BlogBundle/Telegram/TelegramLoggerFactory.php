<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 11.10.17
 * Time: 0:41
 */

namespace Mtt\BlogBundle\Telegram;

use Monolog\Formatter\LineFormatter;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;

class TelegramLoggerFactory
{
    /**
     * @param string $logsDir
     *
     * @return Logger
     */
    public static function createLogger(string $logsDir)
    {
        $loggerHandler = new RotatingFileHandler($logsDir . '/telegram.log', 6, Logger::DEBUG, true, 0666);
        $loggerHandler->setFilenameFormat('{filename}-{date}', 'Y-m');
        $loggerHandler->setFormatter(new LineFormatter(null, 'Y-m-d H:i:s.v'));

        return new Logger('telegram', [$loggerHandler]);
    }
}
