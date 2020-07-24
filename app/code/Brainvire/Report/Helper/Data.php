<?php


namespace Brainvire\Report\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper
{

    const EMAIL_IDENTIFIER_TEMPLATE = 'custom/email/report_template';

    protected $_inlineTranslation;

    protected $_storeManager;

    protected $_transportBuilder;
    /**
     * @param \Magento\Framework\App\Helper\Context $context
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone,
        \Brainvire\Report\Model\ReportFactory $collectionFactory
    ) {
        $this->_inlineTranslation = $inlineTranslation;
        $this->_storeManager = $storeManager;
        $this->_transportBuilder = $transportBuilder;
        $this->scopeConfig = $scopeConfig;
        $this->timezone = $timezone;
        $this->collectionFactory = $collectionFactory;
        parent::__construct($context);
    }

    public function getConfigValue($section_group_field)
    {
        return $this->scopeConfig->getValue(
            $section_group_field,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    public function mailSend($senderName,$senderEmail,$content,$filename,$errorDescription,$id) {

        $storeId = $this->_storeManager->getStore()->getStoreId();
        $templateName = $this->scopeConfig->getValue(
            self::EMAIL_IDENTIFIER_TEMPLATE,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $storeId
        );
        $reportCraetedAt = $this->getFormatedate($id);
        $recipientEmail = $this->scopeConfig->getValue(
                        'error_report/general/admin_email',
                        \Magento\Store\Model\ScopeInterface::SCOPE_STORE); // recipient email id
        $recipientName = 'Admin';

        if (!$senderEmail && !$recipientEmail) {
            return false;
        }
        $emailTemplateVariables = array(
            'customername'=> $senderName,
            'customeremail'=> $senderEmail,
            'errordescription'=> $errorDescription,
            'attachment'=> $filename,
            'created_at'=> $reportCraetedAt
        );
        //$emailTemplateVariables['message'] = 'This is a test message by meetanshi.';
        $this->_inlineTranslation->suspend();
        $this->_transportBuilder->setTemplateIdentifier($templateName)
        ->setTemplateOptions(
            [
                'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                'store' => $this->_storeManager->getStore()->getId(),
            ]
        )
        ->setTemplateVars($emailTemplateVariables)
        ->setFrom([
            'name' => $senderName,
            'email' => $senderEmail,
        ])
        ->addTo($recipientEmail, $recipientName)
        ->addAttachment($content,$filename); //Attachment goes here.
        try {
            $transport = $this->_transportBuilder->getTransport();
            $transport->sendMessage();
            $this->_inlineTranslation->resume();
        } catch (\Exception $e) {
            echo $e->getMessage(); die;
        }
    }

    public function getFormatedate($id){
        $reportCollection = $this->collectionFactory->create();
        $reportCollection->load($id);
        $date = $reportCollection->getCreatedAt();
        $created = $this->timezone->date(new \DateTime($date));
        $dateAsString = $created->format('M j, Y g:i:s A');
        //$createdAt = new \Zend_Date($date, 'MMM d, YYYY h:mm:ss A');
        return $dateAsString;
    }

}
