<?php
/**
 * Magento e-commerce interface (oa_magento)
 *
 * @author S. Hamblett <steve.hamblett@linux.com>
 * For Orbital Alliance
 *
 * @package  oa_magento
 * 
 * Parameters :-
 * 
 * skuid                - A single SKU identifier or a comma seperated list of SKU's
 * wrapperTpl           - An outer wrapper template for results returned, defaults to magentoWrapper
 * productTpl           - A template for an individual product, defaults to magentoProduct
 * categoryTpl          - A template for individual categories, defaults to magentoCategory
 * categoryWrapperTpl   - A template for the product categories, defaults to magentoCategoryWrapper
 * sortby               - Sortby field
 * sortdir              - Direction to sort, defaults to ASC
 * limit                - Limit to, defaults to 10
 * 
 * 
 * Placeholders, all prefixed with oa_magento :-
 * 
 * Single, all Magento product info parameters are available, refer to the 
 * Magento documentation for a complete set, some of the more popular ones are :-
 * 
 * sku          - The SKU
 * product_id   - The product Id
 * name         - Product name
 * set          - Product set
 * type         - Product type
 * price        - Product price
 * weight       - Product weight
 * model        - Product model
 * manufacturer - Product manufacturer
 * cost         - Product cost
 * description  - Product description
 * 
 * Groups
 * productlist  - A list of the returned products as themed by the magentoProduct template
 * categorylist  - A list of the returned categories as themed by the magentoCategory template
 */

/* Initialise our parameter set */
$skuid = (!empty($skuid)) ? explode(',', $skuid) : 0;
$wrapperTpl = !empty($wrapperTpl) ? $wrapperTpl : 'magentoWrapper';
$categoryWrapperTpl = !empty($categoryWrapperTpl) ? $categoryWrapperTpl : 'magentoCategoryWrapper';
$productTpl = !empty($itemTpl) ? $productTpl : 'magentoProduct';
$categoryTpl = !empty($categoryTpl) ? $categoryTpl : 'magentoCategory';
$limit = isset($limit) ? (integer) $limit : 10;
$sortby = isset($sortby) ? $sortby : 'SKU';
$sortdir = isset($sortdir) ? $sortdir : 'ASC';

/* Create the SOAP proxy */
$proxy = new SoapClient($WSDLURL);
$sessionId = $proxy->login($apiUser, $apiKey);

/* Get the products from the SKUID(s) */
$output = "";
$categoryOutput = "";
$productCount = 0;

foreach ( $skuid as $aSkuid ) {
    
    $modx->unsetPlaceholders('oa_magento');
    
    try {
        $productInfo = $proxy->call($sessionId, 'product.info', $aSkuid);
    } catch (SoapFault $e) {
        $error = $e->faultstring;
        $error = "ERROR - Magento returns - " . "'" . $error . "'";
        $output .= $error . " - For SKU " . "'" . $aSkuid . "'";
        continue;
    }
    $modx->toPlaceholders($productInfo, 'oa_magento' );
    $categories = $productInfo['categories'];
    if (!empty($categories) ) {
        
        foreach ( $categories as $category ) {
            $modx->toPlaceholder('category', $category, 'oa_magento');
            $categoryOutput .= $modx->getChunk($categoryTpl);            
        } 
        
    } else {
        
        $categoryOutput = "No Categories defined";
    }
        
    $modx->toPlaceholder('categorylist', $categoryOutput, 'oa_magento'); 
    $categoryChunk = $modx->getChunk('magentoCategoryWrapper');
    $modx->toPlaceholder('categorywrapper', $categoryChunk, 'oa_magento'); 
    $output .= $modx->getChunk($productTpl);
    
    /* Limit */
    $productCount++;
    if ( $productCount == $limit ) break;
}

/* Set the product list placeholder */
$modx->toPlaceholder('productlist', $output, 'oa_magento');
