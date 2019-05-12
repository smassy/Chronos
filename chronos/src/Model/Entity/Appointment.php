<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Appointment Entity
 *
 * @property int $id
 * @property int $user_id
 * @property \Cake\I18n\FrozenTime $start_time
 * @property \Cake\I18n\FrozenTime $end_time
 * @property string $title
 * @property string|null $details
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\ExtAppointment[] $ext_appointments
 * @property \App\Model\Entity\IntAppointment[] $int_appointments
 */
class Appointment extends Entity
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
        'user_id' => true,
        'start_time' => true,
        'end_time' => true,
        'title' => true,
        'details' => true,
        'user' => true,
        'ext_appointments' => true,
        'int_appointments' => true
    ];
}
