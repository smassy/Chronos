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
        <li><?= $this->Html->link(__('List Departments'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Department'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Secretarial Relationships'), ['controller' => 'SecretarialRelationships', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Secretarial Relationship'), ['controller' => 'SecretarialRelationships', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="users form large-9 medium-8 columns content">
    <?= $this->Form->create($user) ?>
    <fieldset>
        <legend><?= __('Add User') ?></legend>
        <?php
            echo $this->Form->control('username', ['minlength' => 3]);
            echo $this->Form->control('role_id', ['options' => $roles]);
            echo $this->Form->control('user_detail.department_id', ['options' => $departments]);
            echo $this->Form->control('password', ['required' => true, 'minlength' => 8]);
            echo $this->Form->control("confirmPassword", ['type' => 'password', 'id' => 'pwConfirm', 'required' => true]);
            echo $this->Form->control('user_detail.first_name', ['required' => true, 'minlength' => 2, 'maxlength' => 60]);
            echo $this->Form->control('user_detail.middle_name', ['maxlength' => 60]);
            echo $this->Form->control('user_detail.last_name', ['required' => true, 'minlength' => 2, 'maxlength' => 60]);
            echo $this->Form->control('user_detail.title', ['required' => true, 'minlength' => 3, 'maxlength' => 100]);
            echo $this->Form->control('user_detail.email', ['type' => 'email', 'required' => true, 'maxlength' => 60]);
            echo $this->Form->control('user_detail.office', ['type' => 'text', 'maxlength' => 10,]);
            echo $this->Form->control('user_detail.extension', ['min' => 0, 'max' => 65535]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
<?= $this->Html->script("passwords") ?>
