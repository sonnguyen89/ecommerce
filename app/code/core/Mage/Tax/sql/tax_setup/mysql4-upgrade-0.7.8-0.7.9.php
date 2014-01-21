<?php
/**
 * Magento Enterprise Edition
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Magento Enterprise Edition License
 * that is bundled with this package in the file LICENSE_EE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.magentocommerce.com/license/enterprise-edition
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
 * @package     Mage_Tax
 * @copyright   Copyright (c) 2013 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://www.magentocommerce.com/license/enterprise-edition
 */

$installer = $this;
/* @var $installer Mage_Tax_Model_Mysql4_Setup */

$installer->addAttribute('invoice', 'shipping_tax_amount', array('type'=>'decimal'));
$installer->addAttribute('invoice', 'base_shipping_tax_amount', array('type'=>'decimal'));

$installer->addAttribute('creditmemo', 'shipping_tax_amount', array('type'=>'decimal'));
$installer->addAttribute('creditmemo', 'base_shipping_tax_amount', array('type'=>'decimal'));

$installer->addAttribute('quote_item', 'price_incl_tax', array('type'=>'decimal'));
$installer->addAttribute('quote_item', 'base_price_incl_tax', array('type'=>'decimal'));
$installer->addAttribute('quote_item', 'row_total_incl_tax', array('type'=>'decimal'));
$installer->addAttribute('quote_item', 'base_row_total_incl_tax', array('type'=>'decimal'));
$installer->addAttribute('quote_address_item', 'price_incl_tax', array('type'=>'decimal'));
$installer->addAttribute('quote_address_item', 'base_price_incl_tax', array('type'=>'decimal'));
$installer->addAttribute('quote_address_item', 'row_total_incl_tax', array('type'=>'decimal'));
$installer->addAttribute('quote_address_item', 'base_row_total_incl_tax', array('type'=>'decimal'));
$installer->addAttribute('quote_address', 'subtotal_incl_tax', array('type'=>'decimal'));
$installer->addAttribute('quote_address', 'base_subtotal_total_incl_tax', array('type'=>'decimal'));

$installer->addAttribute('order_item', 'price_incl_tax', array('type'=>'decimal'));
$installer->addAttribute('order_item', 'base_price_incl_tax', array('type'=>'decimal'));
$installer->addAttribute('order_item', 'row_total_incl_tax', array('type'=>'decimal'));
$installer->addAttribute('order_item', 'base_row_total_incl_tax', array('type'=>'decimal'));
$installer->addAttribute('order', 'subtotal_incl_tax', array('type'=>'decimal'));
$installer->addAttribute('order', 'base_subtotal_incl_tax', array('type'=>'decimal'));

$installer->addAttribute('invoice_item', 'price_incl_tax', array('type'=>'decimal'));
$installer->addAttribute('invoice_item', 'base_price_incl_tax', array('type'=>'decimal'));
$installer->addAttribute('invoice_item', 'row_total_incl_tax', array('type'=>'decimal'));
$installer->addAttribute('invoice_item', 'base_row_total_incl_tax', array('type'=>'decimal'));
$installer->addAttribute('invoice', 'subtotal_incl_tax', array('type'=>'decimal'));
$installer->addAttribute('invoice', 'base_subtotal_incl_tax', array('type'=>'decimal'));

$installer->addAttribute('creditmemo_item', 'price_incl_tax', array('type'=>'decimal'));
$installer->addAttribute('creditmemo_item', 'base_price_incl_tax', array('type'=>'decimal'));
$installer->addAttribute('creditmemo_item', 'row_total_incl_tax', array('type'=>'decimal'));
$installer->addAttribute('creditmemo_item', 'base_row_total_incl_tax', array('type'=>'decimal'));
$installer->addAttribute('creditmemo', 'subtotal_incl_tax', array('type'=>'decimal'));
$installer->addAttribute('creditmemo', 'base_subtotal_incl_tax', array('type'=>'decimal'));
