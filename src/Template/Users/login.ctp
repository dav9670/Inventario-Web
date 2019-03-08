<<<<<<< HEAD
<div class="users form content centered twothirds-width">
    <img class='centered-image third-width' src='/img/logo-rounded-square.png'/>
    <?= $this->Form->create() ?>
    <fieldset>
        <legend><?= __('Login') ?></legend>
        <?= $this->Form->control('email') ?>
        <?= $this->Form->control('password') ?>
    </fieldset>
    <?= $this->Form->button(__('Login'), ['class' => 'centered-button']) ?>
=======
<div class="users form large-12 medium-11 columns content">
    <h1><?= __('Login') ?></h1>
    <?= $this->Form->create() ?>
    <?= $this->Form->control('email') ?>
    <?= $this->Form->control('password') ?>
    <?= $this->Form->button(__('Login')) ?>
>>>>>>> origin/flavien
    <?= $this->Form->end() ?>
</div>