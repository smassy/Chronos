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
        <li><?= $this->Html->link(__('List Users'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New User'), ['action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="users view large-9 medium-8 columns content">
    <h3><?= h($user->username) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Username') ?></th>
            <td><?= h($user->username) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Role') ?></th>
            <td><?= $user->role->role ?></td>
        </tr>
        <tr>
            <th scope="row">Department</th>
            <td><?= $user->user_detail->department->name ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($user->id) ?></td>
        </tr>
        <tr>
            <th scope="row">Full Name</th>
            <td><?= $user->user_detail->first_name . ' ' . $user->user_detail->middle_name . ' ' . $user->user_detail->last_name ?></td>
        </tr>
        <tr>
            <th scope="row">Title</th>
            <td><?= $user->user_detail->title ?></td>
        </tr>
        <tr>
            <th scope="row">E-mail</th>
            <td><?= $user->user_detail->email ?></td>
        </tr>
        <tr>
            <th scope="row">Office</th>
            <td><?= $user->user_detail->office ?></td>
        </tr>
        <tr>
            <th scope="row">Extension</th>
            <td><?= $user->user_detail->extension ?></td>
        </tr>

    </table>
    <div class="related">
        <h4><?= __('Related Tokens') ?></h4>
        <?php if (!empty($user->tokens)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('User Id') ?></th>
                <th scope="col"><?= __('Token') ?></th>
                <th scope="col"><?= __('Expiry') ?></th>
            </tr>
            <?php foreach ($user->tokens as $tokens): ?>
            <tr>
                <td><?= h($tokens->user_id) ?></td>
                <td><?= h($tokens->token) ?></td>
                <td><?= h($tokens->expiry) ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>
