<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Appointment $appointment
 */
//$this->Html->css("scheduler.css", ["block" => true]);
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
<div id="user-time-select">
<?= $this->element("live-user-search") ?>
<?= $this->element("scheduler") ?>
</div>
<div class="appointments form large-9 medium-8 columns content">
    <?= $this->Form->create($appointment) ?>
    <fieldset>
        <legend><?= __('Add Appointment') ?></legend>
        <div class="form-group" id="int-appointment">
        <input name="int_appointment.user_id" type="hidden" id="int_party" />
        <?= $this->Form->control("party_name", ["id" => "party_name", "readonly", 'required']) ?>
        
        <?php
            echo $this->Form->control('start_time', ['id' => 'startTime', 'type' => 'text', 'readonly']);
            echo $this->Form->control('end_time', ['id' => 'endTime', 'type' => 'text', 'readonly']);
            echo $this->Form->control('title');
            echo $this->Form->control('details');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
<?= $this->Html->script('user-search.js') ?>
<?= $this->Html->script('scheduler.js') ?>
<script>
function addUserClickHandler() {
    $("#party_name").val($(this).html());
    var user_id = $(this).attr("id").toString().replace("user-", "");
    $("input#int_party").val(user_id);
    resetScheduler();
}
liveSearchClickHandler = addUserClickHandler;
</script>
