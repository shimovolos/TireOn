<?php

/* @var $installer Mage_Core_Model_Resource_Setup*/

$installer = $this;

$installer->startSetup();

$attributesArray = array(
    SH_Tireon_Model_CSV::CSV_FILE_NAME_WHEELS            => array('PCD', 'ET', 'Dia', 'Цвет'),
    SH_Tireon_Model_CSV::CSV_FILE_NAME_TYRES             => array('Производитель', 'Модель', 'Ширина' , 'Профиль', 'Диаметр', 'Индекс нагрузки', 'Индекс скорости', 'Сезонность'),
    SH_Tireon_Model_CSV::CSV_FILE_NAME_TRUCK_TYRES       => array('Норма слойности', 'Применяемость'),
    SH_Tireon_Model_CSV::CSV_FILE_NAME_INDUSTRIAL_TYRES  => array('Рисунок протектора'),
);

$attributeResourceModel = Mage::getResourceModel('eav/entity_attribute');

$csvModel = new SH_Tireon_Model_CSV();

$helper = Mage::helper('sh_tireon');
/* @var $helper SH_Tireon_Helper_Data*/
foreach($attributesArray as $key => $attributes) {
    foreach ($attributes as $attributeName) {
        $attributeCode = $helper->transliterate(mb_convert_encoding($attributeName, 'UTF-8', 'Windows-1251'), true);
        $attributeValues = $csvModel->getAttributeValues($attributeName, $key);


        $installer->addAttribute('catalog_product', $attributeCode, array(
                'attribute_set'              => 'Default',
                'group'                      => 'General',
                'label'                      => mb_convert_encoding($attributeName, 'UTF-8', 'Windows-1251'),
                'frontend_label'             => mb_convert_encoding($attributeName, 'UTF-8', 'Windows-1251'),
                'global'                     => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
                'visible'                    => true,
                'type'                       => 'int',
                'input'                      => 'select',
                'system'                     => false,
                'required'                   => false,
                'user_defined'               => 1,
                'searchable'                 => true,
                'visible_in_advanced_search' => false,
                'is_visible_on_front'        => true,
                'option' =>
                array (
                    'values' => $attributeValues,
                ),
            )
        );

        $attributeId = $attributeResourceModel->getIdByCode('catalog_product', $attributeCode);
        if ($attributeId) {
            $attribute = Mage::getModel('catalog/resource_eav_attribute')->load($attributeId);
            $attribute->setIsVisibleOnFront(1);
            $attribute->setIsComparable(1);
            $attribute->setIsFilterable(2);
            $attribute->setIsFilterableInSearch(1);
            $attribute->save();
        }
    }
}

$installer->setConfigData('sh_tireon_settings/general/count', 200);
$installer->setConfigData('sh_tireon_settings/general/price_percent', 10);
$installer->setConfigData('sh_tireon_settings/general/round', 3);

$installer->endSetup();
