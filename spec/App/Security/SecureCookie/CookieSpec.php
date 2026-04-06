<?php

namespace spec\App\Security\SecureCookie;

use App\Security\SecureCookie\Cookie;
use PhpSpec\ObjectBehavior;

/**
 * @method \PhpSpec\Wrapper\Subject verifyMac(string)
 */
class CookieSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith('hash key', 'block key');
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
            $data = base64_decode(str_replace(['-', '_'], ['+', '/'], $case));
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
            $data = base64_decode(str_replace(['-', '_'], ['+', '/'], $case));
            $this->verifyMac($data)->shouldReturn(false);
        }
    }
}
