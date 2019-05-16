<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\SecretarialRelationship[]|\Cake\Collection\CollectionInterface $secretarialRelationships
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Secretarial Relationship'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="secretarialRelationships index large-9 medium-8 columns content">
    <h3><?= __('Secretarial Relationships') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('secretary_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('user_id') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($secretarialRelationships as $secretarialRelationship): ?>
            <tr>
                <td><?= $this->Number->format($secretarialRelationship->id) ?></td>
                <td><?= $this->Number->format($secretarialRelationship->secretary_id) ?></td>
                <td><?= $secretarialRelationship->has('user') ? $this->Html->link($secretarialRelationship->user->id, ['controller' => 'Users', 'action' => 'view', $secretarialRelationship->user->id]) : '' ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $secretarialRelationship->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $secretarialRelationship->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $secretarialRelationship->id], ['confirm' => __('Are you sure you want to delete # {0}?', $secretarialRelationship->id)]) ?>
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
