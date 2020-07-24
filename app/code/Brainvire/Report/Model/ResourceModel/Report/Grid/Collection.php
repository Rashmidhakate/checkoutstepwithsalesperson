<?php
 namespace Brainvire\Report\Model\ResourceModel\Report\Grid;
 
use Magento\Framework\Api\Search\SearchResultInterface;
use Magento\Framework\Search\AggregationInterface;
use Brainvire\Report\Model\ResourceModel\Report\Collection as NewsCollection;
 
class Collection extends NewsCollection implements SearchResultInterface
{
     
    protected $aggregations;
 
 
    public function __construct(
        \Magento\Framework\Data\Collection\EntityFactoryInterface $entityFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        $mainTable,
        $eventPrefix,
        $eventObject,
        $resourceModel,
        $model = 'Magento\Framework\View\Element\UiComponent\DataProvider\Document',
        $connection = null,
        \Magento\Framework\Model\ResourceModel\Db\AbstractDb $resource = null
    ) {
        parent::__construct(
            $entityFactory,
            $logger,
            $fetchStrategy,
            $eventManager,
            $connection,
            $resource
        );
        $this->_eventPrefix = $eventPrefix;
        $this->_eventObject = $eventObject;
        $this->_init($model, $resourceModel);
        $this->setMainTable($mainTable);
    }

    protected function _initSelect()
    {
        parent::_initSelect();
       /* $this->getSelect()->joinLeft(
            ['selection' => $this->getTable('customer_entity')],
            'main_table.user_id = selection.entity_id',
            ['*']
        );
        $this->getSelect()->columns(new \Zend_Db_Expr('CONCAT_WS(" ", selection.firstname, selection.lastname) as user_name'));
        $this->addFilterToMap(
            'user_name',
            new \Zend_Db_Expr('CONCAT_WS(" ", selection.firstname, selection.lastname)')
        );
        $this->getSelect()->joinLeft(
            ['customer' => $this->getTable('customer_entity')],
            'main_table.customer_id = customer.entity_id',
            ['*']
        );
        $this->getSelect()->columns(new \Zend_Db_Expr('CONCAT_WS(" ", customer.firstname, customer.lastname) as customer_name'));
        $this->addFilterToMap(
            'customer_name',
            new \Zend_Db_Expr('CONCAT_WS(" ", customer.firstname, customer.lastname)')
        ); */
    }
 
     
    public function getAggregations()
    {
        return $this->aggregations;
    }
 
     
    public function setAggregations($aggregations)
    {
        $this->aggregations = $aggregations;
    }

 
    public function getSearchCriteria()
    {
        return null;
    }
 
     
    public function setSearchCriteria(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria = null)
    {
        return $this;
    }
 
     
    public function getTotalCount()
    {
        return $this->getSize();
    }
 
     
    public function setTotalCount($totalCount)
    {
        return $this;
    }
 
    public function setItems(array $items = null)
    {
        return $this;
    }
}