<?php

namespace AtAdminModules\Controller;

use Zend\Mvc\Controller\AbstractActionController;

class ModulesController extends AbstractActionController
{
    public function indexAction()
    {
        $sl = $this->getServiceLocator();
        $moduleService = $sl->get('atadmin_module_service');

        $modules = array();
        foreach ($sl->get('ModuleManager')->getLoadedModules() as $name => $module) {
            $modules[$name] = $moduleService->getModuleInfo($name);
        }

        return array(
            'modules' => $modules
        );
    }
}
