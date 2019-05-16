<?php
$this->loadHelper('Calendar.Calendar');
foreach ($appointments as $appointment) {
    $content = $this->Html->link($appointment->title, ['action' => 'view', $appointment->id]);
    $this->Calendar->addRow($appointment->start_time, $content, ['class' => 'event']);
}
?>
    <h1>Month View For <?= $firstParty->first_name . ' ' . $firstParty->last_name?></h1>
<div class="content">
<?= $this->Calendar->render(); ?>
<?php 
if (!$this->Calendar->isCurrentMonth()) {
echo $this->Html->link(__('Back to current month') . '...', ['action' => 'month', '?' => ['uid' => $firstParty->user_id]]);
}
?>
</div>
<script>
// This Kludge allows us to insert the user id on prev/next link
var userId = <?= $firstParty->user_id ?>;
$("th.cell-prev a").attr("href", $("th.cell-prev a").attr("href") + "?uid=" + userId);
$("th.cell-next a").attr("href", $("th.cell-next a").attr("href") + "?uid=" + userId);
var year = <?= $_calendar['year'] ?>;
var month = <?= $_calendar['month'] ?>;
$(".cell-number").each(function () {
    $(this).click(function () {
        window.location = "/appointments/day/" + userId + "/" + year + "/" + month + "/" + $(this).html();
    });
});
</script>
