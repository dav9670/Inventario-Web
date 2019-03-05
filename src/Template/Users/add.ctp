<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
?>
<div class="users form large-12 medium-11 columns content">
    <?= $this->Form->create($user, ['type' => 'file']) ?>
    <fieldset>
        <legend><?= __('Add User') ?></legend>
        <div class="left twothirds-width">
            <?php
                echo $this->Form->control('email');
                echo $this->Form->control('password');
                echo $this->Form->select('admin_status', ['user', 'admin']);
            ?>
        </div>
        <div class="right third-width">
            <?php echo $this->Form->control('image', ['type' => 'file', 'accept'=> 'image/*', 'onchange' => 'loadFile(event)']); ?>
            <img id='output' <?php if($user->image != null) { echo "src='data:image/png;base64," . $user->image . "'"; } ?>/>
        </div>
        <div style="clear: both;"></div>
    </fieldset>
    <?= $this->Form->button(__('Save')) ?>
    <?= $this->Form->end() ?>
</div>
<script>
    function loadFile(event) {
        $('#output').attr('src', URL.createObjectURL(event.target.files[0])); 
    }

    function removeLink(id) {
        if(confirm('<?= __('Are you sure you want to remove this skill?')?>')){
            $('#skill_row_' + id).remove();
        }
    }

    $('document').ready(function(){
        $("#autocomplete").autocomplete({
            source: function(request, show){
                $.ajax({
                    method: 'get',
                    url : "/skills/search.json",
                    data: {keyword: request.term, sort_field: 'name', sort_dir: 'asc'},
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

                table.append(`
                    <tr id='skill_row_` + elem.id + `'>
                        <input type='hidden' name='skills[_ids][]' value='` + elem.id + `'/>
                        <td><a href='skills/` + elem.id + `'>` + elem.name + `</a></td>
                        <td><a href='skills/` + elem.id + `'>` + elem.description + `</a></td>
                        <td><a href='/skills/` + elem.id + `'>` + elem.user_count + `</a></td>
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