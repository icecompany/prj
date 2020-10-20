<?php
defined('_JEXEC') or die;
use Joomla\CMS\MVC\Model\AdminModel;

class PrjModelProject extends AdminModel {

    public function getItem($pk = null)
    {
        return parent::getItem($pk);
    }

    public function save($data)
    {
        if ($data['id'] === null) {
            $app = JFactory::getApplication();
            //Создание прайса для проекта
            $s1 = $this->createPrice($data['title']);
            if ($s1 === 0) {
                $app->enqueueMessage(JText::sprintf('COM_PRJ_ERROR_CREATE_PRICE', JFactory::getDbo()->getErrorMsg()), 'error');
                return false;
            }
            $data['priceID'] = $s1;
            //Создание каталога стендов для проекта
            $s2 = $this->createSandCatalog($data['title']);
            if ($s2 === 0) {
                $app->enqueueMessage(JText::sprintf('COM_PRJ_ERROR_CREATE_CATALOG', JFactory::getDbo()->getErrorMsg()), 'error');
                return false;
            }
            $data['catalogID'] = $s2;
            //Создание группы пользователей для проекта
            $s3 = $this->createUserGroup($data['title']);
            if (!$s3) {
                $app->enqueueMessage(JText::sprintf('COM_PRJ_ERROR_CREATE_USER_GROUP'), 'error');
                return false;
            }
            $data['groupID'] = PrjHelper::getLastUserGroupID();
        }

        return parent::save($data);
    }

    private function createUserGroup(string $title): bool
    {
        $parent_group = PrjHelper::getConfig('parent_group', 40);
        JModelLegacy::addIncludePath(JPATH_ADMINISTRATOR . "/components/com_users/models", 'UsersModel');
        $model = JModelLegacy::getInstance('Group', 'UsersModel');
        return $model->save(['id' => 0, 'title' => $title, 'parent_id' => $parent_group]);
    }

    private function createPrice(string $title): int
    {
        JTable::addIncludePath(JPATH_ADMINISTRATOR."/components/com_prices/tables");
        $arr = ['id' => null, 'title' => JText::sprintf('COM_PRJ_TITLE_NEW_PRICE_NAME', $title)];
        $table = JTable::getInstance('Prices', 'TablePrices');
        return (!$table->save($arr)) ? 0 : JFactory::getDbo()->insertid();
    }

    private function createSandCatalog(string $title): int
    {
        JTable::addIncludePath(JPATH_ADMINISTRATOR."/components/com_stands/tables");
        $arr = ['id' => null, 'title' => JText::sprintf('COM_PRJ_TITLE_NEW_STAND_CATALOG_NAME', $title)];
        $table = JTable::getInstance('Catalogs', 'TableStands');
        return (!$table->save($arr)) ? 0 : JFactory::getDbo()->insertid();
    }

    public function getTable($name = 'Projects', $prefix = 'TablePrj', $options = array())
    {
        return JTable::getInstance($name, $prefix, $options);
    }

    public function getForm($data = array(), $loadData = true)
    {
        $form = $this->loadForm(
            $this->option.'.project', 'project', array('control' => 'jform', 'load_data' => $loadData)
        );
        $form->addFieldPath(JPATH_ADMINISTRATOR."/components/com_prices/models/fields");
        $form->addFieldPath(JPATH_ADMINISTRATOR."/components/com_stands/models/fields");
        $form->addFieldPath(JPATH_ADMINISTRATOR."/components/com_mkv/models/fields");

        if (empty($form))
        {
            return false;
        }

        return $form;
    }

    protected function loadFormData()
    {
        $data = JFactory::getApplication()->getUserState($this->option.'.edit.project.data', array());
        if (empty($data))
        {
            $data = $this->getItem();
        }

        return $data;
    }

    protected function prepareTable($table)
    {
        $all = get_class_vars($table);
        unset($all['_errors']);
        $nulls = ['title_en', 'prefix', 'catalogID']; //Поля, которые NULL
        foreach ($all as $field => $v) {
            if (empty($field)) continue;
            if (in_array($field, $nulls)) {
                if (!strlen($table->$field)) {
                    $table->$field = NULL;
                    continue;
                }
            }
            if (!empty($field)) $table->$field = trim($table->$field);
            if ($field == 'date_start' || $field == 'date_end') $table->$field = JDate::getInstance($table->$field)->toSql();
        }
        parent::prepareTable($table);
    }

    protected function canEditState($record)
    {
        $user = JFactory::getUser();

        if (!empty($record->id))
        {
            return $user->authorise('core.edit.state', $this->option . '.project.' . (int) $record->id);
        }
        else
        {
            return parent::canEditState($record);
        }
    }

    public function getScript()
    {
        return 'administrator/components/' . $this->option . '/models/forms/project.js';
    }
}