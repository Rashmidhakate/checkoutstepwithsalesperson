<?php


namespace Brainvire\SalesReps\Model\ResourceModel\SalesReps;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{

    /**
     * Define resource model
     *
     * @return void
     */

    protected $_idFieldName = 'salesreps_id';  
    protected $_eventPrefix = 'brainvire_salesreps_brainvire_salesreps_salesreps_collection';
    protected $_eventObject = 'brainvire_salesreps_salesreps_collection';  
    protected function _construct()
    {
        $this->_init(
            \Brainvire\SalesReps\Model\SalesReps::class,
            \Brainvire\SalesReps\Model\ResourceModel\SalesReps::class
        );
    }

     /**
     * @inheritdoc
     */
    protected function _initSelect()
    {
        parent::_initSelect();
        return $this;
    }
}
