<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * User Entity
 *
 * @property int $id
 * @property string $username
 * @property string $password
 * @property int $role_id
 *
 * @property \App\Model\Entity\Role $role
 * @property \App\Model\Entity\Appointment[] $appointments
 * @property \App\Model\Entity\IntAppointment[] $int_appointments
 * @property \App\Model\Entity\Manager[] $managers
 * @property \App\Model\Entity\MessageInstance[] $message_instances
 * @property \App\Model\Entity\Message[] $messages
 * @property \App\Model\Entity\Note[] $notes
 * @property \App\Model\Entity\SecretarialRelationship[] $secretarial_relationships
 * @property \App\Model\Entity\Token[] $tokens
 * @property \App\Model\Entity\UserDetail[] $user_details
 */
class User extends Entity
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
        'user_details' => true
    ];

    protected function _setPassword($password)
    {
        return (new DefaultPasswordHasher)->hash($password);
    }

    /**
     * Fields that are excluded from JSON versions of the entity.
     *
     * @var array
     */
    protected $_hidden = [
        'password'
    ];
}
