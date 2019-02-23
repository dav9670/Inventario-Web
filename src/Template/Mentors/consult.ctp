<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Mentor $mentor
 */
?>
<div class="mentors form large-12 medium-11 columns content">
    <?= $this->Form->create($mentor) ?>
    <fieldset>
        <legend><?= __('Edit Mentor') ?></legend>
        <?php
            echo $this->Form->control('email');
            echo $this->Form->control('first_name');
            echo $this->Form->control('last_name');
            echo $this->Form->control('description');
            echo $this->Form->control('image');
            echo $this->Form->control('deleted', ['empty' => true]);
            echo $this->Form->control('skills._ids', ['options' => $skills]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
