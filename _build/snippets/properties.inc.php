<?php
/**
 * Default properties for pant_starrating snippet
 *
 * @package pant_starrating
 * @subpackage build
 */
$properties = array(
    array(
        'name' => 'starId',
        'desc' => 'The unique ID for this specific star rating.',
        'type' => 'textfield',
        'options' => '',
        'value' => '1',
    ),
    array(
        'name' => 'groupId',
        'desc' => 'An optional ID to group star ratings together.',
        'type' => 'textfield',
        'options' => '',
        'value' => '1',
    ),
    array(
        'name' => 'maxStars',
        'desc' => 'The number of stars used in ranking.',
        'type' => 'textfield',
        'options' => '',
        'value' => '5',
    ),
    array(
        'name' => 'starTpl',
        'desc' => 'The name of the Chunk to use for rendering the stars.',
        'type' => 'textfield',
        'options' => '',
        'value' => 'starTpl',
    ),
    array(
        'name' => 'theme',
        'desc' => 'The theme to use.',
        'type' => 'textfield',
        'options' => '',
        'value' => 'default',
    ),
    array(
        'name' => 'imgWidth',
        'desc' => 'The width of the star images.',
        'type' => 'textfield',
        'options' => '',
        'value' => '25',
    ),
    array(
        'name' => 'cssFile',
        'desc' => 'The name of the css file to use.',
        'type' => 'textfield',
        'options' => '',
        'value' => 'star',
    ),
    array(
        'name' => 'urlPrefix',
        'desc' => 'This will prefix this value to any Star Rating URLs.',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
    ),
    array(
        'name' => 'useSession',
        'desc' => 'If true, will use session to prevent multiple votes.',
        'type' => 'combo-boolean',
        'options' => '',
        'value' => '1',
    ),
    array(
        'name' => 'sessionName',
        'desc' => 'If useSession is true, this will be the name of the session to use.',
        'type' => 'textfield',
        'options' => '',
        'value' => 'starrating',
    ),
    array(
        'name' => 'useCookie',
        'desc' => 'If true, will use cookie to prevent multiple votes.',
        'type' => 'combo-boolean',
        'options' => '',
        'value' => '0',
    ),
    array(
        'name' => 'cookieName',
        'desc' => 'If useCookie is true, this will be the cookie name.',
        'type' => 'textfield',
        'options' => '',
        'value' => 'starrating',
    ),
    array(
        'name' => 'cookieExpiry',
        'desc' => 'The expiration time in seconds of the cookie.',
        'type' => 'textfield',
        'options' => '',
        'value' => '608400',
    ),
    
);

return $properties;