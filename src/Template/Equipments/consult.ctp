<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Equipment $equipment
 */
?>
<div class="equipments form large-12 medium-11 columns content">
    <?= $this->Form->create($equipment, ['id' => 'equipment_form', 'type' => 'file']) ?>
    <button type="button" id="editButton" class='right editdone' onClick='setReadOnly(false)'><?=__('Edit')?></button>
    <button type="button" id="doneButton" class='right editdone' onClick='doneEditing()' hidden='hidden'><?=__('Done')?></button>
    <fieldset>
        <legend><?= __('Edit Equipment') ?></legend>
        <?php
            echo $this->Form->control('name', ['readOnly' => 'readOnly']);
            echo $this->Form->control('description', ['readOnly' => 'readOnly']);
            echo $this->Form->control('image', ['type' => 'file', 'accept'=> 'image/*', 'onchange' => 'loadFile(event)', 'hidden' => 'hidden', 'disabled' => 'disabled']);
        ?>
    </fieldset>
    <img src='data:image/png;base64,<?=$equipment->image?>' id='output' style='max-width:200px; max-height:200px;'/><br/>
    <?php 
        if($equipment->deleted == null){
            echo $this->Html->link(__('Deactivate equipment'), ['controller' => 'Equipments', 'action' => 'deactivate', $equipment->id], ['class' => 'delete-link', 'confirm' => __('Are you sure you want to deactivate {0}?', $equipment->name)]);
        } else {
            echo $this->Html->link(__('Reactivate equipment'), ['controller' => 'Equipments', 'action' => 'reactivate', $equipment->id], ['confirm' => __('Are you sure you want to reactivate {0}?', $equipment->name)]);  
            if($equipment->loan_count == 0){
                echo $this->Html->link(__('Delete equipment'), ['controller' => 'Equipments', 'action' => 'delete', $equipment->id], ['class' => 'delete-link', 'confirm' => __('Are you sure you want to PERMANENTLY delete {0}?', $equipment->name)]);
            }
        }
    ?>
    <button type="button" class="right editdone" id="cancelButton" class='editdone' onClick='cancel()' hidden="hidden"><?=__('Cancel')?></button>
    
    
    
    <h3><?=__('Categories')?></h3>
    <input id='autocomplete' type ='text' style='display:none'>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= __("Name") ?></a></th>
                <th scope="col"><?= __("Description") ?></a></th>
                <th scope="col"><?= __("Equipment count") ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody id='categories_table_body'>
            <?php foreach ($equipment->categories as $category): ?>
            <tr id='category_row_<?=$category->id?>'>
                <td><a href='categories/<?=$category->id?>'><?= h($category->name) ?></a></td>
                <td><a href='categories/<?=$category->id?>'><?= h($category->description)?></a></td>
                <td><a href='categories/<?=$category->id?>'><?= h($category->equipment_count)?></a></td>
                <td><a class='unlink_link delete-link' onclick='removeLink(<?=$category->id?>)' style="display:none;">Remove</a></td>
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
        if ($("#equipment_form").data("changed")){
            $('#equipment_form').submit();
        } else {
            setReadOnly(true);
        }
    }

    function cancel(){
        if(confirm("<?=__('Cancel all your changes?')?>")){
            location.reload(true);
        }
    }

    function removeLink(category_id){
        $.ajax({
            method: 'post',
            url : "/categories/unlink.json?category=" + category_id + "&equipment=<?= $equipment->id ?>",
            headers: { 'X-CSRF-TOKEN': '<?=$this->getRequest()->getParam('_csrfToken');?>' },
            success: function( response ){
                $('#category_row_' + category_id).remove();
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
        $("#equipment_form :input:not(#autocomplete)").on('change paste keyup', (function() {
            $("#equipment_form").data("changed",true);
            $('#cancelButton').show();
        }));

        $("#autocomplete").autocomplete({
            source: function(request, show){
                $.ajax({
                    method: 'get',
                    url : "/categories/search.json",
                    data: {keyword: request.term, sort_field: 'name', sort_dir: 'asc', equipment_id: '<?=$equipment->id?>'},
                    success: function( response ){
                        var results = [];
                        $.each(response.categories, function(idx, elem){
                            if(!$('#category_row_' + elem.id).length){
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
                    url : "/categories/link.json?category=" + elem.id + "&equipment=<?= $equipment->id ?>",
                    headers: { 'X-CSRF-TOKEN': '<?=$this->getRequest()->getParam('_csrfToken');?>' },
                    success: function( response ){
                        let table = $('#categories_table_body');
                        
                        let nameCell = "<td><a href='/categories/" + elem.id + "'>" + elem.name + "</a></td>";
                        let descriptionCell = "<td><a href='/categories/" + elem.id + "'>" + elem.description + "</a></td>";
                        let equipmentCountCell = "<td><a href='/categories/" + elem.id + "'>" + elem.equipment_count + "</a></td>";
                        
                        let deleteLink = "<a class='unlink_link delete-link' onclick='removeLink(" + elem.id + ")'><?=__('Remove')?></a>";
                        
                        let actionsCell = "<td class=\"actions\">";
                        actionsCell = actionsCell.concat(deleteLink);
                        actionsCell = actionsCell.concat("</td>");

                        table.append("<tr id='category_row_" + elem.id +"'>" + nameCell + descriptionCell + equipmentCountCell + actionsCell + "</tr>");

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