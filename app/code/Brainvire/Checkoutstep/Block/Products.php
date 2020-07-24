<?php
namespace Brainvire\Checkoutstep\Block;
 
use Magento\Framework\View\Element\Template;
use Magento\Catalog\Model\ProductFactory;
use Magento\Catalog\Helper\Image;
use Magento\Store\Model\StoreManager;
use Magento\Framework\UrlInterface;
use Magento\Framework\Json\Helper\Data as jsondata;
 
class Products extends Template
{
  /**
  * @var array|\Magento\Checkout\Block\Checkout\LayoutProcessorInterface[]
  */
  protected $layoutProcessors;
  protected $productFactory;
  protected $imageHelper;
  protected $listProduct;
  protected $_storeManager;

  public function __construct(
    Template\Context $context,
    ProductFactory $productFactory,
    StoreManager $storeManager,
    Image $imageHelper,
    UrlInterface $urlBuilder,
    jsondata $jsonHelper,
    array $data = []
  ) {
    $this->productFactory = $productFactory;
    $this->imageHelper = $imageHelper;
    $this->_storeManager = $storeManager;
    $this->urlBuilder = $urlBuilder;
    $this->jsonHelper = $jsonHelper;
    parent::__construct($context, $data);
  }

  public function getCollection()
   {
       return $this->productFactory->create()
           ->getCollection()
           ->addAttributeToSelect('*');
   }


  public function getProductData(){

      $product = $this->productFactory->create()->load(1);

      $productData = [
         'entity_id' => $product->getId(),
         'name' => $product->getName(),
         'price' => '$' . $product->getPrice(),
         'src' => $this->imageHelper->init($product, 'product_base_image')->getUrl(),
      ];
      return $this->jsonHelper->jsonEncode($productData);
  }

}