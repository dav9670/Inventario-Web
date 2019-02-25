<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Equipment $equipment
 */
?>
<div class="equipments form large-12 medium-11 columns content">
    <?= $this->Form->create($equipment, ['type' => 'file']) ?>
    <fieldset>
        <legend><?= __('Add Equipment') ?></legend>
        <?php
            echo $this->Form->control('name');
            echo $this->Form->control('description', ['type' => 'textarea']);
            echo $this->Form->control('image', ['type' => 'file', 'accept'=> 'image/*', 'onchange' => 'loadFile(event)']);
        ?>
        <img id='output' style='max-width:200px; max-height:200px;'/>

    </fieldset>
    <h3><?=__('Categories')?></h3>
    <input id='autocomplete' type ='text'>
    <input type='hidden' name='categories[_ids]' value=""/>
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
        </tbody>
    </table>
    <?= $this->Form->button(__('Save')) ?>
    <?= $this->Form->end() ?>
</div>
<script>
    function loadFile(event) {
        $('#output').attr('src', URL.createObjectURL(event.target.files[0])); 
    }

    function removeRow(id) {
        if(confirm('<?= __('Are you sure you want to remove this category?')?>')){
            $('#category_row_' + id).remove();
        }
    }

    $('document').ready(function(){
        $("#autocomplete").autocomplete({
            source: function(request, show){
                $.ajax({
                    method: 'get',
                    url : "/categories/search.json",
                    data: {keyword: request.term, sort_field: 'name', sort_dir: 'asc'},
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
                        console.log(textStatus);
                    }
                });
            },
            minLength: 1,
            autoFocus: true,
            select: function (event, ui) {
                let table = $('#categories_table_body');
                let elem = ui.item.data;

                let input = "<input type='hidden' name='categories[_ids][]' value='" + elem.id + "'/>";

                let nameCell = "<td><a href='/categories/" + elem.id + "'>" + elem.name + "</a></td>";
                let descriptionCell = "<td><a href='/categories/" + elem.id + "'>" + elem.description + "</a></td>";
                let equipmentCountCell = "<td><a href='/categories/" + elem.id + "'>" + elem.equipment_count + "</a></td>";
                let actionsCell = "<td class=\"actions\">";
                var deleteLink = "<a onclick='removeRow(" + elem.id + ")'>Remove</a>";
                
                actionsCell = actionsCell.concat(deleteLink);
                actionsCell = actionsCell.concat("</td>");

                table.append("<tr id='category_row_" + elem.id +"'>" + input + nameCell + descriptionCell + equipmentCountCell + actionsCell + "</tr>");

                $('#autocomplete').val('');
                event.preventDefault();
            }
        });
    });
</script>