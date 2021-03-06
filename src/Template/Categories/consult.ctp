<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Category $category
 */
?>
<div class="categories form large-12 medium-11 columns content">
    <?= $this->Form->create($category, ['id' => 'category_form']) ?>
    <button type="button" id="editButton" class='right editdone' onClick='setReadOnly(false)'><?=__('Edit')?></button>
    <button type="button" id="doneButton" class='right editdone' onClick='doneEditing()' hidden='hidden'><?=__('Done')?></button>
    <fieldset>
        <legend><?= __('Category') ?></legend>
        <?php
            echo $this->Form->control('name', ['readOnly' => 'readOnly']);
            echo $this->Form->control('hourly_rate', ['readOnly' => 'readOnly']);
            echo $this->Form->control('description', ['type' => 'textarea', 'readOnly' => 'readOnly']);
        ?>
    </fieldset>
    <button type="button" class="right editdone" id="cancelButton" class='editdone' onClick='cancel()' hidden="hidden"><?=__('Cancel')?></button>
    <?= $this->Form->end() ?>
    <?= $this->Html->link(__('Delete category'), ['controller' => 'Categories', 'action' => 'delete', $category->id], ['class' => 'delete-link', 'confirm' => $category->equipment_count == 0 ? __('Are you sure you want to delete {0}?', $category->name) : __('Are you sure you want to delete {0}? {1} items are associated with it.', $category->name, $category->equipment_count)]);?>
    
    
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
            <tr id='equipment_row_<?=$equipment->id?>' class="clickable-row">
                <td><a href='/equipments/<?= h($equipment->id) ?>'><img src="data:image/png;base64, <?= h($equipment->image) ?>" alt="<?= h($equipment->name) ?>" width=100/></a></td>
                <td><a href='/equipments/<?= h($equipment->id) ?>'><?= h($equipment->name) ?></a></td>
                <td><a href='/equipments/<?= h($equipment->id) ?>'><?= h($equipment->description) ?></a></td>

                <?php if (count($equipment->categories_list) > 3): ?>
                    <td><a href='/equipments/<?= h($equipment->id) ?>'><?= h(implode("; ", array_slice($equipment->categories_list,0,3)) . "...") ?></a></td>
                <?php else: ?>
                    <td><a href='/equipments/<?= h($equipment->id) ?>'><?= h(implode("; ", array_slice($equipment->categories_list,0,3))) ?></a></td>
                <?php endif; ?>

                <?php if ($equipment->available): ?>
                    <td><a href='/equipments/<?= h($equipment->id) ?>'><img src='/img/good.png' alt='Available' width=20 height=20></a></td>
                <?php else: ?>
                    <td><a href='/equipments/<?= h($equipment->id) ?>'><img src='/img/bad.png' alt='Not Available' width=20 height=20></a></td>
                <?php endif; ?>

                <td class="actions">
                    <a onclick='if(confirm("<?=__('Are you sure you want to delete the association between {0} and {1}?', $equipment->name, $category->name)?>")){removeLink(<?=$equipment->id?>)}' class='unlink_link delete-link' hidden><?=__('Unlink')?></a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>

<script>
    function doneEditing(){
        if ($("#category_form").data("changed")){
            var confirmed = true;
            <?php if($category->equipment_count > 0) { ?>
                confirmed = confirm('<?= __('Are you sure you want to modify {0}? {1} items are associated with it.', $category->name, $category->equipment_count) ?>');
            <?php } ?>
            if(confirmed){
                $('#category_form').submit();
            }
        } else {
            setReadOnly(true);
        }
    }

    function cancel(){
        if(confirm("<?=__('Cancel all your changes?')?>")){
            location.reload(true);
        }
    }

    function removeLink(equipment_id){
        $.ajax({
            method: 'post',
            url : "/categories/unlink.json?category=<?=$category->id?>&equipment=" + equipment_id,
            headers: { 'X-CSRF-TOKEN': '<?=$this->getRequest()->getParam('_csrfToken');?>' },
            success: function( response ){
                $('#equipment_row_' + equipment_id).remove();
            },
            error: function(jqXHR, textStatus, errorThrown){
                alert("The association could not be deleted");
                console.log(jqXHR.responseText);
            }
        });
    }

    function setReadOnly(readOnly){
        $('#name').attr('readOnly', readOnly);
        $('#description').attr('readOnly', readOnly);
        $('#hourly-rate').attr('readOnly', readOnly);
        
        if(readOnly){
            //View
            $('#doneButton').hide();
            $('.unlink_link').hide();

            $('#editButton').show();
        }else{
            //Edit
            $('#doneButton').show();
            $('.unlink_link').show();

            $('#editButton').hide();
        }
    }

    $('document').ready(function(){
        $("#category_form :input").on('change paste keyup', (function() {
            $("#category_form").data("changed",true);
            $('#cancelButton').show();
        }));
    });
</script>
