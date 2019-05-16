<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Appointment $appointment
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Appointments'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Appointment'), ['action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="appointments view large-9 medium-8 columns content">
    <h3>Day View</h3>
<?= $this->element('scheduler') ?>
</div>
<?= $this->Html->script('scheduler.js') ?>
<script>
var editMode = false;
$("document").ready( function () {
    $(".second-party-hdr, .second-party").hide();
    $("td").off();
});
</script>
