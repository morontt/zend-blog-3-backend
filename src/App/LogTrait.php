<?php

declare(strict_types=1);

/**
 * User: morontt
 * Date: 30.03.2026
 * Time: 09:48
 */

namespace App;

use Psr\Log\LoggerAwareTrait;
use Psr\Log\LogLevel;

trait LogTrait
{
    use LoggerAwareTrait;

    /**
     * @var array<string, int>
     */
    protected array $levelPriority = [
        LogLevel::DEBUG => 1,
        LogLevel::INFO => 2,
        LogLevel::NOTICE => 3,
        LogLevel::WARNING => 4,
        LogLevel::ERROR => 5,
        LogLevel::CRITICAL => 6,
        LogLevel::ALERT => 7,
        LogLevel::EMERGENCY => 8,
    ];

    protected ?string $logPrefix = null;
    protected string $logLevel = LogLevel::DEBUG;

    public function setLogLevel(string $level): void
    {
        $this->logLevel = $level;
    }

    protected function setLogPrefix(?string $prefix): void
    {
        $this->logPrefix = $prefix;
    }

    /**
     * System is unusable.
     *
     * @phpstan-ignore missingType.iterableValue
     */
    protected function emergency(string $message, array $context = []): void
    {
        $this->log(LogLevel::EMERGENCY, $message, $context);
    }

    /**
     * Action must be taken immediately.
     *
     * @phpstan-ignore missingType.iterableValue
     */
    protected function alert(string $message, array $context = []): void
    {
        $this->log(LogLevel::ALERT, $message, $context);
    }

    /**
     * Critical conditions.
     *
     * Example: Application component unavailable, unexpected exception.
     *
     * @phpstan-ignore missingType.iterableValue
     */
    protected function critical(string $message, array $context = []): void
    {
        $this->log(LogLevel::CRITICAL, $message, $context);
    }

    /**
     * Runtime errors that do not require immediate action but should typically
     * be logged and monitored.
     *
     * @phpstan-ignore missingType.iterableValue
     */
    protected function error(string $message, array $context = []): void
    {
        $this->log(LogLevel::ERROR, $message, $context);
    }

    /**
     * Exceptional occurrences that are not errors.
     *
     * Example: Use of deprecated APIs, poor use of an API, undesirable things
     * that are not necessarily wrong.
     *
     * @phpstan-ignore missingType.iterableValue
     */
    protected function warning(string $message, array $context = []): void
    {
        $this->log(LogLevel::WARNING, $message, $context);
    }

    /**
     * Normal but significant events.
     *
     * @phpstan-ignore missingType.iterableValue
     */
    protected function notice(string $message, array $context = []): void
    {
        $this->log(LogLevel::NOTICE, $message, $context);
    }

    /**
     * Interesting events.
     *
     * @phpstan-ignore missingType.iterableValue
     */
    protected function info(string $message, array $context = []): void
    {
        $this->log(LogLevel::INFO, $message, $context);
    }

    /**
     * Detailed debug information.
     *
     * @phpstan-ignore missingType.iterableValue
     */
    protected function debug(string $message, array $context = []): void
    {
        $this->log(LogLevel::DEBUG, $message, $context);
    }

    /**
     * @phpstan-ignore missingType.iterableValue
     */
    protected function log(string $level, string $message, array $context = []): void
    {
        if ($this->levelPriority[$level] < $this->levelPriority[$this->logLevel]) {
            return;
        }

        if (!$this->logPrefix) {
            $this->initLogPrefix();
        }

        $this->logger->log($level, trim($this->logPrefix) . ' ' . $message, $context);
    }

    private function initLogPrefix(): void
    {
        // http://stackoverflow.com/a/27457689/6109406
        $shortName = substr(strrchr(get_class($this), '\\'), 1);
        $this->setLogPrefix($shortName . ':');
    }
}
