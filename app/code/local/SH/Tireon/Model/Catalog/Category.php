<?php

/**
 * Class SH_Tireon_Model_Catalog_Category
 */
class SH_Tireon_Model_Catalog_Category
{
    const PARENT_CATEGORY_URL_KEY = 'shiny';

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
     * @throws Exception
     */
    public function buildCategory()
    {
        $categories = $this->_category;

        foreach ($categories as $category) {
            $urlKey = Mage::helper('sh_tireon')->transliterate($category);
            $categoryModel = Mage::getModel('catalog/category');

            try {

                $categoryCollection = Mage::helper('sh_tireon')->checkExistingModel('catalog/category', array('field' => 'url_key', 'value' => $urlKey));
                $parentCategory = Mage::helper('sh_tireon')->checkExistingModel('catalog/category', array('field' => 'url_key', 'value' => self::PARENT_CATEGORY_URL_KEY));

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