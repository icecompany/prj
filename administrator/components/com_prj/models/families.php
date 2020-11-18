<?php
use Joomla\CMS\MVC\Model\ListModel;

defined('_JEXEC') or die;

class PrjModelFamilies extends ListModel
{
    public function __construct($config = array())
    {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                'f.id',
                'f.title',
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
            ->select("f.id, f.title")
            ->from("#__mkv_project_families f");

        /* Поиск */
        $search = $this->getState('filter.search');

        if (!empty($search)) {
            $text = $db->q("%{$search}%");
            $query->where("f.title like {$text}");
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
        $result = [];
        $return = PrjHelper::getReturnUrl();
        foreach ($items as $item) {
            $arr = array();
            $arr['id'] = $item->id;
            $arr['title'] = $item->title;
            $url = JRoute::_("index.php?option={$this->option}&amp;task=family.edit&amp;id={$item->id}&amp;return={$return}");
            $arr['edit_link'] = JHtml::link($url, $item->title);
            $result[$item->id] = $arr;
        }

        return $result;
    }

    /* Сортировка по умолчанию */
    protected function populateState($ordering = 'f.title', $direction = 'ASC')
    {
        $search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
        $this->setState('filter.search', $search);
        parent::populateState($ordering, $direction);
        PrjHelper::check_refresh();
    }

    protected function getStoreId($id = '')
    {
        $id .= ':' . $this->getState('filter.search');
        return parent::getStoreId($id);
    }

    private $export;
}
