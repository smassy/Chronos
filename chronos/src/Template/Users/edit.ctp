<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Users'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Secretarial Relationships'), ['controller' => 'SecretarialRelationships', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Secretarial Relationship'), ['controller' => 'SecretarialRelationships', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="users form large-9 medium-8 columns content">
    <?= $this->Form->create($user) ?>
    <fieldset>
        <legend><?= __('Edit User') ?></legend>
        <?php
            echo $this->Form->control('username');
            echo $this->Form->control('role_id', ['options' => $roles]);
            echo $this->Form->control('user_detail.department_id', ['options' => $departments]);
            echo $this->Form->control('newPassword', ['type' => 'password', 'id' => 'password', 'minlength' => 8]);
            echo $this->Form->control('confirmPassword', ['type' => 'password', 'id' => 'pwConfirm']);
            echo $this->Form->control('user_detail.first_name');
            echo $this->Form->control('user_detail.middle_name');
            echo $this->Form->control('user_detail.last_name');
            echo $this->Form->control('user_detail.title');
            echo $this->Form->control('user_detail.email');
            echo $this->Form->control('user_detail.office');
            echo $this->Form->control('user_detail.extension'); 
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
<?= $this->Html->script("passwords") ?>
