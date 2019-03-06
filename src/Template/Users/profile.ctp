<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
?>

<div class="users view large-12 medium-11 columns content">
    <h3><?= h($user->email) ?></h3>

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
