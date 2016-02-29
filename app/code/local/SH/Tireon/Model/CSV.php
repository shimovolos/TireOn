<?php

/**
 * Class SH_Tireon_Model_CSV
 */
class SH_Tireon_Model_CSV
{
    const CSV_FILE_NAME_TYRES = 'tyres.csv';
    const CSV_FILE_NAME_TRUCK_TYRES = 'truck_tyres.csv';
    const CSV_FILE_NAME_INDUSTRIAL_TYRES = 'industrial_tyres.csv';
    const CSV_FILE_NAME_WHEELS = 'wheels.csv';
    const CSV_FILE_NAME_OTHER = 'other.csv';

    /**
     * @var $csvPath
     */
    protected $_csvPath;

    /**
     * @param null $attribute
     * @param $fileName
     * @return array
     */
    public function getAttributeValues($attribute = null, $fileName)
    {
        $data = $this->_getCsvData($fileName);
        $attributeValues = array();

        foreach ($data['product'] as $product) {
            $attributeValues[] = $product[mb_convert_encoding($attribute, 'UTF-8', 'Windows-1251')];
        }
        $attributeValues = array_values(array_unique($attributeValues));

        sort($attributeValues);
        return $attributeValues;
    }

    /**
     * Set Entities as Category, Product
     */
    public function setEntities($fileName)
    {
        try {
            $data = $this->_getCsvData($fileName);

            $category = $data['category'];
            $product = $data['product'];

            $shCategoryModel = Mage::getModel('sh_tireon/catalog_category', $category);
            /* @var $shCategoryModel SH_Tireon_Model_Catalog_Category */
            $shCategoryModel->buildCategory($fileName);

            $shProductModel = Mage::getModel('sh_tireon/catalog_product', $product);
            /* @var $shProductModel SH_Tireon_Model_Catalog_Product */
            $shProductModel->buildProducts($fileName);

        } catch (Exception $e) {
            Mage::throwException($e->getMessage());
        }
    }

    /**
     * @return string
     */
    protected function _getCsvPath()
    {
        return $this->_csvPath = Mage::getBaseDir('code') . DS . 'local' . DS . 'SH' . DS . 'Tireon' . DS . 'data';
    }

    /**
     * @param $fileName
     * @return array
     */
    protected function _getCsvData($fileName)
    {
        $category = array();
        $product = array();

        $csvFile = $this->_getCsvPath() . DS . $fileName;

        try {
            $csv = new Varien_File_Csv();
            /* @var $csv Varien_File_Csv */
            $csv->setDelimiter(';');
            $data = $csv->getData($csvFile);

            $columns = $data[0];
            unset($data[0]);
            foreach ($data as $value) {
                $category[] = current($value);
                $product[] = array_combine($columns, $value);
            }
            $category = array_unique($category);

        } catch (Exception $e) {
            Mage::throwException($e->getMessage());
        }

        return array('category' => $category, 'product' => $product);
    }
}