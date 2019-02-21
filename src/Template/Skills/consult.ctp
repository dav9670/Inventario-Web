<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Skill $skill
 */
?>
<div class="skills form large-12 medium-11 columns content">
    <?= $this->Form->create($skill, ['id' => 'skill_form']) ?>
    <button type="button" class="right" id="viewButton" style="width: 75px; text-align: center; padding: 10px;" onClick='setReadOnly(true)' hidden="hidden"><?=__('View')?></button>
    <button type="button" class="right" id="editButton" style="width: 75px; text-align: center; padding: 10px;" onClick='setReadOnly(false)'><?=__('Edit')?></button> 
    <fieldset>
        <legend><?= __('Skill') ?></legend>
        <?php
            echo $this->Form->control('name', ['readOnly' => 'readOnly']);
            echo $this->Form->control('description', ['readOnly' => 'readOnly']);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit'), ['id' => 'submit', 'hidden']) ?>
    <?= $this->Form->end() ?>
    <?= $this->Form->postLink(__('Delete skill'), ['controller' => 'Skills', 'action' => 'delete', $skill->id], ['confirm' => __('Are you sure you want to delete {0}?', $skill->name)]);?>
    
    
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
            <tr class="clickable-row">
                <td><a href='/mentors/<?= h($mentor->id) ?>'><img src="data:image/png;base64, <?= h($mentor->image) ?>" alt="<?= h($mentor->first_name) ?> <?= h($mentor->last_name) ?>" width=100/></a></td>
                <td><a href='/mentors/<?= h($mentor->id) ?>'><?= h($mentor->email) ?></a></td>
                <td><a href='/mentors/<?= h($mentor->id) ?>'><?= h($mentor->first_name) ?></a></td>
                <td><a href='/mentors/<?= h($mentor->id) ?>'><?= h($mentor->last_name) ?></a></td>
                <td><a href='/mentors/<?= h($mentor->id) ?>'><?= h($mentor->description) ?></a></td>

                <?php if (count($mentor->skills_list) > 3): ?>
                    <td><a href='/mentors/<?= h($mentor->id) ?>'><?= h(implode(", ", array_slice($mentor->skills_list,0,3)) . "...") ?></a></td>
                <?php else: ?>
                    <td><a href='/mentors/<?= h($mentor->id) ?>'><?= h(implode(", ", array_slice($mentor->skills_list,0,3))) ?></a></td>
                <?php endif; ?>

                <?php if ($mentor->available): ?>
                    <td><a href='/mentors/<?= h($mentor->id) ?>'><img src='/img/good.png' alt='Available' width=20 height=20></a></td>
                <?php else: ?>
                    <td><a href='/mentors/<?= h($mentor->id) ?>'><img src='/img/bad.png' alt='Not Available' width=20 height=20></a></td>
                <?php endif; ?>

                <td class="actions">
                    <?= $this->Form->postLink(__('Unlink'), ['controller' => 'skills', 'action' => 'unlink', '?' => ['skill' => $skill->id, 'mentor' => $mentor->id]], ['confirm' => __('Are you sure you want to delete the association between {0} and {1}?', $mentor->first_name . " " . $mentor->last_name, $skill->name), 'class' => 'unlink_link', 'hidden']) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>

<script>
    $("#skill_form :input").change(function() {
        $("#skill_form").data("changed",true);
    });

    function setReadOnly(readOnly){
        if(readOnly){
            //View
            if ($("#skill_form").data("changed")) {
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
