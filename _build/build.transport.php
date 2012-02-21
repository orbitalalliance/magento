<?php

/**
 * Statistics (pant_statistics)
 *
 * @author    S. Hamblett <steve.hamblett@linux.com>
 * For Pantechnicon
 *
 * @package  pant_statistics
 */

$mtime = microtime();
$mtime = explode(" ", $mtime);
$mtime = $mtime[1] + $mtime[0];
$tstart = $mtime;
set_time_limit(0);

$base = dirname(dirname(__FILE__)) . '/';
$sources = array(
    'root' => $base . '/',
    'assets' => 'assets/components/pant_statistics',
    'core' => 'core/components/pant_statistics',
    'docs' => $base . '/assets/components/pant_statistics/docs/',
    'chunks' => 'chunks/',
    'snippets' => 'snippets/',
    'templates' => 'templates/',
    'plugins' => 'plugins/',
    'events' => 'plugins/events/',
    'properties' => 'properties/',
    'resolvers' => 'resolvers/',
    'settings' => 'settings/',
    'resources' => 'resources/',
    'source_core' => $base . '/core/components/pant_statistics',
    'source_assets' => $base . '/assets/components/pant_statistics',
    'lexicon' => $base . 'core/components/pant_statistics/lexicon/',
    'model' => $base . 'core/components/pant_statistics/model/',
);
unset($base);

require_once dirname(__FILE__) . '/build.config.php';
require_once MODX_CORE_PATH . 'model/modx/modx.class.php';
$modx = new modX();
$modx->initialize('mgr');
echo '<pre>'; /* used for nice formatting of log messages */
$modx->setLogLevel(modX::LOG_LEVEL_INFO);
$modx->setLogTarget('ECHO');

$name = 'pant_statistics';
$version = '1.0.0';
$release = 'beta';

$modx->loadClass('transport.modPackageBuilder', '', false, true);
$builder = new modPackageBuilder($modx);
$builder->createPackage($name, $version, $release);
$builder->registerNamespace('pant_statistics', false, true, '{core_path}components/pant_statistics/');

$attr = array(
    xPDOTransport::UNIQUE_KEY => 'category',
    xPDOTransport::PRESERVE_KEYS => false,
    xPDOTransport::UPDATE_OBJECT => true,
    xPDOTransport::RELATED_OBJECTS => true,
    xPDOTransport::RELATED_OBJECT_ATTRIBUTES => array(
        'modPlugin' => array(
            xPDOTransport::PRESERVE_KEYS => false,
            xPDOTransport::UPDATE_OBJECT => true,
            xPDOTransport::UNIQUE_KEY => 'name'),
        xPDOTransport::RELATED_OBJECTS => true,
        xPDOTransport::RELATED_OBJECT_ATTRIBUTES => array(
            'modPluginEvent' => array(
                xPDOTransport::PRESERVE_KEYS => true,
                xPDOTransport::UPDATE_OBJECT => false,
                xPDOTransport::UNIQUE_KEY => array('pluginid', 'event')
        )))
);

$category = $modx->newObject('modCategory');
$category->set('category', 'pant_statistics');

/* Get plugin */
include_once($sources['plugins'] . 'plugins.php');

/* Add category items */
$category->addMany($plugins);


/* create a transport vehicle for the category data object */
$vehicle = $builder->createVehicle($category, $attr);
$vehicles[] = $vehicle;

/* Resolvers, both php and file on the last vehicle */
$vehicle = end($vehicles);

$vehicle->resolve('file', array(
    'source' => $sources['source_assets'],
    'target' => "return MODX_ASSETS_PATH . 'components/';"));

$vehicle->resolve('file', array(
    'source' => $sources['source_core'],
    'target' => "return MODX_CORE_PATH . 'components/';"));

$vehicle->resolve('php',array(
    'source' => $sources['resolvers'] . 'tables.resolver.php',
    ));
$vehicle->resolve('php', array(
    'type' => 'php',
    'source' => $sources['resolvers'] . 'resolver.php'));

/* Add all the vehicles */
foreach ($vehicles as $vehicle) {
    $builder->putVehicle($vehicle);
}

/* now pack in the license file, readme and setup options */
$builder->setPackageAttributes(array(
    'license' => file_get_contents($sources['docs'] . 'license.txt'),
    'readme' => file_get_contents($sources['docs'] . 'readme.txt'),
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
