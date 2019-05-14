<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * IntAppointments Model
 *
 * @property \App\Model\Table\AppointmentsTable|\Cake\ORM\Association\BelongsTo $Appointments
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $Users
 *
 * @method \App\Model\Entity\IntAppointment get($primaryKey, $options = [])
 * @method \App\Model\Entity\IntAppointment newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\IntAppointment[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\IntAppointment|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\IntAppointment saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\IntAppointment patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\IntAppointment[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\IntAppointment findOrCreate($search, callable $callback = null, $options = [])
 */
class IntAppointmentsTable extends Table
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

        $this->setTable('int_appointments');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Appointments', [
            'foreignKey' => 'appointment_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER'
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
            ->boolean('confirmed')
            ->requirePresence('confirmed', 'create')
            ->allowEmptyString('confirmed', false);

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
        $rules->add($rules->existsIn(['appointment_id'], 'Appointments'));
        $rules->add($rules->existsIn(['user_id'], 'Users'));

        return $rules;
    }
}
