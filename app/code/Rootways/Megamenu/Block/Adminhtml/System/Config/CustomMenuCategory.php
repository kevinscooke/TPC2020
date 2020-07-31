<?php
/**
 * Mega Menu CustomMenuCategory Block.
 *
 * @category  Site Search & Navigation
 * @package   Rootways_Mega_Menu
 * @author    Developer RootwaysInc <developer@rootways.com>
 * @copyright 2017 Rootways Inc. (https://www.rootways.com)
 * @license   Rootways Custom License
 * @link      https://www.rootways.com/shop/media/extension_doc/license_agreement.pdf
 */
namespace Rootways\Megamenu\Block\Adminhtml\System\Config;

class CustomMenuCategory extends \Magento\Framework\View\Element\Html\Select
{
    /**
     * @var \Magento\Catalog\Helper\Category
     */
    protected $categoryHelper;
    
    /**
     * @var \Magento\Catalog\Helper\Category
     */
    protected $_categoryHelper;
    
    /**
     * Index constructor.
     * @param Context $context
     * @param \Magento\Catalog\Helper\Category $categoryHelper
     * @param \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory
     */
    public function __construct(
        \Magento\Framework\View\Element\Context $context,
        \Magento\Catalog\Helper\Category $categoryHelper,
        \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_categoryHelper = $categoryHelper;
        $this->_categoryCollectionFactory = $categoryCollectionFactory;
    }

    public function _toHtml()
    {
        if (!$this->getOptions()) {
            $cat = $this->getStoreCategories(true, false, true);
            $categories = $this->_categoryCollectionFactory->create();
            $categories->addAttributeToSelect('*');
            $categories->addIsActiveFilter();
            $categories->addLevelFilter(2);
            $this->addOption('', '-- Select --');
            foreach ($cat as $cat) {
                $this->addOption($cat->getId(), $cat->getName());
            }
        }

        return parent::_toHtml();
    }
    
    public function setInputName($value)
    {
        return $this->setName($value);
    }
    
    /**
     * Retrieve current store categories
     */
    public function getStoreCategories($sorted = false, $asCollection = false, $toLoad = true)
    {
        return $this->_categoryHelper->getStoreCategories($sorted , $asCollection, $toLoad);
    }
}
