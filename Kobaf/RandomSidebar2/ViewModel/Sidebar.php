<?php
    
    declare(strict_types=1);
    
    namespace Kobaf\RandomSidebar2\ViewModel;
    
    use Magento\Framework\View\Element\Block\ArgumentInterface;
    
    class Sidebar implements ArgumentInterface
    {
        private $productCollectionFactory;
        private $imageHelper;
    
    
        /**
         * Sidebar constructor
         *
         * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
         * @param \Magento\Catalog\Helper\Image $imageHelper
         */
        public function __construct(
            \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
            \Magento\Catalog\Helper\Image $imageHelper
        )
        {
            
            $this->productCollectionFactory = $productCollectionFactory;
            $this->imageHelper = $imageHelper;
            
        }
    
    
        /**
         *   Fetch product collection
         *
         * @return \Magento\Framework\DataObject[]
         * @throws \Magento\Framework\Exception\LocalizedException
         */
        
        public function getProductCollection()
        {
            $collection = $this->productCollectionFactory->create();
           
            $collection->addAttributeToSelect(['name', 'image'])
                        ->addFinalPrice()
                        ->addUrlRewrite()
                        ->setPageSize(3) //  3 products
                        ->getSelect()
                        ->order('RAND()');
            
            $collection->addFieldToFilter('visibility', ['neq'=>"1"]);
            $collection->addAttributeToFilter('status', ['eq' => \Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_ENABLED]);
            
            return $collection->getItems();
        }
        
        /**
         *  Prepare product image URL
         *
         * @param $product
         * @param $imageFile
         * @return string
         */
        public function getImageUrl($product, $imageFile)
        {
            return
                $this->imageHelper->init($product, 'product_page_image_small')
                    ->setImageFile($imageFile)
                    ->getUrl();
            
        }
    
    }
  
    
