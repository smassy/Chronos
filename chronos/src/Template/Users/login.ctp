<br>
<div class="index large-4 medium-4 large-offset-4 medium-offset-4 column">
    <div class="panel">
        <h2 class="text-center">Login</h2>
        <?= $this->Form->create(); ?>
            <?= $this->Form->input('username'); ?>
            <?= $this->Form->input('password', array('type' => 'password')); ?>
            <?= $this->Form->submit('Login', array('class' => 'button')); ?>
        <?= $this->Form->end(); ?>
    </div>
        <div id="pwReset"><?= $this->Html->link('Forgot your password?', ['controller' => 'Users', 'action' => 'pwresetreq']) ?></div>
</div>
