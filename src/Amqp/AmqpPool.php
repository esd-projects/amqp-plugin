<?php


namespace ESD\Plugins\Amqp;


use PhpAmqpLib\Channel\AMQPChannel;

class AmqpPool
{
    protected $poolList = [];

    /**
     * 添加连接池
     * @param AmqpConnection $amqpConnection
     */
    public function addConnection(AmqpConnection $amqpConnection)
    {
        $this->poolList[$amqpConnection->getAmqpPoolConfig()->getName()] = $amqpConnection;
    }

    /**
     * @param string $name
     * @param int $channel_id
     * @return AMQPChannel
     * @throws \Exception
     */
    public function channel($name = "default", $channel_id = null): AMQPChannel
    {
        $connection = $this->getConnection($name);
        return $connection->channel($channel_id);
    }

    /**
     * 获取连接
     * @param $name
     * @return AmqpConnection|null
     */
    public function getConnection($name = "default")
    {
        return $this->poolList[$name] ?? null;
    }
}