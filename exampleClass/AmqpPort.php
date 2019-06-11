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
        enableRuntimeCoroutine(true, HOOK_TCP);

        var_dump($this->amqp()->is_open());

        $response->withContent("hello");
        $response->end();
    }
}