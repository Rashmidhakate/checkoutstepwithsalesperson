<?php
namespace Brainvire\Checkoutstep\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Controller\ResultFactory;
use Magento\Catalog\Api\ProductRepositoryInterface;
 
class Addtocart extends \Magento\Framework\App\Action\Action
{
 
  protected $_resultPageFactory;

  public function __construct(
      Context $context, 
      \Magento\Catalog\Model\Product $productFactory,
      \Magento\Checkout\Model\Cart $cart,
      \Magento\Catalog\Model\ProductRepository $productRepository,
      \Magento\Framework\ObjectManagerInterface $objectmanager,
      PageFactory $resultPageFactory
      )
  {
     parent::__construct($context);
     $this->productFactory = $productFactory;
     $this->cart = $cart;
     $this->_resultPageFactory = $resultPageFactory;
     $this->_productRepository = $productRepository;
     $this->_objectManager = $objectmanager;
  }
 
  public function execute()
  {
    $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
    $product_entity = $this->getRequest()->getParam("product_entity");
    $product_qty = $this->getRequest()->getParam("product_qty");
    
    $productcollection = $this->productFactory
                               ->getCollection()
                               ->addAttributeToSelect('*')
                               ->addFieldToFilter('entity_id',$product_entity);
    try{
      foreach($productcollection as $product)
      {
        $params = array();
        $params['product'] = $product->getId();
        $params['qty'] = $product_qty[$product->getId()];

        if ($product) {
          $this->cart->addProduct($product->getId(), $params);
        }
      }
      $this->cart->getQuote()->setTotalsCollectedFlag(false);
      $this->cart->save();
      if (!$this->cart->getQuote()->getHasError()) {
        $message = __(
        'Items added to your shopping cart.'
        );
        $this->messageManager->addSuccessMessage($message);
      }
      $result['result'] = 'success';
    }catch(\Exception $e){
      $message = $e->getMessage();
      $this->messageManager->addException($e,$message);
      $result['result'] = 'failed';
    }
    $this->getResponse()->representJson(
            $this->_objectManager->get(\Magento\Framework\Json\Helper\Data::class)->jsonEncode($result)
        );
  }
}