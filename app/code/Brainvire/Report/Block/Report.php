<?php

namespace Brainvire\Report\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

class Report extends Template
{
	protected $_storeManager;
	public function __construct(
		Context $context,
		\Magento\Store\Model\StoreManagerInterface $storeManager,
		\Magento\Customer\Model\Session $customerSession,
		\Magento\Customer\Model\CustomerFactory $customerFactory,
		\Brainvire\Report\Model\ResourceModel\Report\CollectionFactory $collectionFactory,
        \Magento\Framework\UrlInterface $urlBuilder,
        \Magento\Framework\Data\Form\FormKey $formKey,
        \Magento\Framework\App\Request\Http $request,
        \Brainvire\Report\Model\ReportFactory $reportFactory,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone,
		array $data = []
	) {
		$this->_storeManager = $storeManager;
		$this->customerSession = $customerSession;
		$this->customerFactory = $customerFactory;
		$this->collectionFactory = $collectionFactory;
		$this->urlBuilder = $urlBuilder;
		$this->formKey = $formKey;
		$this->request = $request;
		$this->reportFactory = $reportFactory;
		$this->timezone = $timezone;
		parent::__construct($context, $data);
	}

	 /**
     * @inheritDoc
     */
    protected function _construct()
    {
        parent::_construct();
        $this->pageConfig->getTitle()->set(__('Errors Reported'));
    }

     /**
     * @inheritDoc
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if ($this->getReportCollection()) {
            $pager = $this->getLayout()->createBlock(
                \Magento\Theme\Block\Html\Pager::class,
                'report.an.error.pager'
            )->setCollection(
                $this->getReportCollection()
            );
            $this->setChild('pager', $pager);
            $this->getReportCollection()->load();
        }
        return $this;
    }

   /**
     * Get Pager child block output
     *
     * @return string
     */
    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }

	public function getBaseUrl()
	{
		return $this->_storeManager->getStore()->getBaseUrl();
	}

	public function getReportCollection(){
		$customerId = $this->getCustomerSession()->getCustomerData()->getId();
		$reportCollection = $this->collectionFactory->create();
		$reportCollection->addFieldToFilter(
                'customer_id',
                ['eq' => $customerId]
        );
		return $reportCollection;
	}

	public function getCustomerSession(){
		return $this->customerSession;
	}

	public function getCustomerData($id){
		$customer = $this->customerFactory->create();
		$customer->load($id);
		return $customer;
	}

	public function getViewUrl($reportID){
		return $this->urlBuilder->getUrl('customerreport/index/view', ['report_id' => $reportID,'_use_rewrite' => true, '_current' => true]);
	}

	public function getViewReportCollection(){
		$reportID = $this->getRequest()->getParam('report_id');
		$reportCollection = $this->reportFactory->create()->load($reportID);
		return $reportCollection;
	}

	public function getDownloadUrl($reportID){
		return $this->urlBuilder->getUrl('customerreport/index/download', ['report_id' => $reportID,'_use_rewrite' => true, '_current' => true]);
	}

	public function getFormatedate($date){
		$created = $this->timezone->date(new \DateTime($date));
        $dateAsString = $created->format('M j, Y g:i:s A');
        //$createdAt = new \Zend_Date($date, 'MMM d, YYYY h:mm:ss A');
        return $dateAsString;
	}
}