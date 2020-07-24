<?php
namespace Brainvire\Report\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Filesystem;
use Magento\MediaStorage\Model\File\UploaderFactory;

class Save extends Action
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
        \Brainvire\Report\Model\ReportFactory $collectionFactory,
        \Magento\Framework\View\Result\PageFactory $pageFactory,
        Filesystem $filesystem,
        \Brainvire\Report\Helper\Data $helperData,
        \Magento\Framework\Filesystem\DirectoryList $directoryList,
        \Magento\Framework\Filesystem\Driver\File $fileDriver,
        UploaderFactory $fileUploader
    ) {
        $this->resultJsonFactory = $resultJsonFactory;
        $this->collectionFactory = $collectionFactory;
        $this->pageFactory = $pageFactory;
        $this->filesystem           = $filesystem;
        $this->helperData         = $helperData;
        $this->fileDriver = $fileDriver;
        $this->directoryList = $directoryList;
        $this->fileUploader         = $fileUploader;
        $this->mediaDirectory = $filesystem->getDirectoryWrite(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA);
        parent::__construct($context);
    }


    public function execute()
    {
        $params = $this->getRequest()->getPostValue();
        $files = $this->getRequest()->getFiles();
        $customerFirstname = $params['customer-firstname'];
        $customerLastname = $params['customer-lastname'];
        $customerId = $params['customer-id'];
        $customerEmail = $params['customer-email'];
        $errorDescription = $params['error-description'];
        $attachment = $files['attachment']['name'];
        $filename = preg_replace('/\s+/', '_', $attachment);
        try{
            if(isset($attachment) && $attachment != '') {

                $file = $this->getRequest()->getFiles('attachment');
                $fileName = ($file && array_key_exists('name', $file)) ? $file['name'] : null;

                if ($file && $fileName) {
                    $target = $this->mediaDirectory->getAbsolutePath('customer_report');        

                    /** @var $uploader \Magento\MediaStorage\Model\File\Uploader */
                    $uploader = $this->fileUploader->create(['fileId' => 'attachment']);

                    // set allowed file extensions
                    $uploader->setAllowedExtensions(['jpg', 'pdf', 'doc', 'png', 'zip']);

                    // allow folder creation
                    $uploader->setAllowCreateFolders(true);

                    // rename file name if already exists 
                    $uploader->setAllowRenameFiles(true);

                    // upload file in the specified folder
                    $result = $uploader->save($target);

                    if ($result['file']) {
                        $this->messageManager->addSuccessMessage(__('File has been successfully uploaded.')); 
                    }
                }
            }
            $reportCollection = $this->collectionFactory->create();
            $reportCollection->setCustomerFirstname($customerFirstname);
            $reportCollection->setCustomerLastname($customerLastname);
            $reportCollection->setCustomerId($customerId);
            $reportCollection->setErrorDescription($errorDescription);
            $reportCollection->setAttachment($filename);
            $reportCollection->save();
            $lastInsrtedId = $reportCollection->getId();

            $fileDirectoryPath = $this->directoryList->getPath(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA)."/customer_report/";
            $filePath = $fileDirectoryPath.$filename;
            //echo $filePath ;
            $content = $this->fileDriver->fileGetContents($filePath);
            $customerName = $customerFirstname." ".$customerLastname;
            $this->helperData->mailSend($customerName,$customerEmail,$content,$filename,$errorDescription,$lastInsrtedId);
            $this->messageManager->addSuccessMessage(__('Record saved successfully.'));
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }        
        
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        return $resultRedirect->setPath('customerreport/index/index');
        //return $this->pageFactory->create();
    }

}