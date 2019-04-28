<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Appointment $appointment
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Appointment'), ['action' => 'edit', $appointment->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Appointment'), ['action' => 'delete', $appointment->id], ['confirm' => __('Are you sure you want to delete # {0}?', $appointment->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Appointments'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Appointment'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Ext Appointments'), ['controller' => 'ExtAppointments', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Ext Appointment'), ['controller' => 'ExtAppointments', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Int Appointments'), ['controller' => 'IntAppointments', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Int Appointment'), ['controller' => 'IntAppointments', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="appointments view large-9 medium-8 columns content">
    <h3><?= h($appointment->title) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('User') ?></th>
            <td><?= $appointment->has('user') ? $this->Html->link($appointment->user->id, ['controller' => 'Users', 'action' => 'view', $appointment->user->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Title') ?></th>
            <td><?= h($appointment->title) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($appointment->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Start Time') ?></th>
            <td><?= h($appointment->start_time) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('End Time') ?></th>
            <td><?= h($appointment->end_time) ?></td>
        </tr>
    </table>
    <div class="row">
        <h4><?= __('Details') ?></h4>
        <?= $this->Text->autoParagraph(h($appointment->details)); ?>
    </div>
    <div class="related">
        <h4><?= __('Related Ext Appointments') ?></h4>
        <?php if (!empty($appointment->ext_appointments)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Appointment Id') ?></th>
                <th scope="col"><?= __('Party') ?></th>
                <th scope="col"><?= __('Info') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($appointment->ext_appointments as $extAppointments): ?>
            <tr>
                <td><?= h($extAppointments->id) ?></td>
                <td><?= h($extAppointments->appointment_id) ?></td>
                <td><?= h($extAppointments->party) ?></td>
                <td><?= h($extAppointments->info) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'ExtAppointments', 'action' => 'view', $extAppointments->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'ExtAppointments', 'action' => 'edit', $extAppointments->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'ExtAppointments', 'action' => 'delete', $extAppointments->id], ['confirm' => __('Are you sure you want to delete # {0}?', $extAppointments->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
    <div class="related">
        <h4><?= __('Related Int Appointments') ?></h4>
        <?php if (!empty($appointment->int_appointments)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Appointment Id') ?></th>
                <th scope="col"><?= __('User Id') ?></th>
                <th scope="col"><?= __('Confirmed') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($appointment->int_appointments as $intAppointments): ?>
            <tr>
                <td><?= h($intAppointments->id) ?></td>
                <td><?= h($intAppointments->appointment_id) ?></td>
                <td><?= h($intAppointments->user_id) ?></td>
                <td><?= h($intAppointments->confirmed) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'IntAppointments', 'action' => 'view', $intAppointments->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'IntAppointments', 'action' => 'edit', $intAppointments->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'IntAppointments', 'action' => 'delete', $intAppointments->id], ['confirm' => __('Are you sure you want to delete # {0}?', $intAppointments->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>
