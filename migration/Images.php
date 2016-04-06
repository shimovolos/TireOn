<?php
require_once '../app/Mage.php';
Mage::app();

$mediaProductImagesPath = Mage::getBaseDir('media') . DS . 'catalog' . DS . 'product' . DS . 'shiny' . DS;

$mediaProductImagesList = scandir($mediaProductImagesPath);

foreach ($mediaProductImagesList as $mediaProductImage) {
    $mediaProductImageTmp = array_map('trim', explode('.', $mediaProductImage));
    $mediaProductImageType = $mediaProductImageTmp[1];
    $mediaProductImageName = $mediaProductImageTmp[0];

    /** @var $productModel Mage_Catalog_Model_Product */
    $productModel = Mage::getModel('catalog/product');
    $productCollection = $productModel
        ->getCollection()
        ->addAttributeToFilter('name', ['like' => '%' . $mediaProductImageName . '%']);

    if ($productCollection->count() > 0 && $mediaProductImage != '.' && $mediaProductImage != '..' &&
        $mediaProductImage != '.@__thumb') {

//        $mediaProductImage = mb_convert_encoding($mediaProductImage, 'Windows-1251', 'UTF-8');

        $mediaArray = [
            'thumbnail'   => $mediaProductImage,
            'small_image' => $mediaProductImage,
            'image'       => $mediaProductImage,
        ];

        foreach ($productCollection as $product) {
            foreach ($mediaArray as $imageType => $imageName) {
                /** @var $product Mage_Catalog_Model_Product */
                $product->addImageToMediaGallery($mediaProductImagesPath . $imageName, $imageType, false);
            }

            try {
                $product->save();
            } catch (Exception $e) {
                Mage::throwException($e->getMessage());
            }
        }
    }
}
