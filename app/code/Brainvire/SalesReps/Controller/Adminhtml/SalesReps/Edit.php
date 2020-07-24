<?php


namespace Brainvire\SalesReps\Controller\Adminhtml\SalesReps;

class Edit extends \Brainvire\SalesReps\Controller\Adminhtml\SalesReps
{

    protected $resultPageFactory;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->dataPersistor = $dataPersistor;
        parent::__construct($context, $coreRegistry);
    }

    /**
     * Edit action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        // 1. Get ID and create model
        $id = $this->getRequest()->getParam('salesreps_id');
        $model = $this->_objectManager->create(\Brainvire\SalesReps\Model\SalesReps::class);
        
        // 2. Initial checking
        if ($id) {
            $model->load($id);
            if (!$model->getSalesrepsId()) {
                $this->messageManager->addErrorMessage(__('This Salesreps no longer exists.'));
                /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
             //$this->_coreRegistry->register('salesreps_id', $model->getSalesrepsId());
        }
        //$data = $this->dataPersistor->get('brainvire_salesreps_salesreps');

        $this->_objectManager->get('Magento\Backend\Model\Session')->setFormData(true);
        if (!empty($data)) {
            $model->setData($data);
        }
        $this->_coreRegistry->register('brainvire_salesreps_salesreps', $model);
        // 3. Build edit form
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $this->initPage($resultPage)->addBreadcrumb(
            $id ? __('Edit Salesreps') : __('New Salesreps'),
            $id ? __('Edit Salesreps') : __('New Salesreps')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Salesrepss'));
        $resultPage->getConfig()->getTitle()->prepend($model->getSalesrepsId() ? __('Edit Salesreps %1', $model->getSalesrepsId()) : __('New Salesreps'));
        return $resultPage;
    }
}
