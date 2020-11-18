<?php
defined('_JEXEC') or die;
use Joomla\CMS\MVC\View\HtmlView;

class PrjViewFamily extends HtmlView {
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
	    JToolBarHelper::apply('family.apply', 'JTOOLBAR_APPLY');
        JToolbarHelper::save('family.save', 'JTOOLBAR_SAVE');
        JToolbarHelper::cancel('family.cancel', 'JTOOLBAR_CLOSE');
    }

    protected function setDocument() {
        $title = ($this->item->id !== null) ? JText::sprintf('COM_PRJ_TITLE_EDIT_FAMILY', $this->item->title) : JText::sprintf('COM_PRJ_TITLE_ADD_FAMILY');
        JToolbarHelper::title($title, 'tree');
        JHtml::_('bootstrap.framework');
    }
}