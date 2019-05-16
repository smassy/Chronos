<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Appointment $appointment
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Back To Month'), ['action' => 'month', $day->format('Y'), $day->format('m'), '?' => ['uid' => $firstParty->user_id]]) ?> </li>
<?php
$now = new \DateTime();
$now->setTime(0,0);
if ($day >= $now):
?>
        <li><?= $this->Html->link(__('New Appointment'), ['action' => 'add', $firstParty->user_id, $day->format('Y'), $day->format('m'), $day->format('d')]) ?> </li>
<?php endif; ?>
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
