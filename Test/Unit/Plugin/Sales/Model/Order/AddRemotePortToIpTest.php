<?php

namespace MageSuite\RemotePortInOrder\Test\Unit\Plugin\Sales\Model\Order;

class AddRemotePortToIpTest extends \PHPUnit\Framework\TestCase
{
    protected ?\Magento\TestFramework\ObjectManager $objectManager;

    protected ?\MageSuite\RemotePortInOrder\Plugin\Sales\Model\Order\AddRemotePortToIp $plugin;

    /**
     * @var \Magento\Framework\App\Request\Http
     */
    protected ?\PHPUnit\Framework\MockObject\MockObject $requestDouble;

    public function setUp(): void
    {
        $this->objectManager = \Magento\TestFramework\ObjectManager::getInstance();

        $this->requestDouble = $this->getMockBuilder(\Magento\Framework\App\Request\Http::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->plugin = $this->objectManager->create(
            \MageSuite\RemotePortInOrder\Plugin\Sales\Model\Order\AddRemotePortToIp::class,
            [
                'request' => $this->requestDouble
            ]
        );
    }

    public function testAddPortCorrectly()
    {
        $ip = '192.168.0.1';
        $expectedResult = '192.168.0.1:66666';

        $this->requestDouble
            ->expects($this->once())
            ->method('getServer')
            ->willReturn('66666');

        $order = $this->objectManager->create(\Magento\Sales\Model\Order::class);
        $proceed = function ($remoteIp) use ($order) {
            return $order->setRemoteIp($remoteIp);
        };

        $result = $this->plugin->aroundSetRemoteIp($order, $proceed, $ip);

        $this->assertEquals(
            $expectedResult,
            $result->getRemoteIp()
        );
    }

    public function testPortMissing()
    {
        $ip = '192.168.0.1';
        $expectedResult = '192.168.0.1';

        $this->requestDouble
            ->expects($this->once())
            ->method('getServer')
            ->willReturn(NULL);

        $order = $this->objectManager->create(\Magento\Sales\Model\Order::class);
        $proceed = function ($remoteIp) use ($order) {
            return $order->setRemoteIp($remoteIp);
        };

        $result = $this->plugin->aroundSetRemoteIp($order, $proceed, $ip);

        $this->assertEquals(
            $expectedResult,
            $result->getRemoteIp()
        );
    }
}
