<?php
use Joomla\CMS\Table\Table;

defined('_JEXEC') or die;

class TablePrjThematics extends Table
{
    var $id = null;
    var $title = null;
    var $for_contractor = null;
    var $for_ndp = null;
    var $published = null;
	public function __construct(JDatabaseDriver $db)
	{
		parent::__construct('#__mkv_thematics', 'id', $db);
	}

	public function store($updateNulls = true)
    {
        return parent::store($updateNulls);
    }
}