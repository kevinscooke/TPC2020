<?php
/**
 * Helper file to get data.
 *
 * @category  Site Search & Navigation
 * @package   Root_Mega_Menu
 * @author    Developer RootwaysInc <developer@rootways.com>
 * @copyright 2017 Rootways Inc. (https://www.rootways.com)
 * @license   Rootways Custom License
 * @link      https://www.rootways.com/shop/media/extension_doc/license_agreement.pdf
 */
namespace Rootways\Megamenu\Helper;

use Magento\Framework\View\Result\PageFactory;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager;
    
    /**
     * @var \Magento\Catalog\Helper\Category
     */
    protected $_categoryHelper;
    
    /**
     * @var \Magento\Catalog\Model\CategoryFactory
     */
    protected $_categoryFactory;
    
    /**
     * @var \Magento\Catalog\Model\Indexer\Category\Flat\State
     */
    protected $_categoryFlatConfig;
    
    /**
     * @var \Magento\Cms\Model\Template\FilterProvider
     */
    protected $_filterProvider;
    
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;
    
    /**
     * Data Helper.
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Catalog\Helper\Category $categoryHelper
     * @param \Magento\Catalog\Model\CategoryFactory $categoryFactory,
     * @param \Magento\Catalog\Model\Indexer\Category\Flat\State $categoryFlatState,
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @param \Magento\Cms\Model\Template\FilterProvider $filterProvider
     * @param PageFactory $resultPageFactory,
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Catalog\Helper\Category $categoryHelper,
        \Magento\Catalog\Model\CategoryFactory $categoryFactory,
        \Magento\Catalog\Model\Indexer\Category\Flat\State $categoryFlatState,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Cms\Model\Template\FilterProvider $filterProvider,
        PageFactory $resultPageFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        $this->_storeManager = $storeManager;
        $this->_objectManager= $objectManager;
        $this->_categoryFactory = $categoryFactory;
        $this->_categoryFlatConfig = $categoryFlatState;
        $this->_categoryHelper = $categoryHelper;
        $this->resultPageFactory = $resultPageFactory;
        $this->_filterProvider = $filterProvider;
        
        parent::__construct($context);
    }
    
    public function getConfig($config_path)
    {
        return $this->scopeConfig->getValue(
            $config_path,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
    
    public function getCssDir()
    {
        return BP.'/pub/media/rootways/megamenu/';
    }
    
    public function getStoreCss()
    {
        $base_media = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        return $base_media. 'rootways/megamenu/' . 'menu_' . $this->_storeManager->getStore()->getCode() . '.css';
    }
    
    public function surl()
    {
        return "aHR0cHM6Ly9yb290d2F5cy5jb20vc2hvcC9tMl9leHRsYy5waHA=";
    }
    
    public function getMagentoVersion()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $productMetadata = $objectManager->get('Magento\Framework\App\ProductMetadataInterface');
        $version = $productMetadata->getVersion();
        return $version;
    }
    
    public function manageMasonry()
    {
        return $this->getConfig('rootmegamenu_option/general/manage_masonry');
    }
    
    public function masonryCategory()
    {
        $value = array();
        $masonryCategories = $this->getConfig('rootmegamenu_option/general/masonry_category');
        if ($masonryCategories != '') {
            $value = explode(",", $masonryCategories);
        }
        return $value;
    }
    
    public function getCurrentStore()
    {
        return $this->_storeManager->getStore();
    }
    
    public function getRootCategoryId()
    {
        return $this->_storeManager->getStore()->getRootCategoryId();
    }
    
    public function getStoreId()
    {
        return $this->_storeManager->getStore()->getId();
    }
    
    public function getCategory($categoryId) 
    {
        $this->_category = $this->_categoryFactory->create();
        $this->_category->load($categoryId);        
        return $this->_category;
    }
    
    public function getMegaMenuImageName($currentCat)
    {
        if ($this->getConfig('rootmegamenu_option/general/image_source') == 2) {
            $imgName = $currentCat->getThumbnail();
        } else if ($this->getConfig('rootmegamenu_option/general/image_source') == 1) {
            $imgName = $currentCat->getImageUrl();
        } else {
            $imgName = $currentCat->getMegamenuShowCatimageImg();
        }
        return $imgName;
    }
    
    public function getMegaMenuImageUrl($currentCat)
    {
        $imageurl = '';
        if ($this->getConfig('rootmegamenu_option/general/image_source') == 2) {
            if ($currentCat->getThumbnail() != '') {
                $imgName = $currentCat->getThumbnail();
                if ($this->getMagentoVersion() >= '2.3.4') {
                    $imgName = str_replace("/pub/media/catalog/category/", "", $currentCat->getThumbnail());
                }
                $imageurl = $this->_storeManager->getStore()->getBaseUrl(
                    \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
                ) . 'catalog/category/' . $imgName;
                
            }
        } else if ($this->getConfig('rootmegamenu_option/general/image_source') == 1) {
            if ($currentCat->getImageUrl() != '') {
                $imageurl = $currentCat->getImageUrl();
            }
        } else {
            if ($currentCat->getMegamenuShowCatimageImg() != '') {
                /*
                $imgName = $currentCat->getMegamenuShowCatimageImg();
                if ($this->getMagentoVersion() >= '2.3.4') {
                    $imgName = str_replace("/pub/media/catalog/category/", "", $currentCat->getMegamenuShowCatimageImg());
                }
                $imageurl = $this->_storeManager->getStore()->getBaseUrl(
                    \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
                ) . 'catalog/category/' . $imgName;
                */
                
                if ($this->getMagentoVersion() >= '2.3.4') {
                    $imageurl = $this->_storeManager->getStore()->getBaseUrl().ltrim($currentCat->getMegamenuShowCatimageImg(), '/');
                } else {
                    $imageurl = $this->_storeManager->getStore()->getBaseUrl(
                        \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
                    ) . 'catalog/category/' . $currentCat->getMegamenuShowCatimageImg();
                }
            }
        }
        return $imageurl;
    }
}
