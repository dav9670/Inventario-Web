<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Service $service
 */
?>
<div class="services form large-12 medium-11 columns content">
    <?= $this->Form->create($service, ['id' => 'service_form']) ?>
    <button type="button" id="editButton" class='right editdone' onClick='setReadOnly(false)'><?=__('Edit')?></button>
    <button type="button" id="doneButton" class='right editdone' onClick='doneEditing()' hidden='hidden'><?=__('Done')?></button>
    <fieldset>
        <legend><?= __('Service') ?></legend>
        <?php
            echo $this->Form->control('name', ['readOnly' => 'readOnly']);
            echo $this->Form->control('description', ['type' => 'textarea', 'readOnly' => 'readOnly']);
        ?>
    </fieldset>
    <button type="button" class="right editdone" id="cancelButton" class='editdone' onClick='cancel()' hidden="hidden"><?=__('Cancel')?></button>
    <?= $this->Form->end() ?>
    <?= $this->Html->link(__('Delete service'), ['controller' => 'Services', 'action' => 'delete', $service->id], ['confirm' => $service->room_count == 0 ? __('Are you sure you want to delete {0}?', $service->name) : __('Are you sure you want to delete {0}? {1} items are associated with it.', $service->name, $service->room_count)]);?>
 
    
    <div class="related">
        <h4><?= __('Related Rooms') ?></h4>
        <?php if (!empty($service->rooms)): ?>
        <table id="related" cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"></th>
                <th scope="col"><?= __('Name') ?></th>
                <th scope="col"><?= __('Description') ?></th>
                <th scope="col"><?= __("Services") ?></th>
                <th scope="col"><?= __("Available") ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($service->rooms as $room): ?>
            <tr class="clickable-row">
                <td><a href='/rooms/<?= h($room->id) ?>'><img src="data:image/png;base64, <?= h($room->image) ?>" alt="<?= h($room->name) ?>" width=100/></a></td>
                <td><a href='/rooms/<?= h($room->id) ?>'><?= h($room->name) ?></a></td>
                <td><a href='/rooms/<?= h($room->id) ?>'><?= h($room->description) ?></a></td>

                <?php if (count($room->services_list) > 3): ?>
                    <td><a href='/rooms/<?= h($room->id) ?>'><?= h(implode(", ", array_slice($room->services_list,0,3)) . "...") ?></a></td>
                <?php else: ?>
                    <td><a href='/rooms/<?= h($room->id) ?>'><?= h(implode(", ", array_slice($room->services_list,0,3))) ?></a></td>
                <?php endif; ?>

                <?php if ($room->available): ?>
                    <td><a href='/rooms/<?= h($room->id) ?>'><img src='/img/good.png' alt='Available' width=20 height=20></a></td>
                <?php else: ?>
                    <td><a href='/rooms/<?= h($room->id) ?>'><img src='/img/bad.png' alt='Not Available' width=20 height=20></a></td>
                <?php endif; ?>

                <td class="actions">
                    <?= $this->Form->postLink(__('Unlink'), ['controller' => 'services', 'action' => 'unlink', '?' => ['service' => $service->id, 'room' => $room->id]], ['confirm' => __('Are you sure you want to delete the association between {0} and {1}?', $room->name, $service->name), 'class' => 'unlink_link', 'hidden']) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>

<script>
    function doneEditing(){
        if ($("#service_form").data("changed")){
            var confirmed = true;
            <?php if($service->room_count > 0) { ?>
                confirmed = confirm('<?= __('Are you sure you want to modify {0}? {1} items are associated with it.', $service->name, $service->room_count) ?>');
            <?php } ?>
            if(confirmed){
                $('#service_form').submit();
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

    function setReadOnly(readOnly){
        if(readOnly){
            //View
            $('#name').attr('readOnly', readOnly);
            $('#description').attr('readOnly', readOnly);

            $('#doneButton').hide();
            $('#related a[class="unlink_link"').hide();

            $('#editButton').show();
        }else{
            //Edit
            $('#name').attr('readOnly', readOnly);
            $('#description').attr('readOnly', readOnly);

            $('#editButton').hide();

            $('#doneButton').show();
            $('#submit').show();
            $('#related a[class="unlink_link"').show();
        }
    }

    $('document').ready(function(){
        $("#service_form :input").on('change paste keyup', (function() {
            $("#service_form").data("changed",true);
            $('#cancelButton').show();
        }));
    });
</script>
