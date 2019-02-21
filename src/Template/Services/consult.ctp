<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Service $service
 */
?>
<div class="services form large-12 medium-11 columns content">
    <?= $this->Form->create($service, ['id' => 'service_form']) ?>
    <button type="button" class="right" id="viewButton" style="width: 75px; text-align: center; padding: 10px;" onClick='setReadOnly(true)' hidden="hidden"><?=__('View')?></button>
    <button type="button" class="right" id="editButton" style="width: 75px; text-align: center; padding: 10px;" onClick='setReadOnly(false)'><?=__('Edit')?></button> 
    <fieldset>
        <legend><?= __('Service') ?></legend>
        <?php
            echo $this->Form->control('name', ['readOnly' => 'readOnly']);
            echo $this->Form->control('description', ['readOnly' => 'readOnly']);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit'), ['id' => 'submit', 'hidden']) ?>
    <?= $this->Form->end() ?>
    <?= $this->Form->postLink(__('Delete service'), ['controller' => 'Services', 'action' => 'delete', $service->id], ['confirm' => __('Are you sure you want to delete {0}?', $service->name)]);?>
    
    
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
    $("#service_form :input").change(function() {
        $("#service_form").data("changed",true);
    });

    function setReadOnly(readOnly){
        if(readOnly){
            //View
            if ($("#service_form").data("changed")) {
                if(confirm("<?=__('Return to view mode and cancel all your changes?')?>")){
                    location.reload(true);
                }
            } else {
                $('#name').attr('readOnly', readOnly);
                $('#description').attr('readOnly', readOnly);

                $('#viewButton').hide();
                $('#submit').hide();
                $('#related a[class="unlink_link"').hide();

                $('#editButton').show();
            }
        }else{
            //Edit
            $('#name').attr('readOnly', readOnly);
            $('#description').attr('readOnly', readOnly);

            $('#editButton').hide();

            $('#viewButton').show();
            $('#submit').show();
            $('#related a[class="unlink_link"').show();
        }
    }
</script>
