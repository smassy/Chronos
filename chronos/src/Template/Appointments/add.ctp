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
        <legend id="lgnd"><?= __('New internal Appointment') ?></legend>
<button click="switchApt" id="switchApt">Switch To External Appointment</button>
        <div class="form-group" id="int-appointment">
        <input name="int_party" type="hidden" id="int_party" />
        <?= $this->Form->control("party_name", ["id" => "party_name", "readonly", 'required']) ?>
       </div>
        <div class="form-group" id="ext-appointment">
        <?= $this->Form->control('party', ['required' => true, 'maxlength' => 127, 'minlength' => 5, 'label' => 'Party Name']) ?>
        <?= $this->Form->control('info', ['type' => 'textarea', 'rows' => 3, 'label' => 'Additional Info']) ?>
        </div> 
        <?php
            echo $this->Form->control('aptType', ['id' => 'aptType', 'type' => 'hidden', 'value' => 'int']);
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
'use strict';
var editMode = false;
function addUserClickHandler() {
    $("#party_name").val($(this).html());
    var user_id = $(this).attr("id").toString().replace("user-", "");
    $("input#int_party").val(user_id);
    resetScheduler();
}
liveSearchClickHandler = addUserClickHandler;

var extApt = false;
var aptHolder;

function toggleApt() {
    if (!extApt) { // int to ext apt
        $("button#switchApt").after(aptHolder);
        aptHolder = $("div#int-appointment").detach();
        $("legend#lgnd").html("New External Appointment");
        $("button#switchApt").html("Switch To Internal Appointment");
        $(".second-party-hdr, .second-party, div#live-user-search").hide();
        $("input#aptType").val("ext");
        extApt = true;
    } else { // Toggle from ext to int appointment
        $("button#switchApt").after(aptHolder);
        aptHolder = $("div#ext-appointment").detach();
        $("legend#lgnd").html("New Internal Appointment");
        $("button#switchApt").html("Switch To External Appointment");
        $(".second-party-hdr, .second-party, div#live-user-search").show();
        $("input#aptType").val("int");
        extApt = false;
    }
}

$("document").ready( function () {
    aptHolder = $("div#ext-appointment").detach();
    $("button#switchApt").click( function (event) {
        event.preventDefault();
        toggleApt();
    });
});

</script>
