<?php

namespace AtAdminModules;

use Zend\EventManager\EventInterface;
use Zend\ModuleManager\ModuleEvent;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\ModuleManager;

class Module implements AutoloaderProviderInterface
{
    /**
     * @var array
     */
    protected static $loadedModules = array();

    /**
     * @return array
     */
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

    /**
     * @return mixed
     */
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    /**
     * @param \Zend\ModuleManager\ModuleManager $moduleManager
     */
    public function init(ModuleManager $moduleManager)
    {
        $moduleManager->getEventManager()->attach(ModuleEvent::EVENT_LOAD_MODULES_POST, array($this, 'onModulesLoaded'));
    }

    /**
     * @param EventInterface $e
     */
    public function onBootstrap(EventInterface $e)
    {
        $application = $e->getApplication();
        $sm = $application->getServiceManager();
        $mm = $sm->get('ModuleManager');

        $enabledModules = $mm->getModules();

        if (in_array('AtAdmin', $enabledModules)) {
            /** @var \Zend\Mvc\Router\Http\TreeRouteStack $router  */
            $router = $application->getMvcEvent()->getRouter();
            $adminNavigation = $sm->get('admin_navigation');

            $systemMenuItem = $adminNavigation->findOneById('system-page');

            if ($systemMenuItem) {
        /** @todo How to dinamically add route? */

                $systemMenuItem->addPage(
                    array(
                       'label'       => 'Modules',
                        'route'       => 'zfcadmin/system/modules',
                        'router'      => $router,
                    )
                );
            }
        }
    }

    /**
     * @param \Zend\ModuleManager\ModuleEvent $e
     */
    public function onModulesLoaded(ModuleEvent $e)
    {
        self::$loadedModules = $e->getTarget()->getLoadedModules();
    }

    /**
     * @return array
     */
    public static function getLoadedModules()
    {
        return self::$loadedModules;
    }
}