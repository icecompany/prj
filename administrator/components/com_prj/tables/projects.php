<?php
use Joomla\CMS\Table\Table;

defined('_JEXEC') or die;

class TablePrjProjects extends Table
{
    var $id = null;
    var $title = null;
    var $title_en = null;
    var $managerID = null;
    var $groupID = null;
    var $priceID = null;
    var $columnID = null;
    var $date_start = null;
    var $date_end = null;
    var $prefix = null;
    var $is_archive = null;
	public function __construct(JDatabaseDriver $db)
	{
		parent::__construct('#__mkv_projects', 'id', $db);
	}

	public function store($updateNulls = true)
    {
        return parent::store($updateNulls);
    }
}