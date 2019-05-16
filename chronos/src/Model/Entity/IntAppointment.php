<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * IntAppointment Entity
 *
 * @property int $id
 * @property int $appointment_id
 * @property int $user_id
 * @property bool $confirmed
 *
 * @property \App\Model\Entity\Appointment $appointment
 * @property \App\Model\Entity\User $user
 */
class IntAppointment extends Entity
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
        'appointment_id' => true,
        'user_id' => true,
        'confirmed' => true,
        'appointment' => true,
        'user' => true
    ];
}
