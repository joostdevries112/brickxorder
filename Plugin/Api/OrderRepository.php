<?php

namespace Alius\BrickxOrder\Plugin\Api;

use Magento\Sales\Api\Data\OrderExtensionFactory;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\Data\OrderSearchResultInterface;
use Magento\Sales\Api\OrderRepositoryInterface;


class OrderRepository
{

    const DELIVERY_DATE = 'brickx_requested_delivery_date';
    const ORDER_REFERENCE = 'brickx_order_reference';
    const MEMO = 'brickx_order_memo';

    protected $extensionFactory;

    public function __construct(OrderExtensionFactory $extensionFactory)
    {
        $this->extensionFactory = $extensionFactory;
    }

    public function getExtensionAttributes(){
        return array(
            self::DELIVERY_DATE,
            self::ORDER_REFERENCE,
            self::MEMO
        );
    }

    public function afterGet(OrderRepositoryInterface $subject, OrderInterface $order)
    {
        foreach($this->getExtensionAttributes() as $attrName){
            $attribute = $order->getData($attrName);
            $extensionAttributes = $order->getExtensionAttributes();
            $extensionAttributes = $extensionAttributes ? $extensionAttributes : $this->extensionFactory->create();
            $method = "set" . str_replace(" ", "", ucwords(str_replace("_", " ", $attrName)));
            $extensionAttributes->$method($attribute);
            $order->setExtensionAttributes($extensionAttributes);
        }

        return $order;
    }

    public function afterGetList(OrderRepositoryInterface $subject, OrderSearchResultInterface $searchResult)
    {
        $orders = $searchResult->getItems();

        foreach ($orders as &$order) {
            foreach($this->getExtensionAttributes() as $attrName){
                $attribute = $order->getData($attrName);
                $extensionAttributes = $order->getExtensionAttributes();
                $extensionAttributes = $extensionAttributes ? $extensionAttributes : $this->extensionFactory->create();
                $method = "set" . str_replace(" ", "", ucwords(str_replace("_", " ", $attrName)));
                $extensionAttributes->$method($attribute);
                $order->setExtensionAttributes($extensionAttributes);
            }
        }

        return $searchResult;
    }
}