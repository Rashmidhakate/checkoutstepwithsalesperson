<?php


namespace Brainvire\SalesReps\Model;

use Brainvire\SalesReps\Api\SalesRepsRepositoryInterface;
use Brainvire\SalesReps\Api\Data\SalesRepsInterfaceFactory;
use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Framework\Api\ExtensibleDataObjectConverter;
use Brainvire\SalesReps\Model\ResourceModel\SalesReps\CollectionFactory as SalesRepsCollectionFactory;
use Brainvire\SalesReps\Model\ResourceModel\SalesReps as ResourceSalesReps;
use Brainvire\SalesReps\Api\Data\SalesRepsSearchResultsInterfaceFactory;
use Magento\Framework\Exception\NoSuchEntityException;

class SalesRepsRepository implements SalesRepsRepositoryInterface
{

    protected $dataObjectHelper;

    protected $salesRepsCollectionFactory;

    private $storeManager;

    protected $searchResultsFactory;

    protected $dataObjectProcessor;

    protected $extensionAttributesJoinProcessor;

    private $collectionProcessor;

    protected $extensibleDataObjectConverter;
    protected $resource;

    protected $dataSalesRepsFactory;

    protected $salesRepsFactory;


    /**
     * @param ResourceSalesReps $resource
     * @param SalesRepsFactory $salesRepsFactory
     * @param SalesRepsInterfaceFactory $dataSalesRepsFactory
     * @param SalesRepsCollectionFactory $salesRepsCollectionFactory
     * @param SalesRepsSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     * @param CollectionProcessorInterface $collectionProcessor
     * @param JoinProcessorInterface $extensionAttributesJoinProcessor
     * @param ExtensibleDataObjectConverter $extensibleDataObjectConverter
     */
    public function __construct(
        ResourceSalesReps $resource,
        SalesRepsFactory $salesRepsFactory,
        SalesRepsInterfaceFactory $dataSalesRepsFactory,
        SalesRepsCollectionFactory $salesRepsCollectionFactory,
        SalesRepsSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager,
        CollectionProcessorInterface $collectionProcessor,
        JoinProcessorInterface $extensionAttributesJoinProcessor,
        ExtensibleDataObjectConverter $extensibleDataObjectConverter
    ) {
        $this->resource = $resource;
        $this->salesRepsFactory = $salesRepsFactory;
        $this->salesRepsCollectionFactory = $salesRepsCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataSalesRepsFactory = $dataSalesRepsFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
        $this->collectionProcessor = $collectionProcessor;
        $this->extensionAttributesJoinProcessor = $extensionAttributesJoinProcessor;
        $this->extensibleDataObjectConverter = $extensibleDataObjectConverter;
    }

    /**
     * {@inheritdoc}
     */
    public function save(
        \Brainvire\SalesReps\Api\Data\SalesRepsInterface $salesReps
    ) {
        /* if (empty($salesReps->getStoreId())) {
            $storeId = $this->storeManager->getStore()->getId();
            $salesReps->setStoreId($storeId);
        } */
        
        $salesRepsData = $this->extensibleDataObjectConverter->toNestedArray(
            $salesReps,
            [],
            \Brainvire\SalesReps\Api\Data\SalesRepsInterface::class
        );
        
        $salesRepsModel = $this->salesRepsFactory->create()->setData($salesRepsData);
        
        try {
            $this->resource->save($salesRepsModel);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the salesReps: %1',
                $exception->getMessage()
            ));
        }
        return $salesRepsModel->getDataModel();
    }

    /**
     * {@inheritdoc}
     */
    public function get($salesRepsId)
    {
        $salesReps = $this->salesRepsFactory->create();
        $this->resource->load($salesReps, $salesRepsId);
        if (!$salesReps->getId()) {
            throw new NoSuchEntityException(__('SalesReps with id "%1" does not exist.', $salesRepsId));
        }
        return $salesReps->getDataModel();
    }

    /**
     * {@inheritdoc}
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->salesRepsCollectionFactory->create();
        
        $this->extensionAttributesJoinProcessor->process(
            $collection,
            \Brainvire\SalesReps\Api\Data\SalesRepsInterface::class
        );
        
        $this->collectionProcessor->process($criteria, $collection);
        
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        
        $items = [];
        foreach ($collection as $model) {
            $items[] = $model->getDataModel();
        }
        
        $searchResults->setItems($items);
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(
        \Brainvire\SalesReps\Api\Data\SalesRepsInterface $salesReps
    ) {
        try {
            $salesRepsModel = $this->salesRepsFactory->create();
            $this->resource->load($salesRepsModel, $salesReps->getSalesrepsId());
            $this->resource->delete($salesRepsModel);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the SalesReps: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($salesRepsId)
    {
        return $this->delete($this->get($salesRepsId));
    }
}
