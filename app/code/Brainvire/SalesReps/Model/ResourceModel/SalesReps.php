<?php


namespace Brainvire\SalesReps\Model\ResourceModel;

class SalesReps extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('brainvire_salesreps_salesreps', 'salesreps_id');
    }
}
