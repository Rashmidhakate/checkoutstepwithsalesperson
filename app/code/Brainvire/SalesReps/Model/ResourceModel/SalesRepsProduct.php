<?php
namespace Brainvire\SalesReps\Model\ResourceModel;

use \Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class SalesRepsProduct extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('brainvire_salesreps_product','record_id');
    }
}

