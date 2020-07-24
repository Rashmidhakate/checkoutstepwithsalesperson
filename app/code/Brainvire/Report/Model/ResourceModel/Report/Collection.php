<?php
namespace Brainvire\Report\Model\ResourceModel\Report;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
	protected $_idFieldName = 'report_id';
	protected $_eventPrefix = 'brainvire_report_brainvire_customer_report_collection';
	protected $_eventObject = 'brainvire_customer_report_collection';

	/**
	 * Define resource model
	 *
	 * @return void
	 */
	protected function _construct()
	{
		$this->_init('Brainvire\Report\Model\Report', 'Brainvire\Report\Model\ResourceModel\Report');
	}



}