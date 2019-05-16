<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use \DateTime;

/**
 * Appointments Controller
 *
 * @property \App\Model\Table\AppointmentsTable $Appointments
 *
 * @method \App\Model\Entity\Appointment[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class AppointmentsController extends AppController
{

    public function initialize() {
        parent::initialize();
        $this->loadComponent('Csrf');
        $this->loadComponent('Calendar.Calendar');
    }

    public function beforeFilter(Event $event) {
        parent::beforeFilter($event);
        if (in_array($this->request->param('action'), ['availability'])) {
            $this->getEventManager()->off($this->Csrf);
        }
    }

    public function isAuthorized($user) {
        return true;
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Users']
        ];
        $appointments = $this->paginate($this->Appointments);

        $this->set(compact('appointments'));
    }

    /**
     * View method
     *
     * @param string|null $id Appointment id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $appointment = $this->Appointments->get($id, [
            'contain' => ['Users' => ['UserDetails'], 'ExtAppointments', 'IntAppointments' => ['Users' => ['UserDetails' => ['Departments']]]]
        ]);
        if ($appointment['int_appointments']) {
            $appointment['type'] = 'int';
        } else {
            $appointment['type'] = 'ext';
    }
        $this->set('appointment', $appointment);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add($uid = null, $year = null, $month = null, $day = null)
    {
        if (($year && $month && $day)) {
            $day = new \DateTime($year . '-' . $month . '-' . $day);
            $today = new \DateTime();
            $today->setTime(0,0);
            if ($day < $today) {
                $this->Flash->error("You can't create an appointment in the past!");
                $this->redirect(HOME);
            }
        } else {
            $day = new \DateTime();
        }
        $this->loadModel("UserDetails");
        $firstParty = $this->UserDetails->find()->where(['user_id' => $uid])->first();
        $availability = $this->Appointments->getAvailability($uid, $day);
        $appointment = $this->Appointments->newEntity();
        if ($this->request->is('post')) {
                        $appointment = $this->Appointments->patchEntity($appointment, $this->request->getData());
            $appointment->start_time = new \DateTime($day->format('Y-m-d') . ' ' . $this->request->getData("start_time"));
            $appointment->end_time = new \DateTime($day->format('Y-m-d') . ' ' . $this->request->getData("end_time"));
            $appointment->user_id = $uid;
            if ($this->request->getData('aptType') === 'int') {
                $int_appointment = $this->Appointments->IntAppointments->newEntity();
                $int_appointment->user_id = $this->request->getData('int_party');
                $this->log('user id' . $this->request->getData('int_party'), 'debug');
                $int_appointment->confirmed = false;
                $appointment['int_appointments'] = array($int_appointment);
            } elseif ($this->request->getData('aptType') === 'ext') {
                $extApt = $this->Appointments->ExtAppointments->newEntity();
                $extApt->party = $this->request->getData('party');
                $extApt->info = $this->request->getData('info');
                $appointment['ext_appointment'] = $extApt;
            } else {
                $this->Flash->error("Unknown appointment type.");
            }
            if ($this->Appointments->save($appointment)) {
                $this->Flash->success(__('The appointment has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The appointment could not be saved. Please, try again.'));
        }
        $users = $this->Appointments->Users->find('list', ['limit' => 200]);
        $this->set(compact('appointment', 'users', 'availability', 'day', 'firstParty'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Appointment id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $appointment = $this->Appointments->get($id, [
            'contain' => ['IntAppointments' => ['Users' => ['UserDetails']], 'ExtAppointments']
        ]);
        if ($appointment->start_time < new \DateTime()) {
            $this->Flash->error("This appointment is in thje past, it may no longer be modified.");
            $this->redirect($this->referer());
        }
        $day = $appointment->start_time;
        if ($this->request->is(['patch', 'post', 'put'])) {
            $appointment = $this->Appointments->patchEntity($appointment, $this->request->getData());
            $appointment->start_time = new \DateTime($day->format('Y-m-d') . ' ' . $this->request->getData('start_time'));
            $appointment->end_time = new \DateTime($day->format('Y-m-d') . ' ' . $this->request->getData('end_time'));
            if ($appointment['int_appointments']) {
                $this->log('cnf ' . $appointment['int_appointments'][0]['confirmed'], 'debug');
                $appointment['int_appointments'][0]['confirmed'] = 0;
                $this->log('cnf ' . $appointment['int_appointments'][0]['confirmed'], 'debug');

            }
            if ($this->Appointments->save($appointment) && $this->Appointments->IntAppointments->save($appointment['int_appointments'][0])) {
                $this->Flash->success(__('The appointment has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The appointment could not be saved. Please, try again.'));
        }
        if ($appointment['int_appointments']) {
            $appointment['type'] = 'int';
        } else {
            $appointment['type'] = 'ext';
    }
        $availability = $this->Appointments->getAvailability($appointment->user_id, new \DateTime($appointment->start_time->format('Y-m-d')));
        $this->loadModel("UserDetails");
        $firstParty = $this->UserDetails->find()->where(['user_id' => $appointment->user_id])->first();
        $editMode = true;
        $this->set(compact('appointment', 'availability', 'firstParty', 'day', 'editMode'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Appointment id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $appointment = $this->Appointments->get($id, ['contain' => ['IntAppointments', 'ExtAppointments']]);
        if ($appointment['int_appointments']) {
            foreach ($appointment['int_appointments'] as $iapt) {
                $this->Appointments->IntAppointments->delete($iapt);
            }
        } else {
            $this->Appointments->ExtAppointments->delete($appointment['ext_appointment']);
        }
        if ($this->Appointments->delete($appointment)) {
            $this->Flash->success(__('The appointment has been deleted.'));
        } else {
            $this->Flash->error(__('The appointment could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function month($year = null, $month = null) {
        $uid = $this->request->getQuery('uid');
        if (!$uid) {
            $uid = $this->Auth->user('id');
        }
        $this->Calendar->init($year, $month);
        $appointments = $this->Appointments->find('calendar', ['year' => $this->Calendar->year(), 'month' => $this->Calendar->month()])->where(['user_id' => $uid]);
        $this->loadModel('UserDetails');
        $firstParty = $this->UserDetails->find()->where(['user_id' => $uid])->first();
        $this->set(compact('appointments', 'firstParty'));
    }

    public function day($uid, $year = null, $month = null, $day = null) {
        if ($year && $month && $day) {
            $day = new \DateTime($year . '-' . $month . '-' . $day);
        } else {
            $day = new \DateTime();
        }
        $availability = $this->Appointments->getAvailability($uid, $day);
        $this->loadModel('UserDetails');
        $firstParty = $this->UserDetails->find()->where(['user_id' => $uid])->first();
        $this->set(compact('availability', 'firstParty', 'day'));
    }


    public function confirm($id) {
        $appointment = $this->Appointments->get($id, ['contain' => ['IntAppointments']]);
        if ($appointment['int_appointments']) {
            $appointment['int_appointments'][0]['confirmed'] = 1;
            if ($this->Appointments->IntAppointments->save($appointment['int_appointments'][0])) {
                $this->Flash->success("Appointment successfully confirmed");
            } else {
                $this->Flash->error("Appointment could not be confirmed.");
            }
        }
        $this->redirect($this->referer());
    }

    public function availability() {
        $this->request->allowMethod('ajax');
        if ($this->request->is('ajax')) {
            if (preg_match('/\d\d\d\d-\d\d-\d\d/', $this->request->getData('day')) && is_numeric($this->request->getData('user_id'))) {
                $day = new DateTime($this->request->getData('day'));
                $response = $this->Appointments->getAvailability($this->request->getData('user_id'), $day);
            } else {
                $response = "Invalid request";
            }
            $this->set([
                'response' => $response,
                '_serialize' => 'response'
            ]);
            $this->RequestHandler->renderAs($this, 'json');
        }
            return;
    }


}
