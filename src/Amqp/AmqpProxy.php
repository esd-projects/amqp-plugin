<?php


namespace ESD\Plugins\Amqp;


use ESD\Psr\DB\DBInterface;

class AmqpProxy implements DBInterface
{
    use GetAmqp;

    protected $_lastQuery;

    public function __get($name)
    {
        return $this->amqp()->$name;
    }

    public function __set($name, $value)
    {
        $this->amqp()->$name = $value;
    }

    public function __call($name, $arguments)
    {
        $this->_lastQuery = json_encode($arguments);
        return $this->execute($name, function () use ($name, $arguments) {
            return call_user_func_array([$this->amqp(), $name], $arguments);
        });
    }

    public function getType()
    {
        return 'amqp';
    }


    public function execute($name, callable $call = null)
    {
        if ($call != null) {
            return $call();
        }
        return null;
    }

    public function getLastQuery()
    {
        return $this->_lastQuery;
    }
}