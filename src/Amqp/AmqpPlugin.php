<?php


namespace ESD\Plugins\Amqp;


use ESD\Core\Context\Context;
use ESD\Core\PlugIn\AbstractPlugin;
use ESD\Core\Plugins\Config\ConfigException;
use ESD\Core\Plugins\Logger\GetLogger;
use ESD\Core\Server\Server;
use PhpAmqpLib\Channel\AMQPChannel;

class AmqpPlugin extends AbstractPlugin
{
    use GetLogger;

    use GetAmqp;

    /**
     * @var AmqpConfig[]
     */
    protected $configList = [];

    public function __construct()
    {
        parent::__construct();
    }


    /**
     * @return string
     */
    public function getName(): string
    {
        return "AmqpPlugin";
    }

    /**
     * 初始化
     * @param Context $context
     * @throws ConfigException
     * @throws \Exception
     */
    public function beforeServerStart(Context $context)
    {
        //所有配置合併
        foreach ($this->configList as $config) {
            $config->merge();
        }

        $amqpProxy = new AmqpProxy();
        $this->setToDIContainer(AmqpChannel::class, $amqpProxy);
        $this->setToDIContainer(\PhpAmqpLib\Channel\AMQPChannel::class, $amqpProxy);
    }

    /**
     * 在进程启动前
     * @param Context $context
     * @throws \ReflectionException
     * @throws \Exception
     */
    public function beforeProcessStart(Context $context)
    {
        $amqpPool = new AmqpPool();

        //重新获取配置
        $this->configList = [];

        $configs = Server::$instance->getConfigContext()->get(AmqpConfig::key, []);
        if (empty($configs)) {
            $this->warn("没有amqp配置");
            return;
        }
        foreach ($configs as $key => $value) {
            $amqpConfig = new AmqpConfig();
            $amqpConfig->setName($key);
            $this->configList[$key] = $amqpConfig->buildFromConfig($value);

            $amqpConnection = new AmqpConnection($amqpConfig);
            $amqpPool->addConnection($amqpConnection);
            $this->debug("已添加名为 {$amqpConfig->getName()} 的Amqp连接");
        }
        $context->add("amqpPool", $amqpPool);
        $this->setToDIContainer(AmqpPool::class, $amqpPool);
        $this->ready();
    }
}