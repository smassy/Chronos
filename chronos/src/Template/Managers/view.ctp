<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Manager $manager
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Manager'), ['action' => 'edit', $manager->user_id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Manager'), ['action' => 'delete', $manager->user_id], ['confirm' => __('Are you sure you want to delete # {0}?', $manager->user_id)]) ?> </li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Managers'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Manager'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Departments'), ['controller' => 'Departments', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Department'), ['controller' => 'Departments', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="managers view large-9 medium-8 columns content">
    <h3><?= h($manager->user_id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Department') ?></th>
            <td><?= $manager->has('department') ? $this->Html->link($manager->department->name, ['controller' => 'Departments', 'action' => 'view', $manager->department->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('User Id') ?></th>
            <td><?= $this->Number->format($manager->user_id) ?></td>
        </tr>
    </table>
</div>
