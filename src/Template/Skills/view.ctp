<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Skill $skill
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Skill'), ['action' => 'edit', $skill->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Skill'), ['action' => 'delete', $skill->id], ['confirm' => __('Are you sure you want to delete # {0}?', $skill->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Skills'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Skill'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Mentors'), ['controller' => 'Mentors', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Mentor'), ['controller' => 'Mentors', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="skills view large-9 medium-8 columns content">
    <h3><?= h($skill->name) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Name') ?></th>
            <td><?= h($skill->name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Description') ?></th>
            <td><?= h($skill->description) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($skill->id) ?></td>
        </tr>
    </table>
    <div class="related">
        <h4><?= __('Related Mentors') ?></h4>
        <?php if (!empty($skill->mentors)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Email') ?></th>
                <th scope="col"><?= __('First Name') ?></th>
                <th scope="col"><?= __('Last Name') ?></th>
                <th scope="col"><?= __('Description') ?></th>
                <th scope="col"><?= __('Image') ?></th>
                <th scope="col"><?= __('Created') ?></th>
                <th scope="col"><?= __('Modified') ?></th>
                <th scope="col"><?= __('Deleted') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($skill->mentors as $mentors): ?>
            <tr>
                <td><?= h($mentors->id) ?></td>
                <td><?= h($mentors->email) ?></td>
                <td><?= h($mentors->first_name) ?></td>
                <td><?= h($mentors->last_name) ?></td>
                <td><?= h($mentors->description) ?></td>
                <td><img src="<?="data:image/jpeg;base64,".$mentors->image?>" \></td>
                <td><?= h($mentors->created) ?></td>
                <td><?= h($mentors->modified) ?></td>
                <td><?= h($mentors->deleted) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Mentors', 'action' => 'view', $mentors->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Mentors', 'action' => 'edit', $mentors->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Mentors', 'action' => 'delete', $mentors->id], ['confirm' => __('Are you sure you want to delete # {0}?', $mentors->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>
