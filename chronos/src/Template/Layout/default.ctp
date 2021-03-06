<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

$session = $this->getRequest()->getSession();
$cakeDescription = 'Chronos System';
?>
<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= $cakeDescription ?>:
        <?= $this->fetch('title') ?>
    </title>
    <?= $this->Html->meta('icon') ?>

    <?= $this->Html->css('base.css') ?>
    <?= $this->Html->css('style.css') ?>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</head>
<body>

<?php if ($session->read("Auth.User.username")): ?>

    <nav class="top-bar expanded" data-topbar role="navigation">
        <ul class="title-area large-3 medium-4 columns">
            <li class="name">
                <h1>
                    <a href=""><?= $this->fetch('title') ?></a>
                </h1>
            </li>
        </ul>
        <div class="top-bar-section">
            <ul class="right">
                <li>
                    <a href="https://www.vaniercollege.qc.ca/">Vanier College</a>
                </li>
                <li>
                    <a href="https://api.cakephp.org/">CakePHP</a>
                </li>
                <li><?= $this->Html->link('Your Month', ['controller' => 'Appointments', 'action' => 'month', '?' => ['uid', $session->read('Auth.User.id')]]) ?> </li>
                <li><?= $this->Html->link('User Directory', ['controller' => 'UserDetails', 'action' => 'directory'])?></li>
<?php if ($session->read('Auth.User.role_id') >= 200): ?>
                <li><?= $this->Html->link('Manage Users', ['controller' => 'Users', 'action' => 'index'])?></li>
<?php endif; ?>
                <li> 
                    <?= $this->Html->link("Logout", ["controller" => "Users", "action" => "logout"]) ?> 
                </li>
            </ul>
        </div>
    </nav>

<?php endif; ?>

    <?= $this->Flash->render() ?>

    <div class="container clearfix">
        <?= $this->fetch('content') ?>
    </div>

    <footer>
        <?php if ($session->read("Auth.User.username")): ?>
        <p> Logged in as <?= $session->read("Auth.User.username") ?>.</p>
    </footer>

        <?php if ($session->read("Auth.User.username") && $this->request->getParam('action') != 'search'): ?>
            <div class="search-bar top-bar-section">
                <?php
                    echo $this->Form->create(null, ['method' => 'post', 'url' => $this->Url->build(['controller' => 'UserDetails', 'action' => 'search'], true)]);
                    echo $this->Form->control('searchFor', ['label' => 'Search Bar:']);
                    echo $this->Form->submit('Go');
                    echo $this->Form->hidden('first_name', ['value' => 1]);
                    echo $this->Form->hidden('last_name', ['value' => 1]);
                    echo $this->Form->end();
                ?> 
            </div>
        <?php endif; ?>
        
<?php endif; ?>

</body>
</html>
