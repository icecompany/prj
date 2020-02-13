<?php
use Joomla\CMS\MVC\View\HtmlView;

defined('_JEXEC') or die;

class PrjViewThematics extends HtmlView
{
    protected $sidebar = '';
    public $items, $pagination, $uid, $state, $filterForm, $activeFilters, $script;

    public function display($tpl = null)
    {
        $this->items = $this->get('Items');
        $this->pagination = $this->get('Pagination');
        $this->state = $this->get('State');
        $this->filterForm = $this->get('FilterForm');
        $this->activeFilters = $this->get('ActiveFilters');
        $this->script = $this->get('Script');

        // Show the toolbar
        $this->toolbar();

        // Show the sidebar
        PrjHelper::addSubmenu('thematics');
        $this->sidebar = JHtmlSidebar::render();

        // Display it all
        return parent::display($tpl);
    }

    private function toolbar()
    {
        JToolBarHelper::title(JText::sprintf('COM_PRJ_MENU_THEMATICS'), 'paragraph-left');

        if (PrjHelper::canDo('core.create'))
        {
            JToolbarHelper::addNew('thematic.add');
        }
        if (PrjHelper::canDo('core.edit'))
        {
            JToolbarHelper::editList('thematic.edit');
        }
        if (PrjHelper::canDo('core.delete'))
        {
            JToolbarHelper::deleteList('COM_PRJ_CONFIRM_REMOVE_THEMATIC', 'thematics.delete');
        }
        if (PrjHelper::canDo('core.admin'))
        {
            JToolBarHelper::preferences('com_prj');
        }
    }
}
