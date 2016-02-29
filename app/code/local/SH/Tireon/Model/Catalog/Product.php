<?php

/**
 * Class SH_Tireon_Model_Catalog_Product
 */
class SH_Tireon_Model_Catalog_Product
{

    const CSV_COLUMN_CATEGORY = 'Категория';
    const CSV_COLUMN_PRODUCT_NAME = 'Наименование';
    const CSV_COLUMN_PRODUCT_PRICE = 'Цена';
    const CSV_COLUMN_PRODUCT_COUNT = 'Количество';

    /**
     * @var array
     */
    protected $_product = array();

    public function __construct($products)
    {
        $this->_product = $products;
    }

    /**
     * Create Products
     * @param $fileName
     * @throws Exception
     */
    public function buildProducts($fileName)
    {
        $products = $this->_product;
        $shHelper = Mage::helper('sh_tireon');
        /* @var $shHelper SH_Tireon_Helper_Data */

        $shHelper->encoding();

        if($fileName == SH_Tireon_Model_CSV::CSV_FILE_NAME_WHEELS) {
            $fileName = SH_Tireon_Model_Catalog_Category::PARENT_CATEGORY_URL_KEY_WHEELS;
        } elseif($fileName == SH_Tireon_Model_CSV::CSV_FILE_NAME_OTHER) {
            $fileName = SH_Tireon_Model_Catalog_Category::PARENT_CATEGORY_URL_KEY_OTHERS;
        } else {
            $fileName = SH_Tireon_Model_Catalog_Category::PARENT_CATEGORY_URL_KEY_TYRES;
        }

        foreach ($products as $value) {
            $productModel = Mage::getModel('catalog/product');
            /* @var $productModel Mage_Catalog_Model_Product */

            try {
                $productModel
                    ->setTypeId(Mage_Catalog_Model_Product_Type::TYPE_VIRTUAL)
                    ->setAttributeSetId($productModel->getDefaultAttributeSetId())
                    ->setWebsiteIDs(array(1));

                $issetProduct = $shHelper->checkExistingModel(
                    'catalog/product',
                    array('field' => 'sku', 'value' => $shHelper->transliterate($value[self::CSV_COLUMN_PRODUCT_NAME]))
                );

                if (!$issetProduct->isEmpty()) {
                    $productModel = $productModel->load($issetProduct->getId());
                }

                $urlKey = $shHelper->transliterate($value[self::CSV_COLUMN_CATEGORY]);

                $productCategoryId = $shHelper->checkExistingModel(
                    'catalog/category',
                    array('field' => 'url_path', 'value' => $fileName . '/' . $urlKey . '.html')
                );

                $productModel
                    ->setCategoryIds(array($productCategoryId->getId()))
                    ->setStatus(Mage_Catalog_Model_Product_Status::STATUS_ENABLED)
                    ->setVisibility(Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH)
                    ->setSku($shHelper->transliterate($value[self::CSV_COLUMN_PRODUCT_NAME]))
                    ->setName($value[self::CSV_COLUMN_PRODUCT_NAME])
                    ->setShortDescription($value[self::CSV_COLUMN_PRODUCT_NAME])
                    ->setPrice($this->_calProductFinalPrice($value[self::CSV_COLUMN_PRODUCT_PRICE]))
                    ->setWeight(0)
                    ->setStockData(
                        array(
                            'use_config_manage_stock' => 0,
                            'manage_stock' => 1,
                            'is_in_stock' => 1,
                            'qty' => $this->_setProductQty($value[self::CSV_COLUMN_PRODUCT_COUNT]),
                        )
                    );

                foreach ($value as $key => $productValue) {
                    if ($key != self::CSV_COLUMN_PRODUCT_COUNT && $key != self::CSV_COLUMN_PRODUCT_PRICE &&
                        $key != self::CSV_COLUMN_PRODUCT_NAME && $key != self::CSV_COLUMN_CATEGORY
                    ) {
                        if(!empty($productValue)) {
                            $attrValue = $this->_setAttributeEntities($shHelper->transliterate($key, true), $productValue);
                            if(!empty($attrValue)) {
                                $productModel->setData($shHelper->transliterate($key, true), $attrValue);
                            }
                        }

                    }
                }

                $productModel->save();
            } catch (Exception $e) {
                Mage::throwException($e->getMessage());
            }
        }
    }

    /**
     * @param $value
     * @return mixed
     */
    protected function _setProductQty($value)
    {
        $qty = (strpos($value, '>') !== false) ? Mage::getStoreConfig('sh_tireon_settings/general/count') : str_replace('>', '', $value);

        return $qty;
    }

    /**
     * @param $value
     * @return float
     */
    protected function _calProductFinalPrice($value)
    {
        $finalPrice = $value - ($value/100) * Mage::getStoreConfig('sh_tireon_settings/general/price_percent');
        $finalPrice = round($finalPrice, -Mage::getStoreConfig('sh_tireon_settings/general/round'));

        return $finalPrice;
    }

    /**
     * @param $attributeCode
     * @param $attributeValue
     * @return mixed
     */
    protected function _setAttributeEntities($attributeCode, $attributeValue)
    {
        $attributeModel = Mage::getModel('eav/entity_attribute');
        /* @var $attributeModel Mage_Eav_Model_Attribute*/
        $attributeOptionsModel = Mage::getModel('eav/entity_attribute_source_table');
        $attributeCode = $attributeModel->getIdByCode('catalog_product', $attributeCode);
        $attribute = $attributeModel->load($attributeCode);
        $attributeOptionsModel->setAttribute($attribute);
        $options = $attributeOptionsModel->getAllOptions(false);

        $valueExists = false;
        foreach ($options as $option) {
            if ($option['label'] == $attributeValue) {
                $valueExists = true;
                break;
            }
        }

        if (!$valueExists) {
            $attribute->setData(
                'option',
                array(
                    'value' => array(
                        'option' => array($attributeValue)
                    )
                )
            );
            $attribute->save();
        }

        $attribute = Mage::getSingleton('eav/config')->getAttribute('catalog_product', $attributeCode);

        if ($attribute->getFrontendInput() == 'select') {
            $attributeSelectTagOptions = $attribute->getSource()->getAllOptions(false);

            foreach ($attributeSelectTagOptions as $option) {

                if ($option['label'] == $attributeValue) {
                    $attrValue = $option['value'];
                }
            }
        }

        return $attrValue;
    }
}