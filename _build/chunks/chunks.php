<?php
/**
 * pant_starrating Chunks
 *
 * @package pant_starrating
 * @author S. Hamblett steve.hamblett@linux.com
 */ 

$chunks = array();
$c= $modx->newObject('modChunk');
$c->set('name', 'pant_starTpl');
$c->set('description', 'Star Rating Default Template');
$c->set('snippet', file_get_contents($sources['chunks'] . 'starTpl.html'));
$chunks[] = $c;

$c= $modx->newObject('modChunk');
$c->set('name', 'pant_starTopVotesTpl');
$c->set('description', 'Star Rating Default Top Votes Template');
$c->set('snippet', file_get_contents($sources['chunks'] . 'starTopVotesTpl.html'));
$chunks[] = $c;

