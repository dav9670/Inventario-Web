<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Product $product
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Product'), ['action' => 'edit', $product->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Product'), ['action' => 'delete', $product->id], ['confirm' => __('Are you sure you want to delete # {0}?', $product->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Products'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Product'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Licences'), ['controller' => 'Licences', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Licence'), ['controller' => 'Licences', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="products view large-9 medium-8 columns content">
    <h3><?= h($product->name) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Name') ?></th>
            <td><?= h($product->name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Platform') ?></th>
            <td><?= h($product->platform) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Description') ?></th>
            <td><?= h($product->description) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($product->id) ?></td>
        </tr>
    </table>
    <div class="related">
        <h4><?= __('Related Licences') ?></h4>
        <?php if (!empty($product->licences)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Name') ?></th>
                <th scope="col"><?= __('Key Text') ?></th>
                <th scope="col"><?= __('Description') ?></th>
                <th scope="col"><?= __('Image') ?></th>
                <th scope="col"><?= __('Start Time') ?></th>
                <th scope="col"><?= __('End Time') ?></th>
                <th scope="col"><?= __('Created') ?></th>
                <th scope="col"><?= __('Modified') ?></th>
                <th scope="col"><?= __('Deleted') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($product->licences as $licences): ?>
            <tr>
                <td><?= h($licences->id) ?></td>
                <td><?= h($licences->name) ?></td>
                <td><?= h($licences->key_text) ?></td>
                <td><?= h($licences->description) ?></td>
                <td><?= h($licences->image) ?></td>
                <td><?= h($licences->start_time) ?></td>
                <td><?= h($licences->end_time) ?></td>
                <td><?= h($licences->created) ?></td>
                <td><?= h($licences->modified) ?></td>
                <td><?= h($licences->deleted) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Licences', 'action' => 'view', $licences->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Licences', 'action' => 'edit', $licences->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Licences', 'action' => 'delete', $licences->id], ['confirm' => __('Are you sure you want to delete # {0}?', $licences->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>
