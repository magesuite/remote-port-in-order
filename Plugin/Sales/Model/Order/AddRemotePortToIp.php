<?php

namespace MageSuite\RemotePortInOrder\Plugin\Sales\Model\Order;

class AddRemotePortToIp
{

    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    private $request;

    public function __construct(\Magento\Framework\App\RequestInterface $request)
    {
        $this->request = $request;
    }

    public function aroundSetRemoteIp(\Magento\Sales\Model\Order $subject, callable $proceed, $remoteIp)
    {
        $remotePort = $this->request->getServer('REMOTE_PORT');
        if (!preg_match('/(\d[\d.]+):(\d+)\b/', $remoteIp) && !empty($remotePort)) {
            $remoteIp = sprintf('%s:%s', $remoteIp, $remotePort);
        }

        $result = $proceed($remoteIp);

        return $result;
    }

}
