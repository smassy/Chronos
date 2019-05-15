<?php $this->Html->css("scheduler.css", ["block" => true]); ?>
<div id="sched-container">
<table id="scheduler">
<caption><?= $day->format('l, F j, Y') ?></caption>
<thead>
<tr>
<th>Time</th>
<th class="first-party-hdr"><?= $firstParty->first_name . ' ' . $firstParty->last_name ?></th>
<th class="second-party-hdr">Please add party...</th>
</tr>
</thead>
<tbody id="<?= $availability[0]["slot_time"]->format('Y-m-d') ?>">
<?php foreach ($availability as $slot): ?>
<tr>
<td id="slot-<?= $slot['slot_time']->format('Hi') ?>"><?= $slot['slot_time']->format('H:i') ?></td>
<?php if (!$slot['booked']): ?>
<td class="first-party free"></td>
<?php elseif (isset($slot['slots']) && !(isset($editMode) && $slot["slot_time"] == $appointment->start_time)): ?>
<td rowspan="<?= $slot['slots'] ?>" class="first-party booked"><?= $slot['title'] ?></td>
<?php endif; ?>
<td class="second-party"></td>
</tr>
<?php endforeach; ?>
</tbody>
</table>

</div>
