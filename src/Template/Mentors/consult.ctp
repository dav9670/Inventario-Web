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

        <div class="left twothirds-width">
            <?php
                echo $this->Form->control('email', ['readOnly' => 'readOnly']);
                echo $this->Form->control('first_name', ['readOnly' => 'readOnly']);
                echo $this->Form->control('last_name', ['readOnly' => 'readOnly']);
                echo $this->Form->control('description', ['readOnly' => 'readOnly', 'type' => 'textarea']);
            ?>
        </div>

        <div class="right third-width">
            <?php echo $this->Form->control('image', ['type' => 'file', 'accept'=> 'image/*', 'onchange' => 'loadFile(event)', 'hidden' => 'hidden', 'disabled' => 'disabled']); ?>
            <img src='data:image/png;base64,<?=$mentor->image?>' id='output'/>
        </div>
        <div style="clear: both;"></div>
    </fieldset>
    
    <?php 
        if($mentor->deleted == null){
            echo $this->Html->link(__('Deactivate mentor'), ['controller' => 'Mentors', 'action' => 'deactivate', $mentor->id], ['class' => 'delete-link', 'confirm' => __('Are you sure you want to deactivate {0}?', $mentor->email)]);
        } else {
            echo $this->Html->link(__('Reactivate mentor'), ['controller' => 'Mentors', 'action' => 'reactivate', $mentor->id], ['confirm' => __('Are you sure you want to reactivate {0}?', $mentor->email), 'style' => 'margin-right: 25px;']);  
            if($mentor->loan_count == 0){
                echo $this->Html->link(__('Delete mentor'), ['controller' => 'Mentors', 'action' => 'delete', $mentor->id], ['class' => 'delete-link', 'confirm' => __('Are you sure you want to PERMANENTLY delete {0}?', $mentor->email)]);
            }
        }
    ?>
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
            <tr id='skill_row_<?=$skill->id?>'>
                <td><a href='/skills/<?=$skill->id?>'><?= h($skill->name) ?></a></td>
                <td><a href='/skills/<?=$skill->id?>'><?= h($skill->description)?></a></td>
                <td><a href='/skills/<?=$skill->id?>'><?= h($skill->mentor_count)?></a></td>
                <td><a class='unlink_link delete-link' onclick='removeLink(<?=$skill->id?>)' style="display:none;">Remove</a></td>
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

    function removeLink(skill_id){
        $.ajax({
            method: 'post',
            url : "/skills/unlink.json?skill=" + skill_id + "&mentor=<?= $mentor->id ?>",
            headers: { 'X-CSRF-TOKEN': '<?=$this->getRequest()->getParam('_csrfToken');?>' },
            success: function( response ){
                $('#skill_row_' + skill_id).remove();
            },
            error: function(jqXHR, textStatus, errorThrown){
                alert('The association could not be deleted');
                console.log(jqXHR.responseText);
            }
        });
    }

    function setReadOnly(readOnly){
        $('#email').attr('readOnly', readOnly);
        $('#first-name').attr('readOnly', readOnly);
        $('#last-name').attr('readOnly', readOnly);
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
        $("#mentor_form :input:not(#autocomplete)").on('change paste keyup', (function() {
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
                    url : "/skills/link.json?skill=" + elem.id + "&mentor=<?= $mentor->id ?>",
                    headers: { 'X-CSRF-TOKEN': '<?=$this->getRequest()->getParam('_csrfToken');?>' },
                    success: function( response ){
                        let table = $('#skills_table_body');
                        table.append(`
                            <tr id="skill_row_` + elem.id + `">
                                <td><a href='/skills/` + elem.id + `'>` + elem.name + `</a></td>
                                <td><a href='/skills/` + elem.id + `'>` + elem.description + `</a></td>
                                <td><a href='/skills/` + elem.id + `'>` + elem.mentor_count + `</a></td>
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