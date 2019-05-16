<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * SecretarialRelationship Entity
 *
 * @property int $id
 * @property int $secretary_id
 * @property int $user_id
 *
 * @property \App\Model\Entity\User $user
 */
class SecretarialRelationship extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'secretary_id' => true,
        'user_id' => true,
        'user' => true,
        'username' => true,
        'password' => true,
        'role_id' => true,
        'role' => true,
        'appointments' => true,
        'int_appointments' => true,
        'managers' => true,
        'message_instances' => true,
        'messages' => true,
        'notes' => true,
        'secretarial_relationships' => true,
        'tokens' => true,
        'user_details' => true,
        'department_id' => true,
        'email' => true,
        'last_name' => true,
        'first_name' => true,
        'middle_name' => true,
        'title' => true,
        'office' => true,
        'extension' => true,
        'user' => true,
        'department' => true
    ];
}
