<?php

declare(strict_types=1);

namespace Alius\BrickxOrder\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

use Magento\Sales\Setup\SalesSetupFactory;
use Magento\Framework\DB\Ddl\Table;
 
class InstallData implements InstallDataInterface
{

    protected $salesSetupFactory;

    protected $setup;

    public function __construct(
        SalesSetupFactory $salesSetupFactory
    ){
        $this->salesSetupFactory = $salesSetupFactory;
    }

    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $this->setup = $setup->startSetup();
        $this->installOrder();
        $this->setup = $setup->endSetup();
    }

    public function installOrder(){
        $salesInstaller = $this->salesSetupFactory->create(
            [
                'resourceName' => 'sales_setup',
                'setup' => $this->setup
            ]
        );
        $salesInstaller
            ->addAttribute(
                'order',
                'brickx_requested_delivery_date',
                ['type' => Table::TYPE_DATE, 'length' => '255', 'nullable' => true]
            )
            ->addAttribute(
                'order',
                'brickx_order_reference',
                ['type' => Table::TYPE_TEXT, 'length' => '255', 'nullable' => true]
            )
            ->addAttribute(
                'order',
                'brickx_order_memo',
                ['type' => Table::TYPE_TEXT, 'length' => Table::DEFAULT_TEXT_SIZE, 'nullable' => true]
            );
    }
}