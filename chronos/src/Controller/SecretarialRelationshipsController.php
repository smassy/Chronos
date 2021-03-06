<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * SecretarialRelationships Controller
 *
 * @property \App\Model\Table\SecretarialRelationshipsTable $SecretarialRelationships
 *
 * @method \App\Model\Entity\SecretarialRelationship[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class SecretarialRelationshipsController extends AppController
{
    public function isAuthorized($user) {
        // Special cases
        if (in_array($this->request->param('action'), ['changepassword', 'logout'])) {
            return true;
        }
        // Only admins can access this controller save for above actions.
        if ($user["role_id"] >= ADMIN) {
            return true;
        }
        return false;
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
        $secretarialRelationships = $this->paginate($this->SecretarialRelationships);

        $this->set(compact('secretarialRelationships'));
    }

    /**
     * View method
     *
     * @param string|null $id Secretarial Relationship id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $secretarialRelationship = $this->SecretarialRelationships->get($id, [
            'contain' => ['Users']
        ]);

        $this->set('secretarialRelationship', $secretarialRelationship);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $secretarialRelationship = $this->SecretarialRelationships->newEntity();
        if ($this->request->is('post')) {
            $secretarialRelationship = $this->SecretarialRelationships->patchEntity($secretarialRelationship, $this->request->getData());
            if ($this->SecretarialRelationships->save($secretarialRelationship)) {
                $this->Flash->success(__('The secretarial relationship has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The secretarial relationship could not be saved. Please, try again.'));
        }


        $users = $this->SecretarialRelationships->Users->find('list', array('conditions' => array('SecretarialRelationships.role_id' == '20') ) )->where();

        $secretaryid = $this->SecretarialRelationships->Users->find('list' , array('conditions' => array('role_id' => 40) ) );

        $this->set(compact('secretarialRelationship', 'users', 'secretaryid'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Secretarial Relationship id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $secretarialRelationship = $this->SecretarialRelationships->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $secretarialRelationship = $this->SecretarialRelationships->patchEntity($secretarialRelationship, $this->request->getData());
            if ($this->SecretarialRelationships->save($secretarialRelationship)) {
                $this->Flash->success(__('The secretarial relationship has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The secretarial relationship could not be saved. Please, try again.'));
        }
        $users = $this->SecretarialRelationships->Users->find('list', ['limit' => 200]);
        $this->set(compact('secretarialRelationship', 'users'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Secretarial Relationship id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $secretarialRelationship = $this->SecretarialRelationships->get($id);
        if ($this->SecretarialRelationships->delete($secretarialRelationship)) {
            $this->Flash->success(__('The secretarial relationship has been deleted.'));
        } else {
            $this->Flash->error(__('The secretarial relationship could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
