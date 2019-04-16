<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * UserDetail Entity
 *
 * @property int $id
 * @property int $user_id
 * @property int $department_id
 * @property string $email
 * @property string $last_name
 * @property string $first_name
 * @property string|null $middle_name
 * @property string $title
 * @property string|null $office
 * @property int|null $extension
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\Department $department
 */
class UserDetail extends Entity
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
