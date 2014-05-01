<?php

class TM_Core_Block_Adminhtml_Module_Grid_Renderer_Actions
    extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    /**
     * Renders grid column
     *
     * @param   Varien_Object $row
     * @return  string
     */
    public function render(Varien_Object $row)
    {
        $links = array();

        if ($row->getChangelog()) {
            $links[] = sprintf(
                '<a href="javascript:void(0)" onclick="%s">%s</a><div style="display:none" class="changelog"><div class="title">%s</div><div class="content">%s</div></div>',
                "tmcoreWindow.update(this.next('.changelog').down('.content').innerHTML, this.next('.changelog').down('.title').innerHTML).show()",
                Mage::helper('tmcore')->__('Changelog'),
                strip_tags($row->getCode()),
                nl2br(htmlspecialchars($row->getChangelog()))
            );
        }

        if ($row->getDownloadLink()) {
            $links[] = sprintf(
                '<a href="%s" title="%s" onclick="window.open(this.href); return false;">%s</a>',
                $row->getDownloadLink(),
                Mage::helper('tmcore')->__('Download Latest Version'),
                Mage::helper('tmcore')->__('Download')
            );
        }

        if ($row->hasUpgradesDir() || $row->getIdentityKeyLink()) {
            $links[] = sprintf(
                '<a href="%s">%s</a>',
                $this->getUrl('*/*/manage/', array('_current' => true, 'id' => $row->getId())),
                Mage::helper('tmcore')->__('Manage')
            );
        }

        return implode(' | ', $links);
    }
}
