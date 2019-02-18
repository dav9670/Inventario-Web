<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Skill $skill
 */
?>
<!--<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><#?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $skill->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $skill->id)]
            )
        ?></li>
        <li><#?= $this->Html->link(__('List Skills'), ['action' => 'index']) ?></li>
        <li><#?= $this->Html->link(__('List Mentors'), ['controller' => 'Mentors', 'action' => 'index']) ?></li>
        <li><#?= $this->Html->link(__('New Mentor'), ['controller' => 'Mentors', 'action' => 'add']) ?></li>
    </ul>
</nav>-->
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
</div>

<script>
    function setReadOnly(readOnly){
        $('#name').attr('readOnly', readOnly);
        $('#description').attr('readOnly', readOnly);
        if(readOnly){
            $('#viewButton').hide();
            $('#editButton').show();
            $('#submit').hide();
        }
        else{
            $('#editButton').hide();
            $('#viewButton').show();
            $('#submit').show();
        }
    }
</script>
