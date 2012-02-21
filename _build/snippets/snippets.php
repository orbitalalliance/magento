<?php
/**
 * magentoProduct Info Snippet
 *
 * @package oa_magento
 * @author S. Hamblett steve.hamblett@linux.com
 */ 

$snippets = array();
$s = $modx->newObject('modSnippet');
$s->set('name', 'magentoProductInfo');
$s->set('description', 'A Magento store product snippet for MODx Revolution. Gets information for products,see the properties for set up values and the snippet itself for parameters and placeholders.');
$s->set('snippet', file_get_contents($sources['snippets'] . 'magentoProductInfo.php'));
$snippets[] = $s;


