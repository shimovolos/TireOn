<?php

/**
 * Class SH_Tireon_Model_Catalog_Category
 */
class SH_Tireon_Model_Catalog_Category
{
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

        $defaultStore = Mage::getModel('core/store')->load(Mage_Core_Model_App::DISTRO_STORE_ID);
        $rootCategoryId = $defaultStore->getRootCategoryId();

        foreach ($categories as $category) {
            $urlKey = Mage::helper('sh_tireon')->transliterate($category);
            $categoryModel = Mage::getModel('catalog/category');

            try {

                $categoryCollection = Mage::helper('sh_tireon')->checkExistingModel('catalog/category', array('field' => 'url_key', 'value' => $urlKey));

                if($categoryCollection->isEmpty()) {
                    $categoryModel
                        ->setName($category)
                        ->setUrlKey($urlKey)
                        ->setIsActive(1)
                        ->setDisplayMode('PRODUCTS')
                        ->setIsAnchor(0)
                        ->setStoreId(Mage::app()->getStore()->getId());

                    $parentCategory = Mage::getModel('catalog/category')->load($rootCategoryId);
                    $categoryModel->setPath($parentCategory->getPath());

                    $categoryModel->save();
                }
            } catch (Exception $e) {
                Mage::throwException($e->getMessage());
            }
        }
    }
}