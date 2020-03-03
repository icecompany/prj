<?php
use Joomla\CMS\MVC\Model\ListModel;

defined('_JEXEC') or die;

class PrjModelProjects extends ListModel
{
    public function __construct($config = array())
    {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                'p.id',
                'p.title',
                'manager',
                'p.date_start',
                'p.date_end',
                'status',
                'search',
            );
        }
        parent::__construct($config);
        $input = JFactory::getApplication()->input;
        $this->export = ($input->getString('format', 'html') === 'html') ? false : true;
        $this->for_thematics = ($config['for_thematics'] !== true) ? false : true;
        if (!empty($config['ids'])) $this->ids = $config['ids'];
        if ($this->for_thematics) $this->export = true;
    }

    protected function _getListQuery()
    {
        $db = $this->getDbo();
        $query = $db->getQuery(true);

        /* Сортировка */
        $orderCol = $this->state->get('list.ordering');
        $orderDirn = $this->state->get('list.direction');
        //Ограничение длины списка
        $limit = (!$this->export) ? $this->getState('list.limit') : 0;

        $query
            ->select("p.id, p.title, p.date_start, p.date_end")
            ->select("IF(p.date_start > current_timestamp and p.date_end > current_timestamp, 0, IF(p.date_start <= current_timestamp and p.date_end >= current_timestamp, 1, IF(p.date_start < current_timestamp and p.date_end < current_timestamp, 2, 3))) as status")
            ->select("u.name as manager")
            ->from("#__mkv_projects p")
            ->leftJoin("#__users u on u.id = p.managerID");
        if (is_array($this->ids)) {
            $ids = implode(", ", $this->ids);
            $query->where("p.id in ({$ids})");
        }

        if ($this->for_thematics) {
            $query->where("p.show_in_thematics = 1");
            $limit = 0;
        }
        else {
            /* Поиск */
            $search = $this->getState('filter.search');
            if (!empty($search)) {
                $text = $db->q("%{$search}%");
                $query->where("(p.title like {$text} or p.title_en like {$text})");
            }
        }

        $this->setState('list.limit', $limit);
        $query->order($db->escape($orderCol . ' ' . $orderDirn));

        return $query;
    }

    public function getItems()
    {
        $items = parent::getItems();
        $result = array();
        foreach ($items as $item) {
            if (!$this->for_thematics) {
                if (!isset($result['items'])) $result['items'] = array();
                $arr = array();
                $arr['id'] = $item->id;
                $arr['title'] = $item->title;
                $arr['managerID'] = $item->managerID;
                $arr['status'] = JText::sprintf("COM_PRJ_TEXT_PROJECT_STATUS_{$item->status}");
                $ds = JDate::getInstance($item->date_start);
                $de = JDate::getInstance($item->date_end);
                $arr['date_start'] = $ds->format("d.m.Y");
                $arr['date_end'] = $de->format("d.m.Y");
                $arr['manager'] = $item->manager;
                $result['items'][] = $this->prepare($arr);
            }
            else {
                $result[$item->id] = $item->title;
            }
        }
        return $result;
    }

    private function prepare(array $item): array
    {
        if (!$this->export) {
            $url = JRoute::_("index.php?option={$this->option}&amp;task=project.edit&amp;id={$item['id']}");
            $title = $item['title'];
            $item['edit_link'] = JHtml::link($url, $title);
        }
        return $item;
    }

    /* Сортировка по умолчанию */
    protected function populateState($ordering = 'status', $direction = 'asc')
    {
        $search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
        $this->setState('filter.search', $search);
        parent::populateState($ordering, $direction);

        $refresh = JFactory::getApplication()->input->getBool('refresh', false);
        if ($refresh) {
            $current = JUri::getInstance(self::getCurrentUrl());
            $current->delVar('refresh');
            JFactory::getApplication()->redirect($current);
        }
    }

    protected function getStoreId($id = '')
    {
        $id .= ':' . $this->getState('filter.search');
        return parent::getStoreId($id);
    }

    private $export, $for_thematics, $ids;
}
