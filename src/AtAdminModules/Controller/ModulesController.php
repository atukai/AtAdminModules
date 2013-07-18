<?php

namespace AtAdminModules\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use AtAdminModules\Module;

class ModulesController extends AbstractActionController
{
    public function indexAction()
    {
        $modulesService = $this->getServiceLocator()->get('atadmin_modules_service');
        $modules = Module::getLoadedModules();

        $moduleVersions = array();
        foreach ($modules as $name => $module) {
            $moduleVersions[$name] = $modulesService->getModuleCommitHashes($name);
        }

        return array('modules' => $moduleVersions);
    }
}
