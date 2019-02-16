<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit User'), ['action' => 'edit', $user->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete User'), ['action' => 'delete', $user->id], ['confirm' => __('Are you sure you want to delete # {0}?', $user->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Users'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New User'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Loans'), ['controller' => 'Loans', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Loan'), ['controller' => 'Loans', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="users view large-9 medium-8 columns content">
    <h3><?= h($user->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Email') ?></th>
            <td><?= h($user->email) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Password') ?></th>
            <td><?= h($user->password) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Admin Status') ?></th>
            <td><?= h($user->admin_status) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($user->id) ?></td>
        </tr>
    </table>
    <div class="row">
        <h4><?= __('Image') ?></h4>
        <?= $this->Text->autoParagraph(h($user->image)); ?>
    </div>
    <div class="related">
        <h4><?= __('Related Loans') ?></h4>
        <?php if (!empty($user->loans)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Start Time') ?></th>
                <th scope="col"><?= __('End Time') ?></th>
                <th scope="col"><?= __('Returned') ?></th>
                <th scope="col"><?= __('User Id') ?></th>
                <th scope="col"><?= __('Loan Type') ?></th>
                <th scope="col"><?= __('Item Id') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($user->loans as $loans): ?>
            <tr>
                <td><?= h($loans->id) ?></td>
                <td><?= h($loans->start_time) ?></td>
                <td><?= h($loans->end_time) ?></td>
                <td><?= h($loans->returned) ?></td>
                <td><?= h($loans->user_id) ?></td>
                <td><?= h($loans->loan_type) ?></td>
                <td><?= h($loans->item_id) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Loans', 'action' => 'view', $loans->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Loans', 'action' => 'edit', $loans->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Loans', 'action' => 'delete', $loans->id], ['confirm' => __('Are you sure you want to delete # {0}?', $loans->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>
