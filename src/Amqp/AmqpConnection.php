<?php


namespace ESD\Plugins\Amqp;

use Exception;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use ReflectionException;

class AmqpConnection
{

    /**
     * @var AmqpConfig
     */
    protected $amqpConfig;

    /**
     * @var AMQPStreamConnection
     */
    protected $connection;

    /**
     * AmqpConnection constructor.
     * @param AmqpConfig $amqpConfig
     * @throws Exception
     */
    public function __construct(AmqpConfig $amqpConfig)
    {
        $amqpConfig->buildConfig();
        /**
         * @var $connection AmqpConnection
         */
        $connection = AMQPStreamConnection::create_connection($amqpConfig->getHosts(), [
            'insist' => $amqpConfig->isInsist(),
            'login_method' => $amqpConfig->getLoginMethod(),
            'login_response' => $amqpConfig->getLoginResponse(),
            'locale' => $amqpConfig->getLocale(),
            'connection_timeout' => $amqpConfig->getConnectionTimeout(),
            'read_write_timeout' => $amqpConfig->getReadWriteTimeout(),
            'context' => $amqpConfig->getContext(),
            'keepalive' => $amqpConfig->isKeepAlive(),
            'heartbeat' => $amqpConfig->getHeartBeat()
        ]);
        $this->connection = $connection;
        $this->amqpConfig = $amqpConfig;
    }

    /**
     * @param null $channel_id
     * @return AmqpChannel
     * @throws ReflectionException
     */
    public function channel($channel_id = null)
    {
        if($this->connection->isConnected()) {
            return $this->connection->channel($channel_id);
        } else {
            $this->connection->reconnect();
            $channel = $this->connection->channel($channel_id);
        }

        return $channel;
    }

    /**
     * @return AmqpConfig
     */
    public function getAmqpConfig(): AmqpConfig
    {
        return $this->amqpConfig;
    }

    /**
     * @param AmqpConfig $amqpConfig
     */
    public function setAmqpConfig(AmqpConfig $amqpConfig): void
    {
        $this->amqpConfig = $amqpConfig;
    }
}