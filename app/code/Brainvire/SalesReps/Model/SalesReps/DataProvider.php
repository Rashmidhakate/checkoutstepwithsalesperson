<?php


namespace Brainvire\SalesReps\Model\SalesReps;

use Brainvire\SalesReps\Model\ResourceModel\SalesReps\CollectionFactory;
use Magento\Framework\App\Request\DataPersistorInterface;

class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{

    protected $collection;

    protected $dataPersistor;

    protected $loadedData;

    /**
     * Constructor
     *
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param DataPersistorInterface $dataPersistor
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        DataPersistorInterface $dataPersistor,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Brainvire\SalesReps\Model\ResourceModel\SalesRepsProduct\CollectionFactory $salesRepsProductCollection,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $collectionFactory->create();
        $this->dataPersistor = $dataPersistor;
        $this->storeManager = $storeManager;
        $this->salesRepsProductCollection = $salesRepsProductCollection;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        $items = $this->collection->getItems();
        foreach ($items as $model) {
            $salesRepsProductCollection = $this->salesRepsProductCollection->create();
            $salesRepsProductCollection->addFieldToFilter('salesreps_id',array('eq'=>$model->getData('salesreps_id')));
            //echo $model->getData('salesreps_id');
            //$this->loadedData[$model->getId()] = $model->getData();
            $this->loadedData[$model->getId()]['general'] = $model->getData();
            $i = 0;
            if($salesRepsProductCollection->getSize() > 0){
                foreach($salesRepsProductCollection as $value){
                    $this->loadedData[$model->getId()]['general']['dynamic_rows']['dynamic_rows'][$i]['selected_record_id'] = $value->getRecordId();
                    $this->loadedData[$model->getId()]['general']['dynamic_rows']['dynamic_rows'][$i]['salesreps_id'] = $model->getData('salesreps_id');
                    $this->loadedData[$model->getId()]['general']['dynamic_rows']['dynamic_rows'][$i]['sku'] = $value->getSku();
                    $this->loadedData[$model->getId()]['general']['dynamic_rows']['dynamic_rows'][$i]['qty'] = $value->getQty();
                    $this->loadedData[$model->getId()]['general']['dynamic_rows']['dynamic_rows'][$i]['price'] = $value->getPrice();
                    $this->loadedData[$model->getId()]['general']['dynamic_rows']['dynamic_rows'][$i]['customer_group'] = $value->getCustomerGroup();

                    if($value->getImage()){
                        //$m = $this->loadedData[$model->getId()]['general']['dynamic_rows']['dynamic_rows'][$i];
                        $this->loadedData[$model->getId()]['general']['dynamic_rows']['dynamic_rows'][$i]['image'][0]['name'] = $value->getImage();
                        $this->loadedData[$model->getId()]['general']['dynamic_rows']['dynamic_rows'][$i]['image'][0]['url'] = $this->getMediaUrl().$value->getImage();
                        $fullData = $this->loadedData;
                    }
                    $i++;
                }
            }
        }
        // echo "<pre>";
        // print_r($this->loadedData);
        // exit;
        return $this->loadedData;
    }

    public function getMediaUrl()
    {
        $mediaUrl = $this->storeManager->getStore()
        ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA).'salesres/';
        return $mediaUrl;
    }
}
