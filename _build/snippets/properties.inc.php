<?php
/**
 * Default properties for the magentoProductInfo snippet
 *
 * @package oa_magento
 * @subpackage build
 */
$properties = array(
    array(
        'name' => 'WSDLURL',
        'desc' => 'The WSDL URL for the stores Soap API.',
        'type' => 'textfield',
        'options' => '',
        'value' => 'http://shamblett.gostorego.com/api/soap/?wsdl',
    ),
    array(
        'name' => 'apiKey',
        'desc' => 'Magento store API Key(user password).',
        'type' => 'textfield',
        'options' => '',
        'value' => 'byrt3kav',
    ),
    array(
        'name' => 'apiUser',
        'desc' => 'Magento store API user(user name).',
        'type' => 'textfield',
        'options' => '',
        'value' => 'shamblett',
    )
);

return $properties;