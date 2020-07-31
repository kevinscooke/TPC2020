<?php
/**
 * Mega Menu Alignmenttype Model.
 *
 * @category  Site Search & Navigation
 * @package   Rootways_Mega_Menu
 * @author    Developer RootwaysInc <developer@rootways.com>
 * @copyright 2017 Rootways Inc. (https://www.rootways.com)
 * @license   Rootways Custom License
 * @link      https://www.rootways.com/shop/media/extension_doc/license_agreement.pdf
 */
namespace Rootways\Megamenu\Model\Config\Backend\Design;

class Alignmenttype implements \Magento\Framework\Option\ArrayInterface
{
    public function toOptionArray()
    {
        return [
            ['value' => '0', 'label' => __('From Left')],
            ['value' => '1', 'label' => __('From Right')],
            ['value' => '2', 'label' => __('Fit to Screen')]
        ];
    }
}
