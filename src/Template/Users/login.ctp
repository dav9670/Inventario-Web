<div class="users form large-12 medium-11 columns content">
    <h1><?= __('Login') ?></h1>
    <?= $this->Form->create() ?>
    <?= $this->Form->control('email') ?>
    <?= $this->Form->control('password') ?>
    <?= $this->Form->button(__('Login')) ?>
    <?= $this->Form->end() ?>
</div>