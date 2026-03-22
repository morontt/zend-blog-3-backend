<?php

namespace spec\App\Service;

use App\Repository\EmailSubscriptionSettingsRepository;
use App\Repository\UserRepository;
use App\Service\Mailer;
use PhpSpec\ObjectBehavior;
use Psr\Log\LoggerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Twig\Environment as TwigEnvironment;
use Xelbot\Telegram\Robot;

class MailerSpec extends ObjectBehavior
{
    public function let(
        MailerInterface $mailer,
        TwigEnvironment $twig,
        Robot $bot,
        EmailSubscriptionSettingsRepository $subscriptionRepository,
        UserRepository $userRepository,
        LoggerInterface $logger,
    ) {
        $this->beConstructedWith(
            $mailer,
            $twig,
            $bot,
            $subscriptionRepository,
            $userRepository,
            $logger,
            'https://example.com',
            'noreply@example.org',
        );
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(Mailer::class);
    }

    public function it_filter_messages()
    {
        $messages = [
            'Можно грабить корованы...',
            'EmailSpoolSend: Отправлено писем: 1',
            'CertExpiration: Valid for 5 days',
            'Error EmailSpoolSend: Achtung!, file: src/App/Cron/Hourly/EmailSpoolSend.php, line: 20',
        ];

        $this
            ->filteredSystemMessages($messages)
            ->shouldReturn([
                'Можно грабить корованы...',
                'CertExpiration: Valid for 5 days',
                'Error EmailSpoolSend: Achtung!, file: src/App/Cron/Hourly/EmailSpoolSend.php, line: 20',
            ])
        ;
    }
}
