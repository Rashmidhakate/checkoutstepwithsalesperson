<?php
namespace Brainvire\Report\Model;

class Report extends \Magento\Framework\Model\AbstractModel implements \Magento\Framework\DataObject\IdentityInterface
{
	const CACHE_TAG = 'brainvire_customer_report';

	protected $_cacheTag = 'brainvire_customer_report';

	protected $_eventPrefix = 'brainvire_report';

	protected function _construct()
	{
		$this->_init('Brainvire\Report\Model\ResourceModel\Report');
	}

	public function getIdentities()
	{
		return [self::CACHE_TAG . '_' . $this->getId()];
	}

	public function getDefaultValues()
	{
		$values = [];

		return $values;
	}
}