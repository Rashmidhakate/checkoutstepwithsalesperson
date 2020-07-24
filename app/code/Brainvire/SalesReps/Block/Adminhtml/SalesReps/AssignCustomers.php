<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Brainvire\SalesReps\Block\Adminhtml\SalesReps;

class AssignCustomers extends \Magento\Backend\Block\Template
{
    /**
     * Block template
     *
     * @var string
     */
    protected $_template = 'Brainvire_SalesReps::assign_customers.phtml';

    /**
     * @var \Magento\Catalog\Block\Adminhtml\Category\Tab\Product
     */
    protected $blockGrid;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /**
     * @var \Magento\Framework\Json\EncoderInterface
     */
    protected $jsonEncoder;

    /**
     * AssignProducts constructor.
     *
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Json\EncoderInterface $jsonEncoder
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Json\EncoderInterface $jsonEncoder,
        \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor,
        \Magento\Customer\Model\ResourceModel\Customer\CollectionFactory $customerFactory,
        array $data = []
    ) {
        $this->registry = $registry;
        $this->jsonEncoder = $jsonEncoder;
        $this->dataPersistor = $dataPersistor;
        $this->customerFactory = $customerFactory;
        parent::__construct($context, $data);
    }

    /**
     * Retrieve instance of grid block
     *
     * @return \Magento\Framework\View\Element\BlockInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getBlockGrid()
    {
        if (null === $this->blockGrid) {
            $this->blockGrid = $this->getLayout()->createBlock(
                \Brainvire\SalesReps\Block\Adminhtml\SalesReps\Tab\Customer::class,
                'salesperson.customer.grid'
            );
        }
        return $this->blockGrid;
    }

    /**
     * Return HTML of grid block
     *
     * @return string
     */
    public function getGridHtml()
    {
        return $this->getBlockGrid()->toHtml();
    }

    // /**
    //  * @return string
    //  */
    public function getCustomersJson()
    {
        $salesPersonCustomer = $this->getSalesperson()->getCustomerIds();
        $customerSelection = explode(",", $salesPersonCustomer);
        $customerCollection = $this->customerFactory->create();
        $customerCollection->addFieldToFilter('entity_id', ['in' => $customerSelection]);
        $customers = array();
        foreach($customerCollection as $customer){      
            $customers[$customer->getEntityId()]  = '';
        }       
        
        if (!empty($customers)) {
            return $this->jsonEncoder->encode($customers);
        }
        return '{}';
    }

    /**
     * Retrieve current category instance
     *
     * @return array|null
     */
    public function getSalesperson()
    {
        return $this->registry->registry('brainvire_salesreps_salesreps');
    }
}
