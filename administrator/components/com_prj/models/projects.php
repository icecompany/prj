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
                'manager',
                'p.date_start',
                'p.date_end',
                'search',
            );
        }
        parent::__construct($config);
        $input = JFactory::getApplication()->input;
        $this->export = ($input->getString('format', 'html') === 'html') ? false : true;
    }

    protected function _getListQuery()
    {

        $db = $this->getDbo();
        $query = $db->getQuery(true);

        /* Сортировка */
        $orderCol = $this->state->get('list.ordering');
        $orderDirn = $this->state->get('list.direction');

        $query
            ->select("p.id, p.title, p.date_start, p.date_end")
            ->select("u.name as manager")
            ->from("#__mkv_projects p")
            ->leftJoin("#__users u on u.id = p.managerID");
        /* Поиск */
        $search = $this->getState('filter.search');

        if (!empty($search)) {
            $text = $db->q("%{$search}%");
            $query->where("(p.title like {$text} or p.title_en like {$text})");
        }
        //Ограничение длины списка
        $limit = (!$this->export) ? $this->getState('list.limit') : 0;
        $this->setState('list.limit', $limit);

        $query->order($db->escape($orderCol . ' ' . $orderDirn));

        return $query;
    }

    public function getItems()
    {
        $items = parent::getItems();
        $result = array('items' => array());
        foreach ($items as $item) {
            $arr = array();
            $arr['id'] = $item->id;
            $arr['title'] = $item->title;
            $arr['managerID'] = $item->managerID;
            $arr['date_start'] = $item->date_start;
            $arr['date_end'] = $item->date_end;
            $arr['manager'] = $item->manager;
            $result['items'][] = $this->prepare($arr);
        }
        return $result;
    }

    private function prepare(array $item): array
    {
        if (!$this->export) {
            $managerID = JFactory::getUser()->id;
            $canDo = (PrjHelper::canDo('core.edit.value') || (!PrjHelper::canDo('core.edit.value') && PrjHelper::canDo('core.edit.own') && $item['managerID'] == $managerID));
            if ($canDo) {
                $url = JRoute::_("index.php?option={$this->option}&amp;task=project.edit&amp;id={$item['id']}");
                $title = $item['title'];
                $item['title'] = JHtml::link($url, $title);
            }
        }
        return $item;
    }

    /* Сортировка по умолчанию */
    protected function populateState($ordering = 'p.title', $direction = 'asc')
    {
        $search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
        $this->setState('filter.search', $search);
        parent::populateState($ordering, $direction);
    }

    protected function getStoreId($id = '')
    {
        $id .= ':' . $this->getState('filter.search');
        return parent::getStoreId($id);
    }

    private $export;
}
