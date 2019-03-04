<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Room $room
 */
?>
<div class="rooms form large-12 medium-11 columns content">
    <?= $this->Form->create($room, ['type' => 'file']) ?>
    <fieldset>
        <legend><?= __('Add Room') ?></legend>
        <div class="left twothirds-width">
            <?php
                echo $this->Form->control('name');
                echo $this->Form->control('description', ['type' => 'textarea']);
            ?>
        </div>
        <div class="right third-width">
            <?php echo $this->Form->control('image', ['type' => 'file', 'accept'=> 'image/*', 'onchange' => 'loadFile(event)']); ?>
            <img id='output'/>
        </div>
        <div style="clear: both;"></div>
    </fieldset>
    
    <h3><?=__('Services')?></h3>
    <input id='autocomplete' type ='text'>
    <input type='hidden' name='services[_ids]' value=""/>
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
        </tbody>
    </table>
    <?= $this->Form->button(__('Save')) ?>
    <?= $this->Form->end() ?>
</div>
<script>
    function loadFile(event) {
        $('#output').attr('src', URL.createObjectURL(event.target.files[0])); 
    }

    function removeLink(id) {
        if(confirm('<?= __('Are you sure you want to remove this service?')?>')){
            $('#service_row_' + id).remove();
        }
    }

    $('document').ready(function(){
        $("#autocomplete").autocomplete({
            source: function(request, show){
                $.ajax({
                    method: 'get',
                    url : "/services/search.json",
                    data: {keyword: request.term, sort_field: 'name', sort_dir: 'asc'},
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
                        console.log(textStatus);
                    }
                });
            },
            minLength: 1,
            autoFocus: true,
            select: function (event, ui) {
                let table = $('#services_table_body');
                let elem = ui.item.data;

                table.append(`
                    <tr id='service_row_` + elem.id + `'>
                        <input type='hidden' name='services[_ids][]' value='` + elem.id + `'/>
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
            }
        });
    });
</script>