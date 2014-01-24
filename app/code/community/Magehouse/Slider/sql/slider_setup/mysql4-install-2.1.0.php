<?php
	
$installer = $this;
$installer->startSetup();
$installer->run("
	DELETE FROM {$installer->getTable('core/config_data')} WHERE path like 'price_slider/price_slider_conf/use_image';
	DELETE FROM {$installer->getTable('core/config_data')} WHERE path like 'price_slider/ajax_conf/layered';
	DELETE FROM {$installer->getTable('core/config_data')} WHERE path like 'price_slider/ajax_conf/slider';
	DELETE FROM {$installer->getTable('core/config_data')} WHERE path like 'price_slider/price_slider_conf/timeout';
    INSERT INTO {$installer->getTable('core/config_data')} VALUES (NULL, 'default', '0', 'price_slider/price_slider_conf/use_image', '1');
    INSERT INTO {$installer->getTable('core/config_data')} VALUES (NULL, 'default', '0', 'price_slider/ajax_conf/layered', '0');
    INSERT INTO {$installer->getTable('core/config_data')} VALUES (NULL, 'default', '0', 'price_slider/ajax_conf/slider', '1');
    INSERT INTO {$installer->getTable('core/config_data')} VALUES (NULL, 'default', '0', 'price_slider/price_slider_conf/timeout', '0');
	
");
$installer->endSetup();