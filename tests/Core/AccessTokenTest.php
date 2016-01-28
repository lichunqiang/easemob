<?php

namespace tests\Core;

use light\Easemob\Core\AccessToken;
use light\Easemob\Core\Http;

class AccessTokenTest extends \PHPUnit_Framework_TestCase
{

    public function testFetch()
    {
        $http = new Http('https://a1.easemob.com/'.getenv('enterpriseId').'/'.getenv('appId').'/');
        $access_token = new AccessToken(getenv('clientId'), getenv('clientSecret'), $http);

        $token = $access_token->getToken();
        $this->assertTrue(is_string($token));
    }
}
