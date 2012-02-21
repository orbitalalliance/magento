<?php
/**
 * magentoProductInfo Chunks
 *
 * @package oa_magento
 * @author S. Hamblett steve.hamblett@linux.com
 */ 

$chunks = array();

$c= $modx->newObject('modChunk');
$c->set('name', 'magentoWrapper');
$c->set('description', 'Magento product info default wrapper template');
$c->set('snippet', file_get_contents($sources['chunks'] . 'magentoWrapper.html'));
$chunks[] = $c;

$c= $modx->newObject('modChunk');
$c->set('name', 'magentoProduct');
$c->set('description', 'Magento product default template');
$c->set('snippet', file_get_contents($sources['chunks'] . 'magentoProduct.html'));
$chunks[] = $c;

$c= $modx->newObject('modChunk');
$c->set('name', 'magentoCategory');
$c->set('description', 'Magento category default template');
$c->set('snippet', file_get_contents($sources['chunks'] . 'magentoCategory.html'));
$chunks[] = $c;

$c= $modx->newObject('modChunk');
$c->set('name', 'magentoCategoryWrapper');
$c->set('description', 'Magento product info default category wrapper template');
$c->set('snippet', file_get_contents($sources['chunks'] . 'magentoCategoryWrapper.html'));
$chunks[] = $c;


