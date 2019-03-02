<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Skill $skill
 */
?>
<div class="skills form large-12 medium-11 columns content">
    <?= $this->Form->create($skill, ['id' => 'skill_form']) ?>
    <button type="button" id="editButton" class='right editdone' onClick='setReadOnly(false)'><?=__('Edit')?></button>
    <button type="button" id="doneButton" class='right editdone' onClick='doneEditing()' hidden='hidden'><?=__('Done')?></button>
    <fieldset>
        <legend><?= __('Skill') ?></legend>
        <?php
            echo $this->Form->control('name', ['readOnly' => 'readOnly']);
            echo $this->Form->control('description', ['type' => 'textarea', 'readOnly' => 'readOnly']);
        ?>
    </fieldset>
    <button type="button" class="right editdone" id="cancelButton" class='editdone' onClick='cancel()' hidden="hidden"><?=__('Cancel')?></button>
    <?= $this->Form->end() ?>
    <?= $this->Html->link(__('Delete skill'), ['controller' => 'Skills', 'action' => 'delete', $skill->id], ['class' => 'delete-link', 'confirm' => $skill->mentor_count == 0 ? __('Are you sure you want to delete {0}?', $skill->name) : __('Are you sure you want to delete {0}? {1} items are associated with it.', $skill->name, $skill->mentor_count)]);?>
    
    
    <div class="related">
        <h4><?= __('Related Mentors') ?></h4>
        <?php if (!empty($skill->mentors)): ?>
        <table id="related" cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"></th>
                <th scope="col"><?= __('Email') ?></th>
                <th scope="col"><?= __('First Name') ?></th>
                <th scope="col"><?= __('Last Name') ?></th>
                <th scope="col"><?= __('Description') ?></th>
                <th scope="col"><?= __("Skills") ?></th>
                <th scope="col"><?= __("Available") ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($skill->mentors as $mentor): ?>
            <tr id='mentor_row_<?=$mentor->id?>' class="clickable-row">
                <td><a href='/mentors/<?= h($mentor->id) ?>'><img src="data:image/png;base64, <?= h($mentor->image) ?>" alt="<?= h($mentor->first_name) ?> <?= h($mentor->last_name) ?>" width=100/></a></td>
                <td><a href='/mentors/<?= h($mentor->id) ?>'><?= h($mentor->email) ?></a></td>
                <td><a href='/mentors/<?= h($mentor->id) ?>'><?= h($mentor->first_name) ?></a></td>
                <td><a href='/mentors/<?= h($mentor->id) ?>'><?= h($mentor->last_name) ?></a></td>
                <td><a href='/mentors/<?= h($mentor->id) ?>'><?= h($mentor->description) ?></a></td>

                <?php if (count($mentor->skills_list) > 3): ?>
                    <td><a href='/mentors/<?= h($mentor->id) ?>'><?= h(implode("; ", array_slice($mentor->skills_list,0,3)) . "...") ?></a></td>
                <?php else: ?>
                    <td><a href='/mentors/<?= h($mentor->id) ?>'><?= h(implode("; ", array_slice($mentor->skills_list,0,3))) ?></a></td>
                <?php endif; ?>

                <?php if ($mentor->available): ?>
                    <td><a href='/mentors/<?= h($mentor->id) ?>'><img src='/img/good.png' alt='Available' width=20 height=20></a></td>
                <?php else: ?>
                    <td><a href='/mentors/<?= h($mentor->id) ?>'><img src='/img/bad.png' alt='Not Available' width=20 height=20></a></td>
                <?php endif; ?>

                <td class="actions">
                    <a onclick='if(confirm("<?=__('Are you sure you want to delete the association between {0} and {1}?', $mentor->email, $skill->name)?>")){removeLink(<?=$mentor->id?>)}' class='unlink_link delete-link' hidden><?=__('Unlink')?></a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>

<script>
    function doneEditing(){
        if ($("#skill_form").data("changed")){
            var confirmed = true;
            <?php if($skill->mentor_count > 0) { ?>
                confirmed = confirm('<?= __('Are you sure you want to modify {0}? {1} items are associated with it.', $skill->name, $skill->mentor_count) ?>');
            <?php } ?>
            if(confirmed){
                $('#skill_form').submit();
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

    function removeLink(mentor_id){
        $.ajax({
            method: 'post',
            url : "/skills/unlink.json?skill=<?=$skill->id?>&mentor=" + mentor_id,
            headers: { 'X-CSRF-TOKEN': '<?=$this->getRequest()->getParam('_csrfToken');?>' },
            success: function( response ){
                $('#mentor_row_' + mentor_id).remove();
            },
            error: function(jqXHR, textStatus, errorThrown){
                alert("The association could not be deleted");
                console.log(jqXHR.responseText);
            }
        });
    }

    function setReadOnly(readOnly){
        $('#name').attr('readOnly', readOnly);
        $('#description').attr('readOnly', readOnly);
        
        if(readOnly){
            //View
            $('#doneButton').hide();
            $('.unlink_link').hide();

            $('#editButton').show();
        }else{
            //Edit
            $('#doneButton').show();
            $('.unlink_link').show();

            $('#editButton').hide();
        }
    }

    $('document').ready(function(){
        $("#skill_form :input").on('change paste keyup', (function() {
            $("#skill_form").data("changed",true);
            $('#cancelButton').show();
        }));
    });
</script>
