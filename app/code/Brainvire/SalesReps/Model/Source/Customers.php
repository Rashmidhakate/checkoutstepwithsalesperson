<?php

namespace Brainvire\SalesReps\Model\Source;

class Customers implements \Magento\Framework\Option\ArrayInterface
{

	/**
	* @param \Magento\Backend\App\Action\Context $context
	* @param \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor
	*/
	public function __construct(
		\Magento\Customer\Model\ResourceModel\Customer\CollectionFactory $collectionFactory,
		\Magento\Customer\Api\GroupRepositoryInterface $groupRepositoryInterface
	) {
		$this->collectionFactory = $collectionFactory;
		$this->groupRepositoryInterface = $groupRepositoryInterface;
	}

    public function toOptionArray()
    {
		$customerObj = $this->collectionFactory->create();
		$customerObj->addFieldToFilter('group_id', array('in' => array(4,5,6,7,8,9)));
		$customerArray = [];
		foreach ($customerObj as $customer) {
			$customerArray[] = ['value' => $customer->getEntityId(), 'label' => $customer->getEmail()];
		}
        return $customerArray;
    }
}