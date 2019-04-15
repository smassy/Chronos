<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 *
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsersController extends AppController
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
            'contain' => ['Roles']
        ];
        $users = $this->paginate($this->Users);

        $this->set(compact('users'));
    }

    /**
     * View method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => ['Roles', 'Appointments', 'IntAppointments', 'Managers', 'MessageInstances', 'Messages', 'Notes', 'SecretarialRelationships', 'Tokens', 'UserDetails']
        ]);

        $this->set('user', $user);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $user = $this->Users->newEntity();
        if ($this->request->is('post')) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $roles = $this->Users->Roles->find('list', ["keyField" => "id", "valueField" => "role"])->order(["id" => "ASC"])->toArray();
        $this->set(compact('user', 'roles'));
    }

    /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $roles = $this->Users->Roles->find('list', ["keyField" => "id", "valueField" => "role"])->order(["id" => "ASC"])->toArray();
        $this->set(compact('user', 'roles'));
    }

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->Flash->error(__('This system does not support user deletion. Set user to inactive instead.'));
        return $this->redirect(['action' => 'index']);
    }

    // Login Controller
    public function login()
    {
        if($this->request->is('post'))
        {
            $user = $this->Auth->identify();
            if($user)
            {
                $this->Auth->setUser($user);
                return $this->redirect(['controller' => 'users']);
            }
            //Bad Login
            $this->Flash->error('Incorrect Login');
        
        }
    }

    // Log Out
    public function logout() {
        return $this->redirect($this->Auth->logout());
    }

    /*
     * Controller action to handle password change for a user (self-serve).
     * Gets its info from the logged in user.
     */
    public function changepassword() {
        $user = $this->Users->get($this->Auth->User('id'));
        if ($this->request->is(['post', 'put'])) {
            if ($user->checkPassword($this->request->getData('oldPassword'))) {
                $user->password = $this->request->getData('newPassword');
                if ($this->Users->save($user)) {
                    $this->Flash->success("Password updated successfully.");
                    return $this->redirect(HOME);
                } else {
                    $this->Flash->error("Failed to update password.");
                }
            } else {
                $this->Flash->error("The password supplied does not match your current password.");
            }
        }
        $this->set(compact('user'));
    }
}
