<?php
use Joomla\CMS\MVC\Model\ListModel;

defined('_JEXEC') or die;

class PrjModelThematics extends ListModel
{
    public function __construct($config = array())
    {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                't.id',
                't.title',
                't.for_contractor', 'for_contractor',
                't.for_ndp', 'for_ndp',
                't.weight',
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
            ->select("t.id, t.title, t.for_contractor, t.for_ndp, t.weight")
            ->from("#__mkv_thematics t");

        /* Поиск */
        $search = $this->getState('filter.search');

        if (!empty($search)) {
            $text = $db->q("%{$search}%");
            $query->where("t.title like {$text}");
        }
        //Фильтруем тематики для подрядчиков
        $for_contractor = $this->getState('filter.for_contractor');
        if (is_numeric($for_contractor)) {
            $for_contractor = $this->_db->q($for_contractor);
            $query->where("t.for_contractor = {$for_contractor}");
        }
        //Фильтруем тематики для организаторов НДП
        $for_ndp = $this->getState('filter.for_ndp');
        if (is_numeric($for_ndp)) {
            $for_ndp = $this->_db->q($for_ndp);
            $query->where("t.for_ndp = {$for_ndp}");
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
        $result = array('items' => array(), 'projects' => array(5 => 'Армия-2019', 11 => 'Армия-2020'));
        foreach ($items as $item) {
            $arr = array();
            $arr['id'] = $item->id;
            $arr['title'] = $item->title;
            $arr['for_contractor'] = JText::sprintf((!$item->for_contractor) ? 'JNO' : 'JYES');
            $arr['for_ndp'] = JText::sprintf((!$item->for_ndp) ? 'JNO' : 'JYES');
            $arr['weight'] = $item->weight;
            $result['items'][$item->id] = $this->prepare($arr);
        }
        $result = $this->addThematicsProjects($result);

        return $result;
    }

    private function addThematicsProjects(array $result = array()): array
    {
        if (empty($result)) return array();
        $ids = array_keys($result['items']);
        $model = ListModel::getInstance('Project_thematics', 'PrjModel', array('thematics' => $ids));
        $projects = $model->getItems();
        foreach ($result['items'] as $i => $item) {
            foreach ($projects[$result['items'][$i]['id']] as $ii => $projectID) {
                $result['items'][$i]['projects'][$projectID] = true;
            }
        }
        return $result;
    }

    private function prepare(array $item): array
    {
        if (!$this->export) {
            $canDo = PrjHelper::canDo('core.edit.value');
            if ($canDo) {
                $url = JRoute::_("index.php?option={$this->option}&amp;task=thematic.edit&amp;id={$item['id']}");
                $title = $item['title'];
                $item['title'] = JHtml::link($url, $title);
            }
        }
        return $item;
    }

    /* Сортировка по умолчанию */
    protected function populateState($ordering = 't.weight', $direction = 'asc')
    {
        $search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
        $this->setState('filter.search', $search);
        $for_contractor = $this->getUserStateFromRequest($this->context . '.filter.for_contractor', 'filter_for_contractor');
        $this->setState('filter.for_contractor', $for_contractor);
        $for_ndp = $this->getUserStateFromRequest($this->context . '.filter.for_ndp', 'filter_for_ndp');
        $this->setState('filter.for_ndp', $for_ndp);
        parent::populateState($ordering, $direction);
        PrjHelper::check_refresh();
    }

    protected function getStoreId($id = '')
    {
        $id .= ':' . $this->getState('filter.search');
        $id .= ':' . $this->getState('filter.for_contractor');
        $id .= ':' . $this->getState('filter.for_ndp');
        return parent::getStoreId($id);
    }

    public function getScript()
    {
        return 'administrator/components/' . $this->option . '/models/forms/thematics.js';
    }

    private $export;
}
