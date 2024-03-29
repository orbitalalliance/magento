<?php

/**
 * Magento e-commerce interface (oa_magento)
 *
 * @author S. Hamblett <steve.hamblett@linux.com>
 * For Orbital Alliance
 *
 * @package  oa_magento
 * 
 * This snippet is a filtering wrapper around the magentoProductInfo snippet.
 * Filtering is as provided by the Magento SOAP API
 *  
 * Parameters :-
 * 
 * These following parameters are passed straight through to the magentoProductInfo snippet :-
 * 
 * wrapperTpl           - An outer wrapper template for results returned, defaults to magentoWrapper
 * productTpl           - A template for an individual product, defaults to magentoProduct
 * categoryTpl          - A template for individual categories, defaults to magentoCategory
 * categoryWrapperTpl   - A template for the product categories, defaults to magentoCategoryWrapper
 * sortby               - Sortby field, one of sku, name, price or product_id
 * sortdir              - Direction to sort, defaults to ASC
 * limit                - Limit to, defaults to 10
 * 
 * 
 * The following parameters are used by this snippet :-
 * 
 * toJSON               - Return the dataset as a JSON string rather than running it through the templates
 *                        used when this snippet is wrapped, defaults to 0
 * 
 * filters              - A comma seperated list of filter strings of the form 
 * 
 *                        field:operator:value-value-value....
 * 
 *                        The filters will be applied in the order given and are AND'ed together
 * 
 *                        Valid operators are :-
 * 
 *                        from  - fromvalue-tovalue,
 *                        like  - value as in SQL e.g %value% or %value or value% etc.
 *                        neq   - value,
 *                        in    - value-value-value-...etc.
 *                        nin   - value-value-value-...etc.
 *                        eq    - value,
 *                        nlike - value as in SQL e.g %value% or %value or value% etc.
 *                        is    - value,
 *                        gt    - value,
 *                        lt    - value,
 *                        gteq  - value,
 *                        lteq  - value,
 *                
 *                        Note no sanity checking is done by this snippet for instance
 *                        gt may only make sense on numeric fields such as price.
 * 
 *                        Examples :-
 * 
 *                        price:gt:100.00
 *                        name:like:%zol,price:lt:1000.00
 *                        price:in:100.00-200.00
 * 
 * outputSeparator      - An optional string to separate each tpl instance [default="\n"]
 * 
 * Placeholders are set by the magentoProductInfo snippet, see the snippet for details
 * 
 * Please inspect and set the Magento SOAP API parameters on the properties tab of 
 * this snippet before continuing.
 */

/* Check for valid SOAP parameters */
if ( ($WSDLUR == "") || ($apiKey == "") || ($apiUser == "") || ($storeURL == "")) {
    
    return "Error - SOAP parameters not set, please inspect the properties tab of this snippet.";
    
}

/* Initialise our parameter set */
$toJSON = $toJSON == 1 ? true : false;
$filters = (!empty($filters)) ? explode(',', $filters) : 0;
$outputSeparator = isset($outputSeparator) ? $outputSeparator : "\n";

if ($filters == 0)
    return;

/* Create the SOAP proxy */
$proxy = new SoapClient($WSDLURL);
try {
    $sessionId = $proxy->login($apiUser, $apiKey);
} catch (SoapFault $e) {

    return "SOAP Fault - Cannot login, error is --> $e->faultstring";
}

/* Process the filter */

/* Create the filter list */
$filterList = array();
foreach ($filters as $filter) {

    $filterComponents = explode(':', $filter);
    $field = $filterComponents[0];
    $operator = $filterComponents[1];
    $values = explode('-', $filterComponents[2]);
    switch ($field) {

        case 'from':

            $valArray = array('from' => $values[0],
                'to' => $values[1]);
            break;

        case 'in':
        case 'nin':

            $inValues = implode(',', $values);
            $valArray = array('in' => $inValues);
            $WSDLURLbreak;

        default :

            $valArray = array($operator => $values[0]);
    }

    $filterList[$field] = $valArray;
}

/* Get the products */
try {
    $products = $proxy->call($sessionId, 'product.list', array($filterList));
} catch (SoapFault $e) {

    return "SOAP Fault - Cannot get all filtered products, error is --> $e->faultstring";
}


/* Get the sku's */
$skuArray = array();
foreach ($products as $product) {

    $skuArray[] = $product['sku'];
}

/* Call the magentoProductInfoSnippet with the return type as JSON */
$skuid = implode(',', $skuArray);
$productJSON = $modx->runSnippet('magentoProductInfo', array('wrapperTpl' => $wrapperTpl,
    'productTpl' => $productTpl,
    'categoryTpl' => $categoryTpl,
    'categoryWrapperTpl' => $categoryWrapperTpl,
    'sortby' => $sortby,
    'sortdir' => $sortdir,
    'limit' => $limit,
    'skuid' => $skuid,
    'toJSON' => 1,
    'outputSeperator' => $outputSeperator,
    'category' => $category));

$productOutput = json_decode($productJSON, true);

/* Return the output for use by getPage etc */
$output = implode($outputSeparator, $productOutput);
$proxy->endSession($sessionId);
return $output;