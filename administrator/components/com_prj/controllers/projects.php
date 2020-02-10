<?php
use Joomla\CMS\MVC\Controller\AdminController;

defined('_JEXEC') or die;

class PrjControllerProjects extends AdminController
{
    public function getModel($name = 'Project', $prefix = 'PrjModel', $config = array())
    {
        return parent::getModel($name, $prefix, $config);
    }
}
