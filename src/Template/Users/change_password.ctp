<div class="users form large-12 medium-11 columns content">
    <h1><?= __('Change your password.') ?></h1>
    <?= $this->Form->create() ?>
    <?= $this->Form->control('Old password',['type' => 'password']) ?>
    <?= $this->Form->control('New password',['type' => 'password']) ?>
    <?= $this->Form->control('Confirm your new password',['type' => 'password']) ?>
    <?= $this->Form->button(__('Change it!')) ?>
    <?= $this->Form->end() ?>
</div>