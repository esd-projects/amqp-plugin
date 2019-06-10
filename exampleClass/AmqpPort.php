<?php

namespace ESD\Plugins\Amqp\ExampleClass;

use ESD\Core\Server\Beans\Request;
use ESD\Core\Server\Beans\Response;
use ESD\Plugins\Amqp\GetAmqp;
use ESD\Server\Co\ExampleClass\Port\DefaultPort;

class AmqpPort extends DefaultPort
{
    use GetAmqp;

    public function onHttpRequest(Request $request, Response $response)
    {
        var_dump($this->amqp());

        $response->withContent("hello");
        $response->end();
    }
}