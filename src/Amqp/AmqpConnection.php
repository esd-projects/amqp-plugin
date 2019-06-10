<?php


namespace ESD\Plugins\Amqp;

use Exception;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class AmqpConnection
{

    /**
     * @var AmqpConfig
     */
    protected $amqpConfig;

    /**
     * @var AMQPStreamConnection
     */
    protected $connection = null;

    /**
     * AmqpConnection constructor.
     * @param AmqpConfig $amqpConfig
     * @throws Exception
     */
    public function __construct(AmqpConfig $amqpConfig)
    {
        $amqpConfig->buildConfig();
        $this->amqpConfig = $amqpConfig;
    }

    /**
     * @param null $channel_id
     * @return AmqpChannel
     * @throws Exception
     */
    public function channel($channel_id = null)
    {
        $this->connect();
        return $this->connection->channel($channel_id);
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

    /**
     * @throws Exception
     */
    protected function connect()
    {
        if($this->connection && !$this->connection->isConnected()) {
            $this->connection->reconnect();
        } else if (!$this->connection) {
            var_dump("test");
            /**
             * @var $connection AmqpConnection
             */
            $connection = AMQPStreamConnection::create_connection($this->amqpConfig->getHosts(), [
                'insist' => $this->amqpConfig->isInsist(),
                'login_method' => $this->amqpConfig->getLoginMethod(),
                'login_response' => $this->amqpConfig->getLoginResponse(),
                'locale' => $this->amqpConfig->getLocale(),
                'connection_timeout' => $this->amqpConfig->getConnectionTimeout(),
                'read_write_timeout' => $this->amqpConfig->getReadWriteTimeout(),
                'context' => $this->amqpConfig->getContext(),
                'keepalive' => $this->amqpConfig->isKeepAlive(),
                'heartbeat' => $this->amqpConfig->getHeartBeat()
            ]);
            $this->connection = $connection;
        }
    }
}