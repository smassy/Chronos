<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
?>
<div class="users form large-9 medium-8 columns content">
    <?= $this->Form->create($user) ?>
    <fieldset>
        <legend><?= __('Change Your Password') ?></legend>
        <?php
        echo $this->Form->input("newPassword", ['type' => 'password', 'id' => 'password', 'required' => true, 'minlength' => 8]);
        echo $this->Form->input("confirmPassword", ['type' => 'password', 'id' => 'pwConfirm', 'required' => true]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
<?= $this->Html->script("passwords") ?>
