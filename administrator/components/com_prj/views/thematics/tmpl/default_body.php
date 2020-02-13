<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;
$ii = $this->state->get('list.start', 0);
foreach ($this->items['items'] as $i => $item) :
    ?>
    <tr class="row0">
        <td class="center">
            <?php echo JHtml::_('grid.id', $i, $item['id']); ?>
        </td>
        <td>
            <?php echo ++$ii; ?>
        </td>
        <td>
            <?php echo $item['title'];?>
        </td>
        <?php foreach ($this->items['projects'] as $projectID => $project): ?>
            <td class="center">
                <input type="checkbox" onclick="change_thematic(this);" data-thematic="<?php echo $i;?>" data-project="<?php echo $projectID;?>" value="1" <?php if ($item['projects'][$projectID]) echo 'checked';?>>
            </td>
        <?php endforeach;?>
        <td>
            <?php echo $item['for_contractor'];?>
        </td>
        <td>
            <?php echo $item['for_ndp'];?>
        </td>
        <td>
            <?php echo $item['weight'];?>
        </td>
        <td>
            <?php echo $item['id'];?>
        </td>
    </tr>
<?php endforeach; ?>