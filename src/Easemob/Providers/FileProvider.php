<?php

namespace light\Easemob\Providers;

use light\Easemob\Rest\File;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class FileProvider implements ServiceProviderInterface
{
    public function register(Container $pimple)
    {
        $pimple['file'] = function ($pimple) {
            return new File($pimple['access_token'], $pimple['http']);
        };
    }
}
