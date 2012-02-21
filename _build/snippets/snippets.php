<?php
/**
 * pant_starrating Snippet
 *
 * @package pant_starrating
 * @author S. Hamblett steve.hamblett@linux.com
 */ 

$snippets = array();
$s = $modx->newObject('modSnippet');
$s->set('name', 'pant_starRating');
$s->set('description', 'A Star Rating snippet for MODx Revolution');
$s->set('snippet', file_get_contents($sources['snippets'] . 'starrating.snippet.php'));
$snippets[] = $s;

$s = $modx->newObject('modSnippet');
$s->set('name', 'pant_starRatingTopVotes');
$s->set('description', 'Get the top rated votes');
$s->set('snippet', file_get_contents($sources['snippets'] . 'topvotes.starrating.snippet.php'));
$snippets[] = $s;

