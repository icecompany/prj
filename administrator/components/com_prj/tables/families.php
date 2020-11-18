<?php
use Joomla\CMS\Table\Table;

defined('_JEXEC') or die;

class TablePrjFamilies extends Table
{
    var $id = null;
    var $title = null;

	public function __construct(JDatabaseDriver $db)
	{
		parent::__construct('#__mkv_project_families', 'id', $db);
	}

	public function store($updateNulls = true)
    {
        return parent::store($updateNulls);
    }
}