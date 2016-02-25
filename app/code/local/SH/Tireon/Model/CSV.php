<?php

/**
 * Class SH_Tireon_Model_CSV
 */
class SH_Tireon_Model_CSV extends Mage_Core_Model_Abstract
{
    const CSV_FILE_NAME = 'tyres.csv';

    /**
     * @var $csvPath
     */
    protected $_csvPath;

    public function _construct()
    {
        $this->_parseCsv();
    }

    /**
     * Parse CSV File
     */
    protected function _parseCsv()
    {
        $csvFile = $this->_getCsvPath() . DS . self::CSV_FILE_NAME;

        $category = array();
        $product = array();

        try {
            $csv = new Varien_File_Csv();
            /* @var $csv Varien_File_Csv*/
            $csv->setDelimiter(';');
            $data = $csv->getData($csvFile);

            $columns = $data[0];
            unset($data[0]);
            foreach($data as $key => $value) {
                $category[] = current($value);
                $product[] = array_combine($columns, $value);
            }
            $category = array_unique($category);

            $shCategoryModel = Mage::getModel('sh_tireon/catalog_category', $category);
            /* @var $shCategoryModel SH_Tireon_Model_Catalog_Category*/
            $shCategoryModel->buildCategory();

            $shProductModel = Mage::getModel('sh_tireon/catalog_product', $product);
            /* @var $shProductModel SH_Tireon_Model_Catalog_Product*/
            $shProductModel->buildProducts();

        } catch (Exception $e) {
            Mage::throwException($e->getMessage());
        }

    }

    /**
     * @return string
     */
    protected function _getCsvPath()
    {
        return $this->_csvPath = Mage::getBaseDir('code') . DS . 'local' . DS .'SH' . DS .'Tireon' . DS . 'data' . DS;
    }
}