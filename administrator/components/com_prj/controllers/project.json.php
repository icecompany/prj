<?php
defined('_JEXEC') or die;
use Joomla\CMS\Response\JsonResponse;
use Joomla\CMS\MVC\Controller\BaseController;

class PrjControllerProject extends BaseController
{
    public function getModel($name = 'Project', $prefix = 'PrjModel', $config = array())
    {
        return parent::getModel($name, $prefix, $config);
    }

    public function execute($task)
    {
        $item = $this->getModel('Project', 'PrjModel', ['id' => JFactory::getApplication()->input->getInt('id', 0)]);
        $result = $item->getItem();
        echo new JsonResponse($result);
    }
}