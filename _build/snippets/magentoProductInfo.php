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
 * sortby               - Sortby field, one of sku, name, price or product_id
 * sortdir              - Direction to sort, defaults to ASC
 * limit                - Limit to, defaults to 10
 * toJSON               - Return the dataset as a JSON string rather than running it through the templates
 *                        used when this snippet is wrapped, defaults to 0
 * outputSeparator      - An optional string to separate each tpl instance [default="\n"]
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
$sortby = isset($sortby) ? $sortby : 'sku';
$sortdir = isset($sortdir) ? $sortdir : 'ASC';
$toJSON = $toJSON == 1 ? true : false;
$outputSeparator = isset($outputSeparator) ? $outputSeparator : "\n";

/* Create the SOAP proxy */
$proxy = new SoapClient($WSDLURL);
$sessionId = $proxy->login($apiUser, $apiKey);


/* Get the products from the SKUID(s) */
$productArray = array();
$sortArray = array();

foreach ( $skuid as $aSkuid ) {
    
    try {
        $productInfo = $proxy->call($sessionId, 'product.info', $aSkuid);
    } catch (SoapFault $e) {
        continue;
    }
    /* Sortby, only if the sortby key is valid for a product */
    if ( isset($productInfo[$sortby]) ) {
        
        if ($sortby == 'price') {

            $productArray[floatval($productInfo[$sortby])] = $productInfo;

        } else {

            $productArray[$productInfo[$sortby]] = $productInfo;
        }
    
    }
}

/* Sort the array */
if ( $sortdir == 'ASC') {
    
    ksort($productArray);
    
} else {
    
    krsort($productArray);
    
}

/* If toJSON selected return the dataset here */
if ( $toJSON ) {
    
    $outputString = json_encode($productArray);
    return $outputString;
}

/* Process the products through the templates */
$productOutput = array();
$categoryOutput = "";
$productCount = 0;
foreach ( $productArray as $key => $productInfo) {
    
    $modx->unsetPlaceholders('oa_magento');
    $modx->toPlaceholders($productInfo, 'oa_magento' );
    $categories = $productInfo['categories'];
    $categoryOutput = "";
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
    $productOutput[] = $modx->getChunk($productTpl);
    
    /* Limit */
    $productCount++;
    if ( $productCount == $limit ) break;
}


 
 
/* Set the product list placeholder */
$modx->toPlaceholder('productlist', $productOutput, 'oa_magento');

/* Return the output for use by getPage etc */
$output = implode($outputSeparator, $productOutput);
return $output;
    
