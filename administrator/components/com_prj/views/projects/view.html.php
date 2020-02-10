<?php
use Joomla\CMS\MVC\View\HtmlView;

defined('_JEXEC') or die;

class PrjViewProjects extends HtmlView
{
    protected $sidebar = '';
    public $items, $pagination, $uid, $state, $filterForm, $activeFilters;

    public function display($tpl = null)
    {
        $this->items = $this->get('Items');
        $this->pagination = $this->get('Pagination');
        $this->state = $this->get('State');
        $this->filterForm = $this->get('FilterForm');
        $this->activeFilters = $this->get('ActiveFilters');

        // Show the toolbar
        $this->toolbar();

        // Show the sidebar
        PrjHelper::addSubmenu('projects');
        $this->sidebar = JHtmlSidebar::render();

        // Display it all
        return parent::display($tpl);
    }

    private function toolbar()
    {
        JToolBarHelper::title(JText::sprintf('COM_PRJ_MENU_PROJECTS'), 'briefcase');

        if (PrjHelper::canDo('core.create'))
        {
            JToolbarHelper::addNew('project.add');
        }
        if (PrjHelper::canDo('core.edit'))
        {
            JToolbarHelper::editList('project.edit');
        }
        if (PrjHelper::canDo('core.delete'))
        {
            JToolbarHelper::deleteList('COM_PRJ_CONFIRM_REMOVE_PROJECT', 'projects.delete');
        }
        if (PrjHelper::canDo('core.admin'))
        {
            JToolBarHelper::preferences('com_prj');
        }
    }
}
