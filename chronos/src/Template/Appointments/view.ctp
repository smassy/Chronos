<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Appointment $appointment
 */
$day = $appointment->start_time;
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
<?php if ($day > new \DateTime()): ?>
        <li><?= $this->Html->link(__('Edit Appointment'), ['action' => 'edit', $appointment->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Appointment'), ['action' => 'delete', $appointment->id], ['confirm' => __('Are you sure you want to delete # {0}?', $appointment->id)]) ?> </li>
<?php endif; ?>
        <li><?= $this->Html->link(__('Back To Month'), ['action' => 'month', $day->format('Y'), $day->format('m'), '?' => ['uid' => $appointment->user_id]]) ?> </li>
        <li><?= $this->Html->link('Back To Day', ['action' => 'day', $appointment->user_id, $day->format('Y'), $day->format('m'), $day->format('d')]) ?> </li>
    </ul>
</nav>
<div class="appointments view large-9 medium-8 columns content">
    <h3><?= h($appointment->title) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('First Party') ?></th>
            <td><?= $appointment['user']['user_detail']['first_name'] . ' ' . $appointment['user']['user_detail']['last_name'] ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Second Party') ?></th>
            <td>
<?php if ($appointment['type'] === 'int'): ?>
<?= $appointment['int_appointments'][0]['user']['user_detail']['first_name'] . ' ' . $appointment['int_appointments'][0]['user']['user_detail']['last_name'] . ' (internal)' ?>
<?php else: ?>
<?= $appointment['ext_appointment']['party'] . ' (external)' ?>
<?php endif; ?>
</td>
        </tr>
<?php if ($appointment['type'] === 'int'): ?>
        <tr>
            <th scope="row"><?= __('Status') ?></th>
            <td><?= ($appointment['int_appointments'][0]['confirmed']) ? "CONFIRMED" : "UNCONFIRMED" ?></td>
        </tr>
<?php endif; ?>
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
<?php if ($appointment['type'] === 'ext'): ?>
    <div class="row">
        <h4><?= __('Supplementary Info') ?></h4>
        <?= $this->Text->autoParagraph(h($appointment['ext_appointment']['info'])); ?>
    </div>
<?php endif; ?>
</div>
