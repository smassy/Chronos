<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Manager[]|\Cake\Collection\CollectionInterface $managers
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Manager'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Departments'), ['controller' => 'Departments', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Department'), ['controller' => 'Departments', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="managers index large-9 medium-8 columns content">
    <h3><?= __('Managers') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('user_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('department_id') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($managers as $manager): ?>
            <tr>
                <td><?= $this->Number->format($manager->user_id) ?></td>
                <td><?= $manager->has('department') ? $this->Html->link($manager->department->name, ['controller' => 'Departments', 'action' => 'view', $manager->department->id]) : '' ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $manager->user_id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $manager->user_id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $manager->user_id], ['confirm' => __('Are you sure you want to delete # {0}?', $manager->user_id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(['format' => __('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')]) ?></p>
    </div>
</div>
