<?php


namespace Brainvire\SalesReps\Model\ResourceModel\SalesRepsProduct;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            \Brainvire\SalesReps\Model\SalesRepsProduct::class,
            \Brainvire\SalesReps\Model\ResourceModel\SalesRepsProduct::class
        );
    }
}
