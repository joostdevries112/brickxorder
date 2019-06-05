<?php

namespace Alius\BrickxOrder\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;

class SetBrickxOrderFields implements ObserverInterface
{
    public function execute(Observer $observer)
    {
        $order = $observer->getEvent()->getOrder();

        $order->setBrickxRequestedDeliveryDate(date('d-m-Y'));
        $order->setBrickxOrderReference("test-reference");
        $order->setBrickxOrderMemo("test-memo");

        $order->save();
    }
}