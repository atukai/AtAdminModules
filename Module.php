<?php

namespace AtAdminModules;

use AtAdminModules\Service\Module as ModuleService;
use Zend\EventManager\EventInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\DependencyIndicatorInterface;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;

class Module implements
    AutoloaderProviderInterface,
    ConfigProviderInterface,
    DependencyIndicatorInterface,
    ServiceProviderInterface
{
    public function getModuleDependencies()
    {
        return array('AtAdmin');
    }

    public function getAutoloaderConfig()
    {
        return array(
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

    /**
     * @return array
     */
    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'at_admin_module_service' => function ($sm) {
                    return new ModuleService($sm->get('ModuleManager'));
                }
            )
        );
    }

    /**
     * @param EventInterface $e
     */
    public function onBootstrap(EventInterface $e)
    {
        $application = $e->getApplication();
        $sm = $application->getServiceManager();

        /** @var \Zend\Mvc\Router\Http\TreeRouteStack $router  */
        $router = $application->getMvcEvent()->getRouter();

        $systemMenuItem = $sm->get('admin_navigation')->findOneById('system-page');
        if ($systemMenuItem) {
            $systemMenuItem->addPage(
                array(
                    'label'  => 'Modules',
                    'route'  => 'zfcadmin/system/modules',
                    'router' => $router,
                )
            );
        }
    }
}