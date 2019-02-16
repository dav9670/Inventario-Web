<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Licence $licence
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Licences'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Products'), ['controller' => 'Products', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Product'), ['controller' => 'Products', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="licences form large-9 medium-8 columns content">
    <?= $this->Form->create($licence) ?>
    <fieldset>
        <legend><?= __('Add Licence') ?></legend>
        <?php
            echo $this->Form->control('name');
            echo $this->Form->control('key_text');
            echo $this->Form->control('description');
            echo $this->Form->control('image');
            echo $this->Form->control('start_time');
            echo $this->Form->control('end_time', ['empty' => true]);
            echo $this->Form->control('deleted', ['empty' => true]);
            echo $this->Form->control('products._ids', ['options' => $products]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
