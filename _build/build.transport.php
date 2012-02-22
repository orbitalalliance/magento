<?php
/**
 * Magento e-commerce interface (oa_magento)
 *
 * @author S. Hamblett <steve.hamblett@linux.com>
 * For Orbital Alliance
 *
 * @package  oa_magento
 */

$mtime = microtime();
$mtime = explode(" ", $mtime);
$mtime = $mtime[1] + $mtime[0];
$tstart = $mtime;
set_time_limit(0);

$base = dirname(dirname(__FILE__)) . '/';
$sources = array(
    'root' => $base . '/',
    'assets' => 'assets/components/oa_magento',
    'core' => 'core/components/oa_magento',
    'docs' => $base . '/assets/components/oa_magento/docs/',
    'chunks' => 'chunks/',
    'snippets' => 'snippets/',
    'templates' => 'templates/',
    'plugins' => 'plugins/',
    'events' => 'plugins/events/',
    'properties' => 'properties/',
    'resolvers' => 'resolvers/',
    'settings' => 'settings/',
    'resources' => 'resources/',
    'source_core' => $base . '/core/components/oa_magento',
    'source_assets' => $base . '/assets/components/oa_magento',
    'lexicon' => $base . 'core/components/oa_magento/lexicon/',
    'model' => $base . 'core/components/oa_magento/model/',
);
unset($base);

require_once dirname(__FILE__) . '/build.config.php';
require_once MODX_CORE_PATH . 'model/modx/modx.class.php';
$modx = new modX();
$modx->initialize('mgr');
echo '<pre>'; /* used for nice formatting of log messages */
$modx->setLogLevel(modX::LOG_LEVEL_INFO);
$modx->setLogTarget('ECHO');

$name = 'oa_magento';
$version = '1.0.0';
$release = 'pl';

$modx->loadClass('transport.modPackageBuilder', '', false, true);
$builder = new modPackageBuilder($modx);
$builder->createPackage($name, $version, $release);
$builder->registerNamespace('oa_magento', false, true, '{core_path}components/oa_magento/');

$attr = array(
    xPDOTransport::UNIQUE_KEY => 'category',
    xPDOTransport::PRESERVE_KEYS => false,
    xPDOTransport::UPDATE_OBJECT => true,
    xPDOTransport::RELATED_OBJECTS => true,
    xPDOTransport::RELATED_OBJECT_ATTRIBUTES => array(
        'modChunk' => array(
            xPDOTransport::PRESERVE_KEYS => false,
            xPDOTransport::UPDATE_OBJECT => true,
            xPDOTransport::UNIQUE_KEY => 'name'),
        ),
        'modSnippet' => array(
            xPDOTransport::PRESERVE_KEYS => false,
            xPDOTransport::UPDATE_OBJECT => true,
            xPDOTransport::UNIQUE_KEY => 'name')
);

$category = $modx->newObject('modCategory');
$category->set('category', 'oa_magento');

/* Get chunks */
include_once($sources['chunks'] . 'chunks.php');

/* Get snippets */
include_once($sources['snippets'] . 'snippets.php');
$properties = include $sources['snippets'].'properties.inc.php';
foreach ( $snippets as $snippet ) {
    $snippet->setProperties($properties);
}
unset($properties);

/* Add category items */
$category->addMany($chunks);
$category->addMany($snippets);

/* create a transport vehicle for the category data object */
$vehicle = $builder->createVehicle($category, $attr);
$vehicles[] = $vehicle;

$vehicle->resolve('file', array(
    'source' => $sources['source_assets'],
    'target' => "return MODX_ASSETS_PATH . 'components/';"));
$vehicle->resolve('file', array(
    'source' => $sources['source_core'],
    'target' => "return MODX_CORE_PATH . 'components/';"));


/* Add all the vehicles */
foreach ($vehicles as $vehicle) {
    $builder->putVehicle($vehicle);
}

/* now pack in the license file, readme and setup options */
$builder->setPackageAttributes(array(
    'license' => file_get_contents($sources['docs'] . 'LICENSE.txt'),
    'readme' => file_get_contents($sources['docs'] . 'README.txt'),
));

/* zip up the package */
$builder->pack();

$mtime = microtime();
$mtime = explode(" ", $mtime);
$mtime = $mtime[1] + $mtime[0];
$tend = $mtime;
$totalTime = ($tend - $tstart);
$totalTime = sprintf("%2.4f s", $totalTime);

$modx->log(MODX_LOG_LEVEL_INFO, "<br />\nPackage Built.<br />\nExecution time: {$totalTime}\n");

exit();