<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Brainvire\Report\Block\Adminhtml\Report;

class Form extends \Magento\Backend\Block\Template
{

    /**
     * Block template.
     *
     * @var string
     */
    protected $_template = 'Brainvire_Report::report_info.phtml';
    protected $_storeManager;
    const URL_PATH_EDIT = 'customerreport/index/download';
    /**
     * AssignProducts constructor.
     *
     * @param \Magento\Backend\Block\Template\Context $context
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Brainvire\Report\Model\ReportFactory $collectionFactory,
        \Magento\Framework\UrlInterface $urlBuilder,
        \Magento\Framework\Data\Form\FormKey $formKey,
        \Magento\Framework\App\Request\Http $request,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone,
        array $data = []
    ) {
        $this->_storeManager = $storeManager;
        $this->collectionFactory = $collectionFactory;
        $this->urlBuilder = $urlBuilder;
        $this->formKey = $formKey;
        $this->request = $request;
        $this->request->setParam('form_key', $this->formKey->getFormKey());
        $this->timezone = $timezone;
        parent::__construct($context, $data);
    }

    public function getBaseUrl()
    {
        return $this->_storeManager->getStore()->getBaseUrl();
    }
    public function getReportCollection(){
        $reportID = $this->getRequest()->getParam('report_id');
        $reportCollection = $this->collectionFactory->create()->load($reportID);
        return $reportCollection;
    }
    public function getDownloadUrl(){
        $reportID = $this->getRequest()->getParam('report_id');
        return $this->urlBuilder->getUrl('customerreport/index/download', ['report_id' => $reportID,'_use_rewrite' => true, '_current' => true]);
    }
    public function getFormatedate($date){
        $created = $this->timezone->date(new \DateTime($date));
        $dateAsString = $created->format('M j, Y g:i:s A');
        //$createdAt = new \Zend_Date($date, 'MMM d, YYYY h:mm:ss A');
        return $dateAsString;
    }
}
