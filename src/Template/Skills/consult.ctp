<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Skill $skill
 */
?>
<div class="skills form large-12 medium-11 columns content"> 
    <?= $this->Form->create($skill) ?>
    <fieldset>
        <legend><?= __('Skill') ?></legend>
        <?php
            echo $this->Form->control('name', ['readOnly' => 'readOnly']);
            echo $this->Form->control('description', ['readOnly' => 'readOnly']);

        ?>
    </fieldset>
    <button type="button" id="viewButton" onClick='setReadOnly(true)' hidden="hidden"><?=__('View')?></button>
    <button type="button" id="editButton" onClick='setReadOnly(false)'><?=__('Edit')?></button>
    <?= $this->Form->button(__('Submit'), ['id' => 'submit', 'hidden']) ?>
    <?= $this->Form->end() ?>

    <div class="related">
        <h4><?= __('Related Mentors') ?></h4>
        <?php if (!empty($skill->mentors)): ?>
        <table id="related" cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Email') ?></th>
                <th scope="col"><?= __('First Name') ?></th>
                <th scope="col"><?= __('Last Name') ?></th>
                <th scope="col"><?= __('Description') ?></th>
                <th scope="col"><?= __('Image') ?></th>
                <th scope="col"><?= __('Created') ?></th>
                <th scope="col"><?= __('Modified') ?></th>
                <th scope="col"><?= __('Deleted') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($skill->mentors as $mentor): ?>
            <tr>
                <td><?= $this->Html->link(h($mentor->email), ['controller' => 'mentors', 'action' => 'consult', $mentor->id]) ?></td>
                <td><?= h($mentor->first_name) ?></td>
                <td><?= h($mentor->last_name) ?></td>
                <td><?= h($mentor->description) ?></td>
                <td><img src="data:image/png;base64, <?= h($mentor->image) ?>" alt="<?=__("Mentor's image")?>"/></td>
                <td><?= h($mentor->created) ?></td>
                <td><?= h($mentor->modified) ?></td>
                <td><?= h($mentor->deleted) ?></td>
                <td class="actions">
                    <?= $this->Form->postLink(__('Unlink'), ['controller' => 'skills', 'action' => 'unlink', '?' => ['skill' => $skill->id, 'mentor' => $mentor->id]], ['confirm' => __('Are you sure you want to delete the association between {0} and {1}?', $mentor->email, $skill->name), 'class' => 'unlink_link', 'hidden']) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>

<script>
    function setReadOnly(readOnly){
        $('#name').attr('readOnly', readOnly);
        $('#description').attr('readOnly', readOnly);
        if(readOnly){//View
            $('#viewButton').hide();
            $('#submit').hide();
            $('#related a[class="unlink_link"').hide();

            $('#editButton').show();
        }
        else{//Edit
            $('#editButton').hide();

            $('#viewButton').show();
            $('#submit').show();
            $('#related a[class="unlink_link"').show();
        }
    }
</script>
