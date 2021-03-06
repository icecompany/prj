<?php
defined('_JEXEC') or die;
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

class JFormFieldProject extends JFormFieldList
{
    protected $type = 'Project';
    protected $loadExternally = 0;

    protected function getOptions()
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query
            ->select("`id`, `title`")
            ->from('#__mkv_projects')
            ->order("`title`");
        $userGroups = implode(', ', JFactory::getUser()->groups);
        if (!empty($userGroups)) $query->where("groupID in ({$userGroups})");
        $result = $db->setQuery($query)->loadObjectList();

        $options = array();

        foreach ($result as $item) {
            $options[] = JHtml::_('select.option', $item->id, $item->title);
        }

        if (!$this->loadExternally) {
            $options = array_merge(parent::getOptions(), $options);
        }

        return $options;
    }

    public function getOptionsExternally()
    {
        $this->loadExternally = 1;
        return $this->getOptions();
    }
}