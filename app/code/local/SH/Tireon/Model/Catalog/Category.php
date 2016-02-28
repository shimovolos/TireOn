<?php

/**
 * Class SH_Tireon_Model_Catalog_Category
 */
class SH_Tireon_Model_Catalog_Category
{
    const PARENT_CATEGORY_URL_KEY_TYRES = 'shiny';
    const PARENT_CATEGORY_URL_KEY_WHEELS = 'diski';

    /**
     * @var array
     */
    protected $_category = array();

    /**
     * @param $categories
     */
    public function __construct($categories)
    {
        $this->_category = $categories;
    }

    /**
     * Build Category Tree
     * @param $fileName
     */
    public function buildCategory($fileName)
    {
        if($fileName == SH_Tireon_Model_CSV::CSV_FILE_NAME_WHEELS) {
            $parentCategoryType = self::PARENT_CATEGORY_URL_KEY_WHEELS;
        } else {
            $parentCategoryType = self::PARENT_CATEGORY_URL_KEY_TYRES;
        }
        $categories = $this->_category;

        foreach ($categories as $category) {
            $urlKey = Mage::helper('sh_tireon')->transliterate($category);
            $categoryModel = Mage::getModel('catalog/category');

            try {

                $categoryCollection = Mage::helper('sh_tireon')->checkExistingModel('catalog/category', array('field' => 'url_path', 'value' => $parentCategoryType . '/' . $urlKey . '.html'));
                $parentCategory = Mage::helper('sh_tireon')->checkExistingModel('catalog/category', array('field' => 'url_path', 'value' => $parentCategoryType . '.html'));

                if($categoryCollection->isEmpty()) {
                    $categoryModel
                        ->setName($category)
                        ->setUrlKey($urlKey)
                        ->setIsActive(1)
                        ->setIsAnchor(1)
                        ->setDisplayMode('PRODUCTS')
                        ->setStoreId(Mage::app()->getStore()->getId());

                    $categoryModel->setPath($parentCategory->getPath());

                    $categoryModel->save();
                }
            } catch (Exception $e) {
                Mage::throwException($e->getMessage());
            }
        }
    }
}