<?php
defined('_JEXEC') or die;
$listOrder    = $this->escape($this->state->get('list.ordering'));
$listDirn    = $this->escape($this->state->get('list.direction'));
?>
<tr>
    <th style="width: 1%;">
        <?php echo JHtml::_('grid.checkall'); ?>
    </th>
    <th style="width: 1%;">
        №
    </th>
    <th>
        <?php echo JHtml::_('searchtools.sort', 'COM_PRJ_HEAD_THEMATICS_TITLE', 't.title', $listDirn, $listOrder); ?>
    </th>
    <?php foreach ($this->items['projects'] as $projectID => $project): ?>
        <th class="center"><?php echo $project;?></th>
    <?php endforeach;?>
    <th>
        <?php echo JHtml::_('searchtools.sort', 'COM_PRJ_HEAD_THEMATICS_FOR_CONTRACTOR', 't.for_contractor', $listDirn, $listOrder); ?>
    </th>
    <th>
        <?php echo JHtml::_('searchtools.sort', 'COM_PRJ_HEAD_THEMATICS_FOR_NDP', 't.for_ndp', $listDirn, $listOrder); ?>
    </th>
    <th>
        <?php echo JHtml::_('searchtools.sort', 'COM_PRJ_SORTING_THEMATICS_WEIGHT_ASC', 't.weight', $listDirn, $listOrder); ?>
    </th>
    <th>
        <?php echo JHtml::_('searchtools.sort', 'ID', 't.id', $listDirn, $listOrder); ?>
    </th>
</tr>