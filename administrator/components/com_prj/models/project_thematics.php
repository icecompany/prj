<?php
use Joomla\CMS\MVC\Model\ListModel;

defined('_JEXEC') or die;

class PrjModelProject_thematics extends ListModel
{
    public $thematics;

    public function __construct($config = array())
    {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                'id',
                'projectID',
                'thematicID',
            );
        }
        parent::__construct($config);
        $input = JFactory::getApplication()->input;
        $this->export = ($input->getString('format', 'html') === 'html') ? false : true;
        $this->thematics = $config['thematics'] ?? array();
    }

    protected function _getListQuery()
    {

        $db = $this->getDbo();
        $query = $db->getQuery(true);

        $query
            ->select("*")
            ->from("#__mkv_projects_thematics")
            ->order("id");
        if (!empty($this->thematics)) {
            $tmp = $db->q($this->thematics);
            $thematics = implode(', ', $tmp);
            $query->where("thematicID in ({$thematics})");
        }

        $this->setState('list.limit', 0);

        return $query;
    }

    public function getItems()
    {
        $items = parent::getItems();
        $result = array();
        foreach ($items as $item) {
            if (!empty($this->thematics)) {
                if (!isset($result[$item->thematicID])) $result[$item->thematicID] = array();
                $result[$item->thematicID][] = $item->projectID;
            }
            else {
                $arr = array();
                $arr['id'] = $item->id;
                $arr['projectID'] = $item->projectID;
                $arr['rubricID'] = $item->thematicID;
                $result[] = $arr;
            }
        }
        return $result;
    }

    private $export;
}
