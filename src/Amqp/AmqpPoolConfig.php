<?php


namespace ESD\Plugins\Amqp;


use ESD\Core\Plugins\Config\BaseConfig;

class AmqpPoolConfig extends BaseConfig
{
    const key = "amqp";

    /**
     * @var string
     */
    protected $name;

    /**
     * @var AmqpHostConfig[]
     */
    protected $hosts = [];

    /**
     * @var bool
     */
    protected $insist = false;

    /**
     * @var string
     */
    protected $loginMethod = 'AMQPLAIN';

    /**
     * @var null
     */
    protected $loginResponse = null;

    /**
     * @var string
     */
    protected $locale = 'en_US';

    /**
     * @var float
     */
    protected $connectionTimeout = 3.0;

    /**
     * @var float
     */
    protected $readWriteTimeout = 130.0;

    /**
     * @var null
     */
    protected $context = null;

    /**
     * @var bool
     */
    protected $keepAlive = false;

    /**
     * @var int
     */
    protected $heartBeat = 60;

    /**
     * ConsulConfig constructor.
     * @throws \ReflectionException
     */
    public function __construct()
    {
        parent::__construct(self::key);
    }

    /**
     * @return bool
     */
    public function isInsist(): bool
    {
        return $this->insist;
    }

    /**
     * @param bool $insist
     */
    public function setInsist(bool $insist): void
    {
        $this->insist = $insist;
    }

    /**
     * @return string
     */
    public function getLoginMethod(): string
    {
        return $this->loginMethod;
    }

    /**
     * @param string $loginMethod
     */
    public function setLoginMethod(string $loginMethod): void
    {
        $this->loginMethod = $loginMethod;
    }

    /**
     * @return null
     */
    public function getLoginResponse()
    {
        return $this->loginResponse;
    }

    /**
     * @param null $loginResponse
     */
    public function setLoginResponse($loginResponse): void
    {
        $this->loginResponse = $loginResponse;
    }

    /**
     * @return string
     */
    public function getLocale(): string
    {
        return $this->locale;
    }

    /**
     * @param string $locale
     */
    public function setLocale(string $locale): void
    {
        $this->locale = $locale;
    }

    /**
     * @return float
     */
    public function getConnectionTimeout(): float
    {
        return $this->connectionTimeout;
    }

    /**
     * @param float $connectionTimeout
     */
    public function setConnectionTimeout(float $connectionTimeout): void
    {
        $this->connectionTimeout = $connectionTimeout;
    }

    /**
     * @return float
     */
    public function getReadWriteTimeout(): float
    {
        return $this->readWriteTimeout;
    }

    /**
     * @param float $readWriteTimeout
     */
    public function setReadWriteTimeout(float $readWriteTimeout): void
    {
        $this->readWriteTimeout = $readWriteTimeout;
    }

    /**
     * @return null
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * @param null $context
     */
    public function setContext($context): void
    {
        $this->context = $context;
    }

    /**
     * @return bool
     */
    public function isKeepAlive(): bool
    {
        return $this->keepAlive;
    }

    /**
     * @param bool $keepAlive
     */
    public function setKeepAlive(bool $keepAlive): void
    {
        $this->keepAlive = $keepAlive;
    }

    /**
     * @return int
     */
    public function getHeartBeat(): int
    {
        return $this->heartBeat;
    }

    /**
     * @param int $heartBeat
     */
    public function setHeartBeat(int $heartBeat): void
    {
        $this->heartBeat = $heartBeat;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return array[]
     */
    public function getHosts(): array
    {
        $hosts = [];
        foreach ($this->hosts as $host) {
            $hosts[] = $host->toConfigArray();
        }
        return $hosts;
    }

    /**
     * @param array $hosts
     * @throws \ReflectionException
     */
    public function setHosts(array $hosts): void
    {
        if (empty($hosts)) {
            $this->hosts = [];
            return;
        }

        foreach ($hosts as $key => $host) {
            if (is_array($host)) {
                $amqpConfig = new AmqpHostConfig();
                $amqpConfig->buildFromConfig($host);
                $this->hosts[$key] = $amqpConfig;
            } else if($host instanceof AmqpHostConfig) {
                $this->hosts[$key] = $host;
            }
        }
    }

    /**
     * 构建配置
     * @throws AmqpException
     */
    public function buildConfig()
    {
        if (!extension_loaded('bcmath')) {
            throw new AmqpException("缺少bcmath扩展");
        }

        if(empty($this->hosts)){
            throw new AmqpException("hosts必须设置");
        }

        foreach ($this->hosts as $host) {
            $host->buildConfig();
        }
    }
}