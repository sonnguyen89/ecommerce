<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_Page
 * @copyright   Copyright (c) 2012 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Top menu block
 *
 * @category    Mage
 * @package     Mage_Page
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Meigee_ThemeOptionsLookbook_Block_Html_Topmenu extends Mage_Page_Block_Html_Topmenu
{

    /**
     * Recursively generates top menu html from data that is specified in $menuTree
     *
     * @param Varien_Data_Tree_Node $menuTree
     * @param string $childrenWrapClass
     * @return string
     */
    protected function _getHtml(Varien_Data_Tree_Node $menuTree, $childrenWrapClass)
    {
        $menuContent = MAGE::helper('ThemeOptionsLookbook')->getThemeOptionsLookbook('menu');
        $html = '';

        $children = $menuTree->getChildren();
        $parentLevel = $menuTree->getLevel();
        $childLevel = is_null($parentLevel) ? 0 : $parentLevel + 1;

        $counter = 1;
        $childrenCount = $children->count();

        $navigation = MAGE::helper('ThemeOptionsLookbook')->getThemeOptionsLookbook('menu');

        $parentPositionClass = $menuTree->getPositionClass();
        $itemPositionClassPrefix = $parentPositionClass ? $parentPositionClass . '-' : 'nav-';
        foreach ($children as $child) {
            $child->setLevel($childLevel);
            $child->setIsFirst($counter == 1);
            $child->setIsLast($counter == $childrenCount);
            $child->setPositionClass($itemPositionClassPrefix . $counter);

            $outermostClassCode = '';
            $outermostClass = $menuTree->getOutermostClass();

            if ($childLevel == 0 && $outermostClass) {
                $outermostClassCode = ' class="' . $outermostClass . '" ';
                $child->setClass($outermostClass);
            }


            if ($navigation['type'] == 'menu_wide' && $counter == 1 && $childLevel == 1 ) {
                $html .= '<li class="tech"><ul class="tech '. $childLevel .'">';
            }

            $html .= '<li ' . $this->_getRenderedMenuItemAttributes($child) . '>';
            $html .= '<a href="' . $child->getUrl() . '" ' . $outermostClassCode . '><span>'
                . $this->escapeHtml($child->getName()) . '</span>';
            $html .= '</a>';

            if ($child->hasChildren()) {
                if (!empty($childrenWrapClass)) {
                    $html .= '<div class="' . $childrenWrapClass . '">';
                }
                $html .= '<ul class="level' . $childLevel . '">';
                $html .= $this->_getHtml($child, $childrenWrapClass);
                /* Static blocks & category description */
                if ($navigation['type'] == 'menu_wide' && $menuContent['additional'] ) {
                    $ca = Mage::getModel('catalog/category')->load(substr($child->getId(), 14));
                    $description = $ca->getDescription();
                    if(Mage::helper('ThemeOptionsLookbook')->isActive('identifier', $child->getId()) && $childLevel == 0) {
                        $staticBlock = trim($this->getLayout()->createBlock('cms/block')->setBlockId($child->getId())->toHtml());
                        if(!empty($staticBlock)){
                            $html .= '<li class="tech clearfix nav-static-block"><a href="#" class="nav-fix">&nbsp;</a>';
                            $html .= $staticBlock;
                            $html .= '</li>';
                        }
                    }
                    elseif ($description && $childLevel == 0) {
                        $html .= '<li class="tech clearfix nav-static-block"><a href="#" class="nav-fix">&nbsp;</a>';
                        if ($ca->getThumbnail()) {
                            $html .= '<a href="' . $child->getUrl() .'" class="cat-thumb"><img src="' . Mage::getBaseUrl('media').'catalog/category/'. $ca->getThumbnail() .'" alt="' . $this->escapeHtml($child->getName()) . '" /></a>';
                        }
                        $html .= '<p>'. $description.'</p><button type="button" title="'. $this->__('Go to Category') .'" class="button btn-cart" onclick="setLocation(\'' . $child->getUrl() . '\')"><span><span>'. $this->__('Go to Category') .'</span></span></button></li>';
                    }
                }
                /* Static blocks & category description */

                $html .= '</ul>';

                if (!empty($childrenWrapClass)) {
                    $html .= '</div>';
                }
            }
            $html .= '</li>';
            if ($navigation['type'] == 'menu_wide' && $counter == intval($childrenCount/2) && $childLevel == 1 ) {
                $html .='</ul></li><li class="tech"><ul class="tech">';
            }
            if ($navigation['type'] == 'menu_wide' && $counter == $childrenCount && $childLevel == 1 ) {
               $html .='</ul></li>';
            }

            $counter++;
        }
        return $html;
    }
}
