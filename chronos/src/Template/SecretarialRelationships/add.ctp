<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\SecretarialRelationship $secretarialRelationship
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Secretarial Relationships'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="secretarialRelationships form large-9 medium-8 columns content">
    <?= $this->Form->create($secretarialRelationship) ?>
    <fieldset>
        <legend><?= __('Add Secretarial Relationship') ?></legend>
        <?php
            echo $this->Form->control('secretary_id');
            echo $this->Form->control('user_id', ['options' => $users]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
