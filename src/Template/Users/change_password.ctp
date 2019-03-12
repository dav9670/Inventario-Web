<div class="users form content centered twothirds-width">

    <?= $this->Form->create() ?>
    <fieldset>
        <legend><?= __('Change your password') ?></legend>
        <?= $this->Form->control('Old password',['type' => 'password']) ?>
        <?= $this->Form->control('New password',['type' => 'password']) ?>
        <?= $this->Form->control('Confirm your new password',['type' => 'password']) ?>
    </fieldset>
    <?= $this->Form->button(__('Change')) ?>

    <?= $this->Form->end() ?>
</div>