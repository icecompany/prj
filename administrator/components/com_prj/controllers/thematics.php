<?php
use Joomla\CMS\MVC\Controller\AdminController;

defined('_JEXEC') or die;

class PrjControllerThematics extends AdminController
{
    public function getModel($name = 'Thematic', $prefix = 'PrjModel', $config = array())
    {
        return parent::getModel($name, $prefix, $config);
    }
}
