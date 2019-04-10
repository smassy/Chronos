<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Managers Controller
 *
 * @property \App\Model\Table\ManagersTable $Managers
 *
 * @method \App\Model\Entity\Manager[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ManagersController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Departments']
        ];
        $managers = $this->paginate($this->Managers);

        $this->set(compact('managers'));
    }

    /**
     * View method
     *
     * @param string|null $id Manager id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $manager = $this->Managers->get($id, [
            'contain' => ['Departments']
        ]);

        $this->set('manager', $manager);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $manager = $this->Managers->newEntity();
        if ($this->request->is('post')) {
            $manager = $this->Managers->patchEntity($manager, $this->request->getData());
            if ($this->Managers->save($manager)) {
                $this->Flash->success(__('The manager has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The manager could not be saved. Please, try again.'));
        }
        $departments = $this->Managers->Departments->find('list', ['limit' => 200]);

        // Test until user_details has information!!!
        $first_name = $this->Users->user_details->find('list', ["keyField" => "id", "valueField" => "first_name"])->order(["id" => "ASC"])->toArray();

        $last_name = $this->Users->user_details->find('list', ["keyField" => "id", "valueField" => "last_name"])->order(["id" => "ASC"])->toArray();

        $full_name = $first_name + " " + $last_name;

        $this->set(compact('manager', 'departments', 'full_name'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Manager id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $manager = $this->Managers->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $manager = $this->Managers->patchEntity($manager, $this->request->getData());
            if ($this->Managers->save($manager)) {
                $this->Flash->success(__('The manager has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The manager could not be saved. Please, try again.'));
        }
        $departments = $this->Managers->Departments->find('list', ['limit' => 200]);
        $this->set(compact('manager', 'departments'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Manager id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $manager = $this->Managers->get($id);
        if ($this->Managers->delete($manager)) {
            $this->Flash->success(__('The manager has been deleted.'));
        } else {
            $this->Flash->error(__('The manager could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
