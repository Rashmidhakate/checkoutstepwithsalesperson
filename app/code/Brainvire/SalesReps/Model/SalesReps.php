<?php


namespace Brainvire\SalesReps\Model;

use Brainvire\SalesReps\Api\Data\SalesRepsInterfaceFactory;
use Magento\Framework\Api\DataObjectHelper;
use Brainvire\SalesReps\Api\Data\SalesRepsInterface;

class SalesReps extends \Magento\Framework\Model\AbstractModel
{

    protected $dataObjectHelper;

    protected $_eventPrefix = 'brainvire_salesreps_salesreps';
    protected $salesrepsDataFactory;


    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param SalesRepsInterfaceFactory $salesrepsDataFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param \Brainvire\SalesReps\Model\ResourceModel\SalesReps $resource
     * @param \Brainvire\SalesReps\Model\ResourceModel\SalesReps\Collection $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        SalesRepsInterfaceFactory $salesrepsDataFactory,
        DataObjectHelper $dataObjectHelper,
        \Brainvire\SalesReps\Model\ResourceModel\SalesReps $resource,
        \Brainvire\SalesReps\Model\ResourceModel\SalesReps\Collection $resourceCollection,
        array $data = []
    ) {
        $this->salesrepsDataFactory = $salesrepsDataFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Retrieve salesreps model with salesreps data
     * @return SalesRepsInterface
     */
    public function getDataModel()
    {
        $salesrepsData = $this->getData();
        
        $salesrepsDataObject = $this->salesrepsDataFactory->create();
        $this->dataObjectHelper->populateWithArray(
            $salesrepsDataObject,
            $salesrepsData,
            SalesRepsInterface::class
        );
        
        return $salesrepsDataObject;
    }
}
