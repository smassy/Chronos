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
<?= $this->element("scheduler") ?>
<div class="appointments form large-9 medium-8 columns content">
    <?= $this->Form->create($appointment) ?>
    <fieldset>
        <legend><?= __('Edit ' . (($appointment->type === 'int') ? 'Internal' : 'External') . ' Appointment') ?></legend>
<?php if ($appointment->type === 'int'): ?>
<div class='form-group' id='int-appointment'>
<input name="int_party" type="hidden" id="int_party" value="<?= $appointment->int_appointments[0]['user_id']?>"/>
<?= $this->Form->control('party_name', ['id' => 'party_name', 'value' => $appointment->int_appointments[0]['user']['user_detail']['first_name'] . ' ' . $appointment->int_appointments[0]['user']['user_detail']['last_name'], 'readonly']) ?>
</div>
<?php else: ?>
<div class="form-group" id="ext-appointment">
<?= $this->Form->control("ext_appointment.party") ?>
<?= $this->Form->control("ext_appointment.info", ['type' => 'textarea', 'rows' => 3]) ?>
</div>
<?php endif; ?>
        <?php
            echo $this->Form->control('type', ['id' => 'aptType', 'type' => 'hidden']);
            echo $this->Form->control('start_time', ['id' => 'startTime', 'value' => $appointment->start_time->format("h:i"), 'type' => 'text', 'readonly']);
            echo $this->Form->control('end_time', ['id' => 'endTime', 'value' => $appointment->end_time->format("h:i"), 'type' => 'text', 'readonly']);
            echo $this->Form->control('title');
            echo $this->Form->control('details');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
<?= $this->Html->script('scheduler.js') ?>
<script>
'use strict';
var editMode = <?= (isset($editMode)) ? 'true' : 'false'?>;
function syncScheduler() {
    startTime = new Date(dayString + " " + $("input#startTime").val());
    startTimeSlot = $("td#slot-" + $("input#startTime").val().toString().replace(":", ""));
    endTime = new Date(dayString + " " + $("input#endTime").val());
    var lastSlot = getSlotStart(endTime);
    endTimeSlot = $("td#slot-" + getTimeString(lastSlot).replace(":", ""));
    refreshSelection();
    refreshSelection();
    if ($("input#aptType").val() === 'ext') {
        $(".second-party-hdr, .second-party").hide();
    } else {
        $(".second-party-hdr .second-party").show();
    }
}
$("document").ready( function () {
    syncScheduler();
});
</script>
