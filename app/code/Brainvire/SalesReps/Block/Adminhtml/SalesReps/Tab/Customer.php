<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Product in category grid
 *
 * @author      Magento Core Team <core@magentocommerce.com>
 */
namespace Brainvire\SalesReps\Block\Adminhtml\SalesReps\Tab;

use Magento\Backend\Block\Widget\Grid;
use Magento\Backend\Block\Widget\Grid\Column;
use Magento\Backend\Block\Widget\Grid\Extended;
use Magento\Catalog\Model\Product\Attribute\Source\Status;
use Magento\Catalog\Model\Product\Visibility;
use Magento\Framework\App\ObjectManager;

class Customer extends \Magento\Backend\Block\Widget\Grid\Extended
{
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    /**
     * @var \Magento\Catalog\Model\ProductFactory
     */
    protected $_productFactory;

    /**
     * @var Status
     */
    private $status;

    /**
     * @var Visibility
     */
    private $visibility;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Helper\Data $backendHelper
     * @param \Magento\Catalog\Model\ProductFactory $productFactory
     * @param \Magento\Framework\Registry $coreRegistry
     * @param array $data
     * @param Visibility|null $visibility
     * @param Status|null $status
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Magento\Customer\Model\ResourceModel\Customer\CollectionFactory $customerCollectionFactory,
        \Magento\Customer\Api\GroupRepositoryInterface $groupRepositoryInterface,
        \Magento\Framework\Registry $coreRegistry,
        array $data = [],
        Visibility $visibility = null,
        Status $status = null
    ) {
        $this->customerCollectionFactory = $customerCollectionFactory;
        $this->groupRepositoryInterface = $groupRepositoryInterface;
        $this->_coreRegistry = $coreRegistry;
        $this->visibility = $visibility ?: ObjectManager::getInstance()->get(Visibility::class);
        $this->status = $status ?: ObjectManager::getInstance()->get(Status::class);
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('salesperson_customers');
        $this->setDefaultSort('entity_id');
        $this->setUseAjax(true);
    }

    /**
     * @return array|null
     */
    public function getSalesperson()
    {
        return $this->_coreRegistry->registry('brainvire_salesreps_salesreps');
    }

    /**
     * @param Column $column
     * @return $this
     */
    protected function _addColumnFilterToCollection($column)
    {
        // Set custom filter for in category flag
        if ($column->getId() == 'in_salesperson') {
            $customerIds = $this->_getSelectedCustomers();
            if (empty($customerIds)) {
                $customerIds = 0;
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('entity_id', ['in' => $customerIds]);
            } elseif (!empty($productIds)) {
                $this->getCollection()->addFieldToFilter('entity_id', ['nin' => $customerIds]);
            }
        } else {
            parent::_addColumnFilterToCollection($column);
        }
        return $this;
    }

    /**
     * @return Grid
     */
    protected function _prepareCollection()
    {
        if ($this->getSalesperson()->getSalesrepsId()) {
            $this->setDefaultFilter(['in_salesperson' => 1]);
        }
        $collection = $this->customerCollectionFactory->create();
        //$collection->addFieldToFilter('group_id', array('in' => array(4,5,6,7,8,9)));
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    /**
     * @return Extended
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'in_salesperson',
            [
                'type' => 'checkbox',
                'name' => 'in_salesperson',
                'values' => $this->_getSelectedCustomers(),
                'index' => 'entity_id',
                'header_css_class' => 'col-select col-massaction',
                'column_css_class' => 'col-select col-massaction'
            ]
        );
        $this->addColumn(
            'entity_id',
            [
                'header' => __('ID'),
                'sortable' => true,
                'index' => 'entity_id',
                'header_css_class' => 'col-id',
                'column_css_class' => 'col-id'
            ]
        );
        $this->addColumn(
            'firstname',
            [
                'header' => __('Name'),
                'sortable' => true,
                'index' => 'firstname',
                'header_css_class' => 'col-id',
                'column_css_class' => 'col-id'
            ]
        );
        $this->addColumn(
            'email',
            [
                'header' => __('Email'),
                'sortable' => true,
                'index' => 'email',
                'header_css_class' => 'col-id',
                'column_css_class' => 'col-id'
            ]
        );
        $this->addColumn(
            'group_id',
            [
                'header' => __('Group'),
                'sortable' => true,
                'index' => 'group_id',
                'header_css_class' => 'col-id',
                'column_css_class' => 'col-id'
            ]
        );
    

        return parent::_prepareColumns();
    }

    /**
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('brainvire_salesreps/salesreps/grid', ['_current' => true]);
    }

    /**
     * @return array
     */
    protected function _getSelectedCustomers()
    {
        $customers = $this->getRequest()->getPost('selected_customers');
        if ($customers === null) {   
            $salesPersonCustomer = $this->getSalesperson()->getCustomerIds();
            $customerSelection = explode(",", $salesPersonCustomer);
            $customerCollection = $this->customerCollectionFactory->create();
            $customerCollection->addFieldToFilter('entity_id', ['in' => $customerSelection]);
            $customers = array();
            foreach($customerCollection as $customer){      
                $customers[]  = $customer->getEntityId();
            }     
        }
        return $customers;
    }
}
