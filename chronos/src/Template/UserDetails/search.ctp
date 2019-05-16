<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li><?= $this->Html->link("User Directory", ['controller' => 'UserDetails', 'action' => 'directory']) ?></li>
    </ul>
</div>
<h2>Advanced Search</h2>
<div class="search form large-9 medium-8 columns content">
    <?= $this->Form->create() ?>
    <?= $this->Form->control('searchFor'); ?>
    <?= $this->Form->button(__('Search')) ?>
    <fieldset>
        <legend>Search On</legend>
        <?php
echo $this->Form->control('last_name', ['type' => 'checkbox', 'checked' => true]);
echo $this->Form->control('first_name', ['type' => 'checkbox', 'checked' => true]);
echo $this->Form->control('department', ['type' => 'checkbox']);
echo $this->Form->control('title', ['type' => 'checkbox']);
echo $this->Form->control('extension', ['type' => 'checkbox']);
        ?>
    </fieldset>
<?= $this->Form->end() ?>
</div>
<?php if (isset($results)): ?>
<div class="results large-9 medium-8 columns content">
<h3>Your search yielded <?= $results->count() ?> matches</h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('last_name', 'Last') ?></th>
                <th scope="col"><?= $this->Paginator->sort('first_name', 'First') ?></th>
                <th scope="col"><?= $this->Paginator->sort('Departments.name', 'Dept') ?></th>
                <th scope="col"><?= $this->Paginator->sort('title') ?></th>
                <th scope="col"><?= $this->Paginator->sort('extension', 'Ext') ?></th>
                <th scope="col"><?= $this->Paginator->sort('office') ?></th>
                <th scope="col" class="actions">Actions</th>
            </tr>
        </thead>
                    <tbody>
                        <?php foreach ($results as $result): ?>
                        <tr>
                            <td><?= $result->last_name ?></td>
                            <td><?= $result->first_name ?></td>
                            <td><?= $result->department->name ?></td>
                            <td><?= $result->title ?></td>
                            <td><?= $result->extension ?></td>
                            <td><?= $result->office ?></td>
                            <td class="actions"><?= $this->Html->link('Appointments', ['controller' => 'Appointments', 'action' => 'month', '?' => ['uid' => $result->user_id]])?></td>
                        </td>
                        <?php endforeach; ?>
                    </tbody>
    </table>
</div>
<?php endif; ?>
