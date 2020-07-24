<?php


namespace Brainvire\Report\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\App\Action\Context;
use Magento\Customer\Model\Session;

class View extends Action
{

    protected $resultJsonFactory;
    /**
     * @var BreadcrumbsHelper
     */
    private $helper;

    /**
     * Index constructor.
     * @param Context $context
     * @param JsonFactory $resultJsonFactory
     * @param BreadcrumbsHelper $helper
     */
    public function __construct(
        Context   $context,
        JsonFactory $resultJsonFactory,
        Session $customerSession,
        \Magento\Framework\View\Result\PageFactory $pageFactory
    ) {
        $this->resultJsonFactory = $resultJsonFactory;
        $this->customerSession = $customerSession;
        $this->pageFactory = $pageFactory;
        parent::__construct($context);
    }


    public function execute()
    {
        return $this->pageFactory->create();
    }
}