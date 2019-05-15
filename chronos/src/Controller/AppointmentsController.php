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
            'contain' => ['Users', 'ExtAppointments', 'IntAppointments']
        ]);

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
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $appointment = $this->Appointments->patchEntity($appointment, $this->request->getData());
            if ($this->Appointments->save($appointment)) {
                $this->Flash->success(__('The appointment has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The appointment could not be saved. Please, try again.'));
        }
        $users = $this->Appointments->Users->find('list', ['limit' => 200]);
        $this->set(compact('appointment', 'users'));
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
        $appointment = $this->Appointments->get($id);
        if ($this->Appointments->delete($appointment)) {
            $this->Flash->success(__('The appointment has been deleted.'));
        } else {
            $this->Flash->error(__('The appointment could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function month($year = null, $month = null) {
        $this->Calendar->init($year, $month);
        $appointments = $this->Appointments->find('calendar', ['year' => $this->Calendar->year(), 'month' => $this->Calendar->month()]);
        $this->set(compact('appointments'));
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
