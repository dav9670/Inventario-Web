<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Room $room
 */
?>
<div class="rooms form large-12 medium-11 columns content">
    <?= $this->Form->create($room, ['id' => 'room_form', 'type' => 'file']) ?>
    <button type="button" id="editButton" class='right editdone' onClick='setReadOnly(false)'><?=__('Edit')?></button>
    <button type="button" id="doneButton" class='right editdone' onClick='doneEditing()' hidden='hidden'><?=__('Done')?></button>
    <fieldset>
        <legend><?= __('Edit Room') ?></legend>
        <div class="left twothirds-width">
            <?php
                echo $this->Form->control('name', ['readOnly' => 'readOnly']);
                echo $this->Form->control('description', ['readOnly' => 'readOnly']);
            ?>
        </div>
        <div class="right third-width">
            <?php echo $this->Form->control('image', ['type' => 'file', 'accept'=> 'image/*', 'onchange' => 'loadFile(event)', 'hidden' => 'hidden', 'disabled' => 'disabled']); ?>
            <img src='data:image/png;base64,<?=$room->image?>' id='output'/>
        </div>

        <div style="clear: both;"></div>
    </fieldset>
    
    <?php 
        if($room->deleted == null){
            echo $this->Html->link(__('Deactivate room'), ['controller' => 'Rooms', 'action' => 'deactivate', $room->id], ['class' => 'delete-link', 'confirm' => __('Are you sure you want to deactivate {0}?', $room->name)]);
        } else {
            echo $this->Html->link(__('Reactivate room'), ['controller' => 'Rooms', 'action' => 'reactivate', $room->id], ['confirm' => __('Are you sure you want to reactivate {0}?', $room->name), 'style' => 'margin-right: 25px;']);  
            if($room->loan_count == 0){
                echo $this->Html->link(__('Delete room'), ['controller' => 'Rooms', 'action' => 'delete', $room->id], ['class' => 'delete-link', 'confirm' => __('Are you sure you want to PERMANENTLY delete {0}?', $room->name)]);
            }
        }
    ?>
    <button type="button" class="right editdone" id="cancelButton" class='editdone' onClick='cancel()' hidden="hidden"><?=__('Cancel')?></button>
    
    
    
    <h3><?=__('Services')?></h3>
    <input id='autocomplete' type ='text' style='display:none'>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= __("Name") ?></a></th>
                <th scope="col"><?= __("Description") ?></a></th>
                <th scope="col"><?= __("Room count") ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody id='services_table_body'>
            <?php foreach ($room->services as $service): ?>
            <tr id='service_row_<?=$service->id?>'>
                <td><a href='services/<?=$service->id?>'><?= h($service->name) ?></a></td>
                <td><a href='services/<?=$service->id?>'><?= h($service->description)?></a></td>
                <td><a href='services/<?=$service->id?>'><?= h($service->room_count)?></a></td>
                <td><a class='unlink_link delete-link' onclick='removeLink(<?=$service->id?>)' style="display:none;">Remove</a></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?= $this->Form->end() ?>
</div>
<script>
    function loadFile(event) {
        $('#output').attr('src', URL.createObjectURL(event.target.files[0])); 
    }

    function doneEditing(){
        if ($("#room_form").data("changed")){
            $('#room_form').submit();
        } else {
            setReadOnly(true);
        }
    }

    function cancel(){
        if(confirm("<?=__('Cancel all your changes?')?>")){
            location.reload(true);
        }
    }

    function removeLink(service_id){
        $.ajax({
            method: 'post',
            url : "/services/unlink.json?service=" + service_id + "&room=<?= $room->id ?>",
            headers: { 'X-CSRF-TOKEN': '<?=$this->getRequest()->getParam('_csrfToken');?>' },
            success: function( response ){
                $('#service_row_' + service_id).remove();
            },
            error: function(jqXHR, textStatus, errorThrown){
                alert('The association could not be deleted');
                console.log(jqXHR.responseText);
            }
        });
    }

    function setReadOnly(readOnly){
        $('#name').attr('readOnly', readOnly);
        $('#description').attr('readOnly', readOnly);
        
        if(readOnly){
            //View
            $('#image').hide();
            $('#image').attr('disabled', 'disabled');
            $('#autocomplete').hide();

            $('#doneButton').hide();
            $('.unlink_link').hide();

            $('#editButton').show();
        }else{
            //Edit
            $('#image').show();
            $('#image').removeAttr('disabled');
            $('#autocomplete').show();

            $('#doneButton').show();
            $('.unlink_link').show();

            $('#editButton').hide();
        }
    }

    $('document').ready(function(){
        $("#room_form :input:not(#autocomplete)").on('change paste keyup', (function() {
            $("#room_form").data("changed",true);
            $('#cancelButton').show();
        }));

        $("#autocomplete").autocomplete({
            source: function(request, show){
                $.ajax({
                    method: 'get',
                    url : "/services/search.json",
                    data: {keyword: request.term, sort_field: 'name', sort_dir: 'asc', room_id: '<?=$room->id?>'},
                    success: function( response ){
                        var results = [];
                        $.each(response.services, function(idx, elem){
                            if(!$('#service_row_' + elem.id).length){
                                var entry = {
                                    label: elem.name,
                                    data: elem
                                };
                                results.push(entry);
                            }
                        });
                        show(results);
                    },
                    error: function(jqXHR, textStatus, errorThrown){
                        console.log(jqXHR.responseText);
                    }
                });
            },
            minLength: 1,
            autoFocus: true,
            select: function (event, ui) {
                let elem = ui.item.data;

                $.ajax({
                    method: 'post',
                    url : "/services/link.json?service=" + elem.id + "&room=<?= $room->id ?>",
                    headers: { 'X-CSRF-TOKEN': '<?=$this->getRequest()->getParam('_csrfToken');?>' },
                    success: function( response ){
                        let table = $('#services_table_body');
                        table.append(`
                            <tr>
                                <td><a href='services/` + elem.id + `'>` + elem.name + `</a></td>
                                <td><a href='services/` + elem.id + `'>` + elem.description + `</a></td>
                                <td><a href='/services/` + elem.id + `'>` + elem.room_count + `</a></td>
                                <td class='actions'>
                                    <a class='unlink_link delete-link' onclick='removeLink(` + elem.id + `)'><?=__('Remove')?></a>
                                </td>
                            </tr>
                        `);

                        $('#autocomplete').val('');
                        event.preventDefault();
                    },
                    error: function(jqXHR, textStatus, errorThrown){
                        console.log(jqXHR.responseText);
                    }
                });
            }
        });
    });
</script>