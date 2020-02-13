<?php
defined('_JEXEC') or die;
use Joomla\CMS\Response\JsonResponse;
use Joomla\CMS\MVC\Controller\BaseController;

class PrjControllerProject_thematic extends BaseController
{
    public function getModel($name = 'Project_thematic', $prefix = 'PrjModel', $config = array())
    {
        return parent::getModel($name, $prefix, $config);
    }

    public function execute($task)
    {
        $result = $this->getModel()->toggle();
        echo new JsonResponse($result);
    }
}