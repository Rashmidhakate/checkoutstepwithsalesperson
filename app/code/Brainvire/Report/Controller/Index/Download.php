<?php 
namespace Brainvire\Report\Controller\Index;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;
use Magento\Framework\App\Response\Http\FileFactory;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Exception\NoSuchEntityException;

class Download extends \Magento\Framework\App\Action\Action
{

    protected $resultRawFactory;
    protected $fileFactory;

    public function __construct(
        \Magento\Framework\Controller\Result\RawFactory $resultRawFactory,
        \Magento\Framework\App\Response\Http\FileFactory $fileFactory,
        \Brainvire\Report\Model\ReportFactory $collectionFactory,
        \Magento\Framework\Filesystem\Driver\File $fileDriver,
        \Magento\Store\Model\StoreManagerInterface $storeManagerInterface,
        \Magento\Framework\Filesystem\DirectoryList $directoryList,
        \Magento\Backend\App\Action\Context $context
    ) {
        $this->resultRawFactory      = $resultRawFactory;
        $this->fileFactory           = $fileFactory;
        $this->collectionFactory = $collectionFactory;
        $this->fileDriver = $fileDriver;
        $this->storeManagerInterface = $storeManagerInterface;
        $this->directoryList = $directoryList;
        parent::__construct($context);
    }
    public function execute()
    {
        $reportData = $this->getReportCollection();
        try{
            $fileName = $reportData->getAttachment(); // the name of the downloaded resource 
            $fileDirectoryPath = $this->directoryList->getPath(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA)."/customer_report/";
            $filePath = $fileDirectoryPath.$fileName;
            $this->fileFactory->create(
                $fileName,
                $this->fileDriver->fileGetContents($filePath),
                DirectoryList::MEDIA , //basedir
                'application/octet-stream',
                '' // content length will be dynamically calculated
            );
        }catch (\Exception $exception){
            // Add your own failure logic here
            var_dump($exception->getMessage());
            exit;
        }
        $resultRaw = $this->resultRawFactory->create();
        return $resultRaw;
    }

    public function getReportCollection(){
        $reportID = $this->getRequest()->getParam('report_id');
        $reportCollection = $this->collectionFactory->create()->load($reportID);
        return $reportCollection;
    }
}