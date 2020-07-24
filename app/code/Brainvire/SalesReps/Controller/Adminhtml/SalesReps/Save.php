<?php


namespace Brainvire\SalesReps\Controller\Adminhtml\SalesReps;

use Magento\Framework\Exception\LocalizedException;

class Save extends \Magento\Backend\App\Action
{

    protected $dataPersistor;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor,
        \Brainvire\SalesReps\Model\ResourceModel\SalesRepsProduct\CollectionFactory $salesRepsProductCollection
    ) {
        $this->dataPersistor = $dataPersistor;
        $this->salesRepsProductCollection = $salesRepsProductCollection;
        parent::__construct($context);
    }

    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();
        $generalFieldsetData = $data['general'];
        if(isset($data['salesperson_customers'])){
            $jsonData = json_decode($data['salesperson_customers']);// object format
            $array = get_object_vars($jsonData);
            $properties = array_keys($array);    
        }
       
        if ($data) {
            $id = $generalFieldsetData['salesreps_id'];
            $model = $this->_objectManager->create(\Brainvire\SalesReps\Model\SalesReps::class);
            if ($id) {
                $model->load($id);
                if (!$model->getSalesrepsId()) {
                    $this->messageManager->addErrorMessage(__('This Salesreps no longer exists.'));
                    return $resultRedirect->setPath('*/*/');
                }
            }
            $model->setSalesPersonCode($generalFieldsetData["sales_person_code"]);
            $model->setSalesPersonName($generalFieldsetData["sales_person_name"]);
            $model->setSalesPersonDivision($generalFieldsetData["sales_person_division"]);
            $model->setSalesManagerDivision($generalFieldsetData["sales_manager_division"]);
            $model->setSalesManagerCode($generalFieldsetData["sales_manager_code"]);
            if(isset($jsonData)){
                $model->setCustomerIds(implode(',', $properties)); 
            }
           
            
            //$model->setCustomerIds(implode(',', $generalFieldsetData["customer_ids"]));
            //$model->setData($data);
        
            try {
                $model->save();
                if(isset($data['general']['dynamic_rows']['dynamic_rows'])){
                    $dynamicRows = $data['general']['dynamic_rows']['dynamic_rows']; 
                    foreach($dynamicRows as $dynamisRow){
                        $salesRepsProductModel = $this->_objectManager->create(\Brainvire\SalesReps\Model\SalesRepsProduct::class);
                        if($id){
                            $salesRepsProductCollection = $this->salesRepsProductCollection->create();
                            $salesRepsProductCollection->addFieldToFilter('salesreps_id',array('eq' => $model->getSalesrepsId()));
                            if($salesRepsProductCollection->getSize()){
                                foreach($salesRepsProductCollection as $salesRepsProduct){
                                    $salesRepsProductModel = $this->_objectManager->create(\Brainvire\SalesReps\Model\SalesRepsProduct::class);
                                    if($dynamisRow["salesreps_id"] && $dynamisRow["selected_record_id"]){
                                        $salesRepsProductModel->load($dynamisRow["selected_record_id"]); 
                                    }
                                }
                            }
                        }
                        $salesRepsProductModel->setSalesrepsId($model->getSalesrepsId());
                        $salesRepsProductModel->setSku($dynamisRow["sku"]);
                        $salesRepsProductModel->setQty($dynamisRow["qty"]);
                        $salesRepsProductModel->setPrice($dynamisRow["price"]);
                        $salesRepsProductModel->setCustomerGroup($dynamisRow["customer_group"]);
                        if(isset($dynamisRow["image"])){
                            $salesRepsProductModel->setImage($dynamisRow["image"][0]["name"]);
                        }
                        $salesRepsProductModel->save();
                    }
                }
              
                $this->messageManager->addSuccessMessage(__('You saved the Salesreps.'));
                //$this->dataPersistor->clear('brainvire_salesreps_salesreps');
                $this->_objectManager->get('Magento\Backend\Model\Session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['salesreps_id' => $model->getSalesrepsId()]);
                }
                return $resultRedirect->setPath('*/*/');
            }catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the Attachment.'));
            }
        
            //$this->dataPersistor->set('brainvire_salesreps_salesreps', $generalFieldsetData);
            return $resultRedirect->setPath('*/*/edit', ['salesreps_id' => $this->getRequest()->getParam('salesreps_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}
