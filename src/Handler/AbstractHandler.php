<?php

namespace DelayQueue\Handler;

use DelayQueue\Container\ContainerAwareTrait;
use Exception;

/**
 * Job处理抽象类
 *
 * Class AbstractHandler
 *
 * @package DelayQueue\Handler
 */
abstract class AbstractHandler implements HandlerInterface
{
    use ContainerAwareTrait;

    /**
     * @var string Job唯一标识
     */
    protected $id;
    /**
     * @var string Job Topic
     */
    protected $topic;

    /**
     * @var array
     */
    protected $body;

    /**
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @param string $topic
     */
    public function setTopic($topic)
    {
        $this->topic = $topic;
    }

    /**
     * @param array $body
     */
    public function setBody(array $body)
    {
        $this->body = $body;
    }

    public function run()
    {
        $this->setUp();

        try {
            $this->perform();
            $this->delayQueue->finish($this->id);
        } catch (Exception $exception) {
            $this->logger->warning(sprintf('Job execution failed %s %s', $exception->getMessage(), $exception->getTraceAsString()));
        }

        $this->tearDown();
    }

    protected function setUp()
    {
    }

    protected function tearDown()
    {
    }

    abstract protected function perform();
}
