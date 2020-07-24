<?php
namespace Brainvire\Checkoutstep\Controller\Index;
 
use Magento\Catalog\Model\ProductFactory;
use Magento\Catalog\Helper\Image;
use Magento\Store\Model\StoreManager;
use Magento\Framework\UrlInterface;
 
class Products extends \Magento\Framework\App\Action\Action
{
   protected $productFactory;
   protected $imageHelper;
   protected $listProduct;
   protected $_storeManager;
 
   public function __construct(
       \Magento\Framework\App\Action\Context $context,
       \Magento\Framework\Data\Form\FormKey $formKey,
       ProductFactory $productFactory,
       \Magento\Catalog\Model\ProductRepository $productRepository,
       StoreManager $storeManager,
       Image $imageHelper,
       \Brainvire\SalesReps\Model\ResourceModel\SalesRepsProduct\CollectionFactory $salesRepsProductCollection,
       \Magento\Framework\Json\Helper\Data $jsonHelper,
      UrlInterface $urlBuilder
   )
   {
       $this->productFactory = $productFactory;
       $this->imageHelper = $imageHelper;
       $this->_storeManager = $storeManager;
       $this->productRepository = $productRepository;
       $this->urlBuilder = $urlBuilder;
       $this->salesRepsProductCollection = $salesRepsProductCollection;
       $this->jsonHelper = $jsonHelper;
       parent::__construct($context);
   }
 
   public function execute()
   {
  
      $salesRepsProductCollection = $this->salesRepsProductCollection->create();
      $skulist = [];
      $productData = [];
      foreach($salesRepsProductCollection as $salesRepsProduct){
          $salesPersonSku = $salesRepsProduct->getSku();
          $skulist[] = $salesPersonSku;
      }
      $productCollection = $this->productFactory->create()->getCollection();
      $productCollection->addAttributeToSelect('*');
      $productCollection->addAttributeToFilter('sku',array('in' => $skulist));
      foreach ($productCollection as $product) {
        if(in_array($product->getSku(), $skulist)){
          $productData[] = [
            'entity_id' => $product->getId(),
            'name' => $product->getName(),
            'price' => '$' . $product->getPrice(),
            'src' => $this->imageHelper->init($product, 'product_base_image')->getUrl(),
          ];
        }
      }
      if(count($productData) > 0){
        $data = [
          'message' => "",
          'products' => $productData
        ];
      }else{
        $data = [
          'message' => "No products Found for salesperson.",
          'products' => ""
        ];
      }
      
      echo $this->jsonHelper->jsonEncode($data);
      return;
   }
}