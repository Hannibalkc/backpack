<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ResourceFolder for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace ResourceFolder;

use ResourceFolder\Model\ResourceFolderTable;
use ResourceFolder\Model\ResourceFolder;
use ResourceFolder\Model\ResourceTable;
use ResourceFolder\Model\Resource;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

class Module
{
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
		    // if we're in a namespace deeper than one level we need to fix the \ in the path
                    __NAMESPACE__ => __DIR__ . '/src/' . str_replace('\\', '/' , __NAMESPACE__),
                ),
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
    
    public function getServiceConfig()
    {
    	return array(
    			'factories' => array(
    					'ResourceFolder\Model\ResourceFolderTable' =>  function($sm) {
    						$tableGateway = $sm->get('ResourceFolderTableGateway');
    						$table = new ResourceFolderTable($tableGateway);
    						return $table;
    					},
    					'ResourceFolderTableGateway' => function ($sm) {
    						$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
    						$resultSetPrototype = new ResultSet();
    						$resultSetPrototype->setArrayObjectPrototype(new ResourceFolder());
    						return new TableGateway('folder', $dbAdapter, null, $resultSetPrototype);
    					},
    					'ResourceFolder\Model\ResourceTable' =>  function($sm) {
    						$tableGateway = $sm->get('ResourceTableGateway');
    						$table = new ResourceTable($tableGateway);
    						return $table;
    					},
    					'ResourceTableGateway' => function ($sm) {
    						$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
    						$resultSetPrototype = new ResultSet();
    						$resultSetPrototype->setArrayObjectPrototype(new Resource());
    						return new TableGateway('resource', $dbAdapter, null, $resultSetPrototype);
    					},
    			),
    	);
    }

//     public function onBootstrap(MvcEvent $e)
//     {
//         // You may not need to do this if you're doing it elsewhere in your
//         // application
//         $eventManager        = $e->getApplication()->getEventManager();
//         $moduleRouteListener = new ModuleRouteListener();
//         $moduleRouteListener->attach($eventManager);
//     }
}
