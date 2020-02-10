<?php
use Joomla\CMS\Table\Table;

defined('_JEXEC') or die;

class TablePrjProjects_thematics extends Table
{
    var $id = null;
    var $projectID = null;
    var $thematicID = null;
	public function __construct(JDatabaseDriver $db)
	{
		parent::__construct('#__mkv_projects_thematics', 'id', $db);
	}

	public function store($updateNulls = true)
    {
        return parent::store($updateNulls);
    }
}