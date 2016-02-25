<?php

/**
 * Class SH_Tireon_Helper_Data
 */
class SH_Tireon_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * Generate latin word from russian
     * @param $string
     * @return mixed
     */
    public function transliterate($string)
    {
        $catalogProductHelper = Mage::helper('catalog/product_url');
        /* @vat $catalogProductHelper Mage_Catalog_Helper_Product_Url*/

        $tmp = $catalogProductHelper->format($string);
        $str = preg_replace('#[^0-9a-z]+#i', '-', $tmp);
        $str = strtolower($str);
        $str = trim($str, '-');

        return $str;
    }

    /**
     * Set UTF-8 charset by default for products
     */
    public function encoding()
    {
        mysql_query("SET NAMES 'utf8'");
        mysql_query("SET CHARACTER SET 'utf8'");
        mysql_query("SET SESSION collation_connection = 'utf8_general_ci'");
    }

    /**
     * @param $model
     * @param array $data
     * @return mixed
     */
    public function checkExistingModel($model, $data)
    {
        $collection = Mage::getModel($model)
            ->getCollection()
            ->addAttributeToFilter($data['field'], array('eq' => $data['value']))
            ->getFirstItem();

        return $collection;
    }
}