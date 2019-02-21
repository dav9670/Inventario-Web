<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Category $category
 */
?>
<div class="categories form large-12 medium-11 columns content">
    <?= $this->Form->create($category, ['id' => 'category_form']) ?>
    <button type="button" class="right" id="viewButton" style="width: 75px; text-align: center; padding: 10px;" onClick='setReadOnly(true)' hidden="hidden"><?=__('View')?></button>
    <button type="button" class="right" id="editButton" style="width: 75px; text-align: center; padding: 10px;" onClick='setReadOnly(false)'><?=__('Edit')?></button> 
    <fieldset>
        <legend><?= __('Category') ?></legend>
        <?php
            echo $this->Form->control('name', ['readOnly' => 'readOnly']);
            echo $this->Form->control('hourly_rate', ['readOnly' => 'readOnly']);
            echo $this->Form->control('description', ['readOnly' => 'readOnly']);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit'), ['id' => 'submit', 'hidden']) ?>
    <?= $this->Form->end() ?>
    <?= $this->Form->postLink(__('Delete category'), ['controller' => 'categories', 'action' => 'delete', $category->id], ['confirm' => __('Are you sure you want to delete {0}?', $category->name)]);?>

    <div class="related">
        <h4><?= __('Related Equipments') ?></h4>
        <?php if (!empty($category->equipments)): ?>
        <table id="related" cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"></th>
                <th scope="col"><?= __('Name') ?></th>
                <th scope="col"><?= __('Description') ?></th>
                <th scope="col"><?= __("Categories") ?></th>
                <th scope="col"><?= __("Available") ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($category->equipments as $equipment): ?>
            <tr class="clickable-row">
                <td><a href='/equipments/<?= h($equipment->id) ?>'><img src="data:image/png;base64, <?= h($equipment->image) ?>" alt="<?= h($equipment->name) ?>" width=100/></a></td>
                <td><a href='/equipments/<?= h($equipment->id) ?>'><?= h($equipment->name) ?></a></td>
                <td><a href='/equipments/<?= h($equipment->id) ?>'><?= h($equipment->description) ?></a></td>
                <?php if (count($equipment->categories_list) > 3): ?>
                    <td><a href='/equipments/<?= h($equipment->id) ?>'><?= h(implode(", ", array_slice($equipment->categories_list,0,3)) . "...") ?></a></td>
                <?php else: ?>
                    <td><a href='/equipments/<?= h($equipment->id) ?>'><?= h(implode(", ", array_slice($equipment->categories_list,0,3))) ?></a></td>
                <?php endif; ?>

                <?php if ($equipment->available): ?>
                    <td><a href='/equipments/<?= h($equipment->id) ?>'><img src='/img/good.png' alt='Available' width=20 height=20></a></td>
                <?php else: ?>
                    <td><a href='/equipments/<?= h($equipment->id) ?>'><img src='/img/bad.png' alt='Not Available' width=20 height=20></a></td>
                <?php endif; ?>

                <td class="actions">
                    <?= $this->Form->postLink(__('Unlink'), ['controller' => 'categories', 'action' => 'unlink', '?' => ['category' => $category->id, 'equipment' => $equipment->id]], ['confirm' => __('Are you sure you want to delete the association between {0} and {1}?', $equipment->name , $category->name), 'class' => 'unlink_link', 'hidden']) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>

<script>
    $("#category_form :input").change(function() {
        $("#category_form").data("changed",true);
    });

    function setReadOnly(readOnly){
        if(readOnly){
            //View
            if ($("#category_form").data("changed")) {
                if(confirm("<?=__('Return to view mode and cancel all your changes?')?>")){
                    location.reload(true);
                }
            } else {
                $('#name').attr('readOnly', readOnly);
                $('#hourly-rate').attr('readOnly', readOnly);
                $('#description').attr('readOnly', readOnly);

                $('#viewButton').hide();
                $('#submit').hide();
                $('#related a[class="unlink_link"').hide();

                $('#editButton').show();
            }
        }else{
            //Edit
            $('#name').attr('readOnly', readOnly);
            $('#hourly-rate').attr('readOnly', readOnly);
            $('#description').attr('readOnly', readOnly);

            $('#editButton').hide();

            $('#viewButton').show();
            $('#submit').show();
            $('#related a[class="unlink_link"').show();
        }
    }
</script>
