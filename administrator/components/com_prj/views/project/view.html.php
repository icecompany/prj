<?php
defined('_JEXEC') or die;
use Joomla\CMS\MVC\View\HtmlView;

class PrjViewProject extends HtmlView {
    protected $item, $form, $script;

    public function display($tmp = null) {
        $this->form = $this->get('Form');
        $this->item = $this->get('Item');
        $this->script = $this->get('Script');

        $this->addToolbar();
        $this->setDocument();

        parent::display($tmp);
    }

    protected function addToolbar() {
	    JToolBarHelper::apply('project.apply', 'JTOOLBAR_APPLY');
        JToolbarHelper::save('project.save', 'JTOOLBAR_SAVE');
        JToolbarHelper::cancel('project.cancel', 'JTOOLBAR_CLOSE');
    }

    protected function setDocument() {
        $title = ($this->item->id !== null) ? JText::sprintf('COM_PRJ_TITLE_EDIT_PROJECT', $this->item->title) : JText::sprintf('COM_PRJ_TITLE_ADD_PROJECT');
        JToolbarHelper::title($title, 'briefcase');
        JHtml::_('bootstrap.framework');
    }
}