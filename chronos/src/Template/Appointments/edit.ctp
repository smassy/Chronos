<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Appointment $appointment
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $appointment->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $appointment->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Appointments'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Ext Appointments'), ['controller' => 'ExtAppointments', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Ext Appointment'), ['controller' => 'ExtAppointments', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Int Appointments'), ['controller' => 'IntAppointments', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Int Appointment'), ['controller' => 'IntAppointments', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="appointments form large-9 medium-8 columns content">
    <?= $this->Form->create($appointment) ?>
    <fieldset>
        <legend><?= __('Edit Appointment') ?></legend>
        <?php
            echo $this->Form->control('user_id', ['options' => $users]);
            echo $this->Form->control('start_time');
            echo $this->Form->control('end_time');
            echo $this->Form->control('title');
            echo $this->Form->control('details');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
