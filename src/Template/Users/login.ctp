<div class="users form content centered twothirds-width">
    <img class='centered-image third-width' src='/img/logo-rounded-square.png'/>
    <?= $this->Form->create() ?>
    <fieldset>
        <legend><?= __('Login') ?></legend>
        <?= $this->Form->control('email') ?>
        <?= $this->Form->control('password') ?>
    </fieldset>
    <?= $this->Form->button(__('Login'), ['class' => 'centered-button']) ?>
    <?= $this->Form->end() ?>
</div>