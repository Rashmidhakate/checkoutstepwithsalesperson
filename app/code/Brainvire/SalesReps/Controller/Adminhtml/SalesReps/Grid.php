<?php 

namespace Brainvire\SalesReps\Controller\Adminhtml\SalesReps;

class Grid extends \Magento\Backend\App\Action{
    /**
     * @var \Magento\Framework\Controller\Result\RawFactory
     */
    protected $resultRawFactory;

    /**
     * @var \Magento\Framework\View\LayoutFactory
     */
    protected $layoutFactory;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Controller\Result\RawFactory $resultRawFactory
     * @param \Magento\Framework\View\LayoutFactory $layoutFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Controller\Result\RawFactory $resultRawFactory,
        \Magento\Framework\View\LayoutFactory $layoutFactory
    ) {
        parent::__construct($context);
        $this->resultRawFactory = $resultRawFactory;
        $this->layoutFactory = $layoutFactory;
    }

    /**
     * Grid Action
     * Display list of products related to current category
     *
     * @return \Magento\Framework\Controller\Result\Raw
     */
    public function execute()
    {
        $item = $this->_initItem(true);
        if (!$item) {
            /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
            $resultRedirect = $this->resultRedirectFactory->create();
            return $resultRedirect->setPath('brainvire_salesreps/salesreps/new', ['_current' => true, 'salesreps_id' => null]);
        }
        /** @var \Magento\Framework\Controller\Result\Raw $resultRaw */
        $resultRaw = $this->resultRawFactory->create();
        return $resultRaw->setContents(
            $this->layoutFactory->create()->createBlock(
                 \Brainvire\SalesReps\Block\Adminhtml\SalesReps\Tab\Customer::class,
                'salesperson.customer.grid'
            )->toHtml()
        );
    }

     protected function _initItem($getRootInstead = false)
    {
        $id = (int)$this->getRequest()->getParam('salesreps_id', false);
        $model = $this->_objectManager->create(\Brainvire\SalesReps\Model\SalesReps::class);

        if ($id) {
            $model->load($id);            
        }
        $this->_objectManager->get('Magento\Framework\Registry')->register('brainvire_salesreps_salesreps', $model);
        return $model;
    }   


}