<?php

/* @var $installer Mage_Core_Model_Resource_Setup*/

$installer = $this;

$installer->startSetup();

$attributesArray = array('Производитель', 'Модель', 'Ширина' , 'Профиль', 'Диаметр', 'Индекс нагрузки', 'Индекс скорости', 'Сезонность');

$attributeResourceModel = Mage::getResourceModel('eav/entity_attribute');

$helper = Mage::helper('sh_tireon');
/* @var $helper SH_Tireon_Helper_Data*/
foreach($attributesArray as $attributeName) {

    $attributeCode = $helper->transliterate(mb_convert_encoding($attributeName, 'UTF-8', 'Windows-1251'));

    $installer->addAttribute('catalog_product', $attributeCode, array(
            'attribute_set'              => 'Default',
            'group'                      => 'General',
            'label'                      => mb_convert_encoding($attributeName, 'UTF-8', 'Windows-1251'),
            'frontend_label'             => mb_convert_encoding($attributeName, 'UTF-8', 'Windows-1251'),
            'global'                     => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
            'visible'                    => true,
            'type'                       => 'varchar',
            'input'                      => 'text',
            'system'                     => false,
            'required'                   => false,
            'user_defined'               => 1,
            'searchable'                 => true,
            'visible_in_advanced_search' => false,
            'is_visible_on_front'        => true,
        )
    );

    $attributeId = $attributeResourceModel->getIdByCode('catalog_product', $attributeCode);
    if ($attributeId) {
        $attribute = Mage::getModel('catalog/resource_eav_attribute')->load($attributeId);
        $attribute->setIsVisibleOnFront(1)->save();
    }
}

$installer->setConfigData('sh_tireon_settings/general/count', 200);
$installer->setConfigData('sh_tireon_settings/general/price_percent', 10);
$installer->setConfigData('sh_tireon_settings/general/round', 3);

$installer->endSetup();
