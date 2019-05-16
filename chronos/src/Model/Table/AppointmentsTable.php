<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use \DateTime;
use \DateInterval;

/**
 * Appointments Model
 *
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\ExtAppointmentsTable|\Cake\ORM\Association\HasMany $ExtAppointments
 * @property \App\Model\Table\IntAppointmentsTable|\Cake\ORM\Association\HasMany $IntAppointments
 *
 * @method \App\Model\Entity\Appointment get($primaryKey, $options = [])
 * @method \App\Model\Entity\Appointment newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Appointment[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Appointment|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Appointment saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Appointment patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Appointment[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Appointment findOrCreate($search, callable $callback = null, $options = [])
 */
class AppointmentsTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('appointments');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER'
        ]);
        $this->hasOne('ExtAppointments', [
            'foreignKey' => 'appointment_id'
        ]);
        $this->hasMany('IntAppointments', [
            'foreignKey' => 'appointment_id'
        ]);
        $this->addBehavior('Calendar.Calendar', [
            'field' => 'start_time',
            'enfField' => 'end_time',
            'scope' => ['invisible' => false]
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->nonNegativeInteger('id')
            ->allowEmptyString('id', 'create');

        $validator
            ->dateTime('start_time')
            ->requirePresence('start_time', 'create')
            ->allowEmptyDateTime('start_time', false);

        $validator
            ->dateTime('end_time')
            ->requirePresence('end_time', 'create')
            ->allowEmptyDateTime('end_time', false);

        $validator
            ->scalar('title')
            ->maxLength('title', 127)
            ->requirePresence('title', 'create')
            ->allowEmptyString('title', false);

        $validator
            ->scalar('details')
            ->allowEmptyString('details');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['user_id'], 'Users'));

        return $rules;
    }

    // These are used to control start and end of day
    const DAY_START_HR = 7;
    const DAY_START_MN = 0;
    const DAY_END_HR = 19;
    const DAY_END_MN = 0;
    const SLOT_LENGTH = 'PT30M';

    /**
     * Returns an array of time slots of length SLOT_LENGTH from DAY_START to DAY_END
     * Every slot is an element in the array with its start time length and a valued booked which is true is slot is taken for specified user on that day.
     * @param int $user The user ID of the person whose availability is being queried.
     * @param DateTime $day The day to check availability for.
     */
    public function getAvailability($user, $day = null) {
        if (!$day) {
            $day = new DateTime();
        }
        $timeSlots = [];
        $day->setTime(self::DAY_START_HR, self::DAY_START_MN);
        $dayEnd = clone $day;
        $dayEnd->setTime(self::DAY_END_HR, self::DAY_END_MN);
        $timeCursor = clone $day;
        $step = new DateInterval('PT30M');
        $aptStart = null;
        $aptEnd = null;
        $slots = 0;
        while ($timeCursor < $dayEnd) {
            $slotInfo = ['slot_time' => clone $timeCursor];
            if (!$aptStart) {
                $apt = $this->find()
                    ->where(['start_time' => $timeCursor, 'user_id' => $user])
                    ->first();
                if ($apt) {
                    if ((clone $apt->start_time)->add($step) < $apt->end_time) {
                        $aptStart = $apt->start_time;
                        $aptEnd = $apt->end_time;
                        $slots = 1;
                    } else {
                        $slotInfo['slots'] = 1;
                    }
                    $slotInfo['booked'] = true;
                    $slotInfo['start'] = $apt->start_time;
                    $slotInfo['end'] = $apt->end_time;
                    $slotInfo['title'] = $apt->title;
                    $slotInfo['aptId'] = $apt->id;
                } else {
                    $slotInfo['booked'] = false;
                }
            } else {
                $slotInfo['booked'] = true;
                $slotInfo['start'] = $apt->start_time;
                $slotInfo['end'] = $apt->end_time;
                $slotInfo['title'] = $apt->title;
                $slots++;
                if ((clone $timeCursor)->add($step) >= $aptEnd) {
                    $timeSlots[count($timeSlots) + 1 - $slots]['slots'] = $slots;
                    $slots = 0;
                    $aptStart = null;
                    $aptEnd = null;
                }
            }
            $timeSlots[] = $slotInfo;
            $timeCursor->add($step);
        }
        return $timeSlots;
    }



                    
}





