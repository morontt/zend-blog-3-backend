<?php

namespace spec\App\Security\SecureCookie;

use App\Security\SecureCookie\Cookie;
use PhpSpec\ObjectBehavior;
use Psr\Log\LoggerInterface;
use Symfony\Component\Serializer\SerializerInterface;

use function App\Utils\base64url_decode;

/**
 * @method \PhpSpec\Wrapper\Subject verifyMac(string)
 * @method \PhpSpec\Wrapper\Subject decrypt(string)
 */
class CookieSpec extends ObjectBehavior
{
    public function let(
        SerializerInterface $serializer,
        LoggerInterface $logger,
    ) {
        $this->beConstructedWith($serializer, 'hash key', 'block key', $logger);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(Cookie::class);
    }

    public function it_is_verify_valid_mac()
    {
        $cases = [
            'zOmyDjEI44cRy5WYKUHM6sojTp4rnZCGtcQHFgPKoydP7n4wovLIJ3RWShU-eMShal0xncC7bDqqgpmTOEel05B1ebAQYA4dISCNpAn5IAhT6vxm0cMkOCRg43Q1DXe2H1q6A0EElQ4H7sYV',
            '-dzuLcZkBRiKpH16l6dZGWRL2lFGY9fpRrM7vgcL-sj6Uc750LHaH-51sWkgV3QX-ng8rl3tOrC94MUjb2q7t2dV8gkv5WWeKR2CSHyNwJHRINjRYZcE_Mhs38KHsVcjogYD1J3CCRMdvesQrdBRr_4=',
        ];

        foreach ($cases as $case) {
            $data = base64url_decode($case);
            $this->verifyMac($data)->shouldReturn(true);
        }
    }

    public function it_is_verify_invalid_mac()
    {
        $cases = [
            'k313jrdNk2u+wL4kAaKgLQ==',
            'c9NjjQ2oeZ6qKmnXdqAh0yweTVCx0V9eKgCfuJOcBmc=',
        ];

        foreach ($cases as $case) {
            $data = base64url_decode($case);
            $this->verifyMac($data)->shouldReturn(false);
        }
    }

    public function it_is_decrypt()
    {
        $encrypted = 'wEPUU6shbiZPQe7-xKHIAPfQvOVE5XkWP5NxaAaokGxpZbjL2OG5PpxIjSxt8BpwHcQHfiY1GxU6dDrAsHyr61KQpw6jAnwZ8OK0L_JjIPtYfkl5dCyzuXpMNDM_4IlHWYz-OkBbDL5KXOs0PI_hxwGoqOM=';

        $data = base64url_decode($encrypted);
        $this->decrypt($data)->shouldReturn('{"a":{"i":160,"u":"admin","r":"admin"},"d":"2026-04-21T10:02:40+03:00"}' . PHP_EOL);
    }
}
