<?php
use Joomla\CMS\MVC\Controller\AdminController;

defined('_JEXEC') or die;

class PrjControllerFailies extends AdminController
{
    public function getModel($name = 'Family', $prefix = 'PrjModel', $config = array())
    {
        return parent::getModel($name, $prefix, $config);
    }
}
