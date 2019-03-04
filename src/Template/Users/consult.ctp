<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
?>
<div class="users form large-12 medium-11 columns content">
    <?= $this->Form->create($user, ['id' => 'user_form', 'type' => 'file']) ?>
    <button type="button" id="editButton" class='right editdone' onClick='setReadOnly(false)'><?=__('Edit')?></button>
    <button type="button" id="doneButton" class='right editdone' onClick='doneEditing()' hidden='hidden'><?=__('Done')?></button>
    <fieldset>
        <legend><?= __('Edit User') ?></legend>

        <div class="left twothirds-width">
            <?php
                echo $this->Form->control('email', ['readOnly' => 'readOnly']);
                echo $this->Form->control('password', ['readOnly' => 'readOnly']);
                echo $this->Form->select('admin_status', ['admin', 'user'], ['disabled' => 'disabled', 'id' => 'admin']);
            ?>
        </div>

        <div class="right third-width">
            <?php echo $this->Form->control('image', ['type' => 'file', 'accept'=> 'image/*', 'onchange' => 'loadFile(event)', 'hidden' => 'hidden', 'disabled' => 'disabled']); ?>
            <img src='data:image/png;base64,<?=$user->image?>' id='output'/>
        </div>
        <div style="clear: both;"></div>
    </fieldset>
    
    <?php 
        if($user->deleted == null){
            echo $this->Html->link(__('Deactivate user'), ['controller' => 'Users', 'action' => 'deactivate', $user->id], ['class' => 'delete-link', 'confirm' => __('Are you sure you want to deactivate {0}?', $user->email)]);
        } else {
            echo $this->Html->link(__('Reactivate user'), ['controller' => 'Users', 'action' => 'reactivate', $user->id], ['confirm' => __('Are you sure you want to reactivate {0}?', $user->email), 'style' => 'margin-right: 25px;']);  
            if($user->loan_count == 0){
                echo $this->Html->link(__('Delete user'), ['controller' => 'Users', 'action' => 'delete', $user->id], ['class' => 'delete-link', 'confirm' => __('Are you sure you want to PERMANENTLY delete {0}?', $user->email)]);
            }
        }
    ?>
    <button type="button" class="right editdone" id="cancelButton" class='editdone' onClick='cancel()' hidden="hidden"><?=__('Cancel')?></button>
    
    
    <div class = 'loans'>
    <h3><?=__('Loans')?></h3>
    <input id='autocomplete' type ='text' style='display:none'>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= __("Loan type") ?></a></th>
                <th scope="col"><?= __("Start date") ?></a></th>
                <th scope="col"><?= __("End Date") ?></th>
                <th scope="col"><?= __("Returned") ?></a></th>
                <th scope="col"><?= __("Overtime Fee") ?></a></th>
            </tr>
        </thead>
        <tbody id='loans_table_body'>
            <?php foreach ($user->loans as $loan): ?>
            <tr id='loan_row_<?=$loan->id?>'>
                <td><a href='loans/<?=$loan->id?>'><?= h($loan->item_type) ?></a></td>
                <td><a href='loans/<?=$loan->id?>'><?= h($loan->start_date)?></a></td>
                <td><a href='loans/<?=$loan->id?>'><?= h($loan->end_date)?></a></td>
                <td><a href='loans/<?=$loan->id?>'><?= h($loan->returned)?></a></td>
                <td><a href='loans/<?=$loan->id?>'><?= h($loan->overtimeFee)?></a></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?= $this->Form->end() ?>
    </div>
</div>
<script>
    function loadFile(event) {
        $('#output').attr('src', URL.createObjectURL(event.target.files[0])); 
    }

    function doneEditing(){
        if ($("#user_form").data("changed")){
            $('#user_form').submit();
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
        $('#password').attr('readOnly', readOnly); 

        
        if(readOnly){
            //View
            $('#image').hide();
            $('#image').attr('disabled', 'disabled');
            $('#autocomplete').hide(); 
            $('#admin').attr('disabled', 'disabled');



            $('.loans').show();

            $('#doneButton').hide();

            $('#editButton').show();
        }else{
            //Edit
            $('#image').show();
            $('#image').removeAttr('disabled');
            $('#autocomplete').show();
            $('#admin').removeAttr('disabled');

            

            $('.loans').hide();

            $('#doneButton').show();

            $('#editButton').hide();
        }
    }

    $('document').ready(function(){
        $("#user_form :input:not(#autocomplete)").on('change paste keyup', (function() {
            $("#user_form").data("changed",true);
            $('#cancelButton').show();
        }));

        $("#autocomplete").autocomplete({
            source: function(request, show){
                $.ajax({
                    method: 'get',
                    url : "/loans/search.json",
                    data: {keyword: request.term, sort_field: 'name', sort_dir: 'asc', user_id: '<?=$user->id?>'},
                    success: function( response ){
                        var results = [];
                        $.each(response.loans, function(idx, elem){
                            if(!$('#loan_row_' + elem.id).length){
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
                    url : "/loans/link.json?loan=" + elem.id + "&user=<?= $user->id ?>",
                    headers: { 'X-CSRF-TOKEN': '<?=$this->getRequest()->getParam('_csrfToken');?>' },
                    success: function( response ){
                        let table = $('#loans_table_body');
                        table.append(`
                            <tr>
                                <td><a href='loans/` + elem.id + `'>` + elem.name + `</a></td>
                                <td><a href='loans/` + elem.id + `'>` + elem.description + `</a></td>
                                <td><a href='/loans/` + elem.id + `'>` + elem.user_count + `</a></td>
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