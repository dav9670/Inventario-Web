<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Mentor $mentor
 */
?>
<div class="mentors form large-12 medium-11 columns content">
    <?= $this->Form->create($mentor, ['id' => 'mentor_form', 'type' => 'file']) ?>
    <button type="button" id="editButton" class='right editdone' onClick='setReadOnly(false)'><?=__('Edit')?></button>
    <button type="button" id="doneButton" class='right editdone' onClick='doneEditing()' hidden='hidden'><?=__('Done')?></button>
    <fieldset>
        <legend><?= __('Edit Mentor') ?></legend>
        <?php
            echo $this->Form->control('email', ['readOnly' => 'readOnly']);
            echo $this->Form->control('first_name', ['readOnly' => 'readOnly']);
            echo $this->Form->control('last_name', ['readOnly' => 'readOnly']);
            echo $this->Form->control('description', ['readOnly' => 'readOnly']);
            echo $this->Form->control('image', ['type' => 'file', 'accept'=> 'image/*', 'onchange' => 'loadFile(event)', 'hidden' => 'hidden', 'disabled' => 'disabled']);
        ?>
    </fieldset>
    <img src='data:image/png;base64,<?=$mentor->image?>' id='output' style='max-width:200px; max-height:200px;'/>
    <button type="button" class="right editdone" id="cancelButton" class='editdone' onClick='cancel()' hidden="hidden"><?=__('Cancel')?></button>
    <h3><?=__('Skills')?></h3>
    <input id='autocomplete' type ='text' style='display:none'>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= __("Name") ?></a></th>
                <th scope="col"><?= __("Description") ?></a></th>
                <th scope="col"><?= __("Mentor count") ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody id='skills_table_body'>
            <?php foreach ($mentor->skills as $skill): ?>
            <tr>
                <td><a href='skills/<?=$skill->id?>'><?= h($skill->name) ?></a></td>
                <td><a href='skills/<?=$skill->id?>'><?= h($skill->description)?></a></td>
                <td><a href='skills/<?=$skill->id?>'><?= h($skill->mentor_count)?></a></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?= $this->Form->button(__('Save')) ?>
    <?= $this->Form->end() ?>
</div>
<script>
    function loadFile(event) {
        $('#output').attr('src', URL.createObjectURL(event.target.files[0])); 
    }

    function doneEditing(){
        if ($("#mentor_form").data("changed")){
            $('#mentor_form').submit();
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
        $('#email').attr('readOnly', readOnly);
        $('#first-name').attr('readOnly', readOnly);
        $('#last-name').attr('readOnly', readOnly);
        $('#description').attr('readOnly', readOnly);
        
        if(readOnly){
            //View
            $('#image').hide();
            $('#autocomplete').hide();

            $('#doneButton').hide();
            $('#related a[class="unlink_link"').hide();

            $('#editButton').show();
        }else{
            //Edit
            $('#image').show();
            $('#autocomplete').show();

            $('#doneButton').show();
            $('#related a[class="unlink_link"').show();

            $('#editButton').hide();
        }
    }

    $('document').ready(function(){
        $("#mentor_form :input").on('change paste keyup', (function() {
            $("#mentor_form").data("changed",true);
            $('#cancelButton').show();
        }));

        $("#autocomplete").autocomplete({
            source: function(request, show){
                $.ajax({
                    method: 'get',
                    url : "/skills/search.json",
                    data: {keyword: request.term, sort_field: 'name', sort_dir: 'asc', mentor_id: '<?=$mentor->id?>'},
                    success: function( response ){
                        var results = [];
                        $.each(response.skills, function(idx, elem){
                            if(!$('#skill_row_' + elem.id).length){
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
                let table = $('#skills_table_body');
                let elem = ui.item.data;

                let nameCell = "<td><a href='/skills/" + elem.id + "'>" + elem.name + "</a></td>";
                let descriptionCell = "<td><a href='/skills/" + elem.id + "'>" + elem.description + "</a></td>";
                let mentorCountCell = "<td><a href='/skills/" + elem.id + "'>" + elem.mentor_count + "</a></td>";
                let actionsCell = "<td class=\"actions\">";
                //var deleteLink = "<a onclick='removeRow(" + elem.id + ")'>Remove</a>";
                
                //actionsCell = actionsCell.concat(deleteLink);
                actionsCell = actionsCell.concat("</td>");

                table.append("<tr id='skill_row_" + elem.id +"'>" + input + nameCell + descriptionCell + mentorCountCell + actionsCell + "</tr>");

                $('#autocomplete').val('');
                event.preventDefault();
            }
        });
    });
</script>