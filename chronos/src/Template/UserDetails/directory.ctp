<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li><?= $this->Html->link("Search", ['controller' => 'UserDetails', 'action' => 'search']) ?></li>
    </ul>
</div>
<div class="results large-9 medium-8 columns content">
<h3>User Directory</h3>
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
                            <td class="actions"></td>
                        </td>
                        <?php endforeach; ?>
                    </tbody>
    </table>
</div>
