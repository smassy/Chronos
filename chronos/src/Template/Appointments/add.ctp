<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Appointment $appointment
 */
$this->Html->css("scheduler.css", ["block" => true]);
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Appointments'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Ext Appointments'), ['controller' => 'ExtAppointments', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Ext Appointment'), ['controller' => 'ExtAppointments', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Int Appointments'), ['controller' => 'IntAppointments', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Int Appointment'), ['controller' => 'IntAppointments', 'action' => 'add']) ?></li>
    </ul>
</nav>
<?= $this->element("live-user-search") ?>
<div>
<h1>Experimental Time Slot Selection</h1>
<table id="scheduler">
<caption></caption>
<thead>
<tr>
<th>Time</th>
<th>1st party</th>
<th>2nd party</th>
</tr>
</thead>
<tbody id="<?= $availability[0]["slot_time"]->format('Y-m-d') ?>">
<?php foreach ($availability as $slot): ?>
<tr>
<td id="slot-<?= $slot['slot_time']->format('Hi') ?>"><?= $slot['slot_time']->format('H:i') ?></td>
<?php if (!$slot['booked']): ?>
<td class="first-party free"></td>
<?php elseif (isset($slot['slots'])): ?>
<td rowspan="<?= $slot['slots'] ?>" class="first-party booked"><?= $slot['title'] ?></td>
<?php endif; ?>
<td></td>
</tr>
<?php endforeach; ?>
</tbody>
</table>

</div>
<div class="appointments form large-9 medium-8 columns content">
    <?= $this->Form->create($appointment) ?>
    <fieldset>
        <legend><?= __('Add Appointment') ?></legend>
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
<?= $this->Html->script('user-search.js') ?>
<?= $this->Html->script('scheduler.js') ?>
