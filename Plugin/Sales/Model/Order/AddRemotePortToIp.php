<?php

namespace MageSuite\RemotePortInOrder\Plugin\Sales\Model\Order;

class AddRemotePortToIp
{
    protected \Magento\Framework\App\RequestInterface $request;

    public function __construct(\Magento\Framework\App\RequestInterface $request)
    {
        $this->request = $request;
    }

    public function aroundSetRemoteIp(\Magento\Sales\Model\Order $subject, callable $proceed, $remoteIp)
    {
        $remotePort = $this->request->getServer('REMOTE_PORT');

        if ($remoteIp !== null && !preg_match('/(\d[\d.]+):(\d+)\b/', $remoteIp) && !empty($remotePort)) {
            $remoteIp = sprintf('%s:%s', $remoteIp, $remotePort);
        }

        return $proceed($remoteIp);
    }
}
