<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Product $product
 */
?>
<div class="products form large-12 medium-11 columns content">
    <?= $this->Form->create($product) ?>
    <fieldset>
        <legend><?= __('Add Product') ?></legend>
        <?php
            echo $this->Form->control('name');
            echo $this->Form->control('platform');
            echo $this->Form->control('description', ['type' => 'textarea']);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Save')) ?>
    <?= $this->Form->end() ?>
</div>
