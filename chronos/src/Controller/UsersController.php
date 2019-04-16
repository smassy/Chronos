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
            'contain' => ['Roles', 'Appointments', 'IntAppointments', 'MessageInstances', 'Messages', 'Notes', 'SecretarialRelationships', 'Tokens', 'UserDetails']
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
        $userDetails = $this->Users->UserDetails->newEntity();
        $user['user_detail'] = $userDetails;
        if ($this->request->is('post')) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $errorMsg = "User could not be saved.";
            if ($user->errors()) {
                $errorList = [];
                foreach ($user->errors() as $errors) {
                    if (is_array($errors)) {
                        foreach ($errors as $error) {
                            $errorList[] = is_array($error) ? implode('', $error) : $error;
                        }
                    } else {
                        $errorList[] = $errors;
                    }
                }

                if (!empty($errorList)) {
                    $errorMsg .= ":<br />".implode("<br />", $errorList);
                }
            }

            $this->Flash->error($errorMsg, ['escape' => false]);
        }
        $roles = $this->Users->Roles->find('list', ["keyField" => "id", "valueField" => "role"])->order(["id" => "ASC"])->where(['id <=' => $this->Auth->User('role_id')])->toArray();
        $this->loadModel('Departments');
        $departments = $this->Departments->find('list', ['keyField' => 'id', 'valueField' => 'name'])->toArray();
        $this->set(compact('user', 'roles', 'departments'));
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
            'contain' => ['UserDetails']
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if (!empty($this->request->getData('newPassword'))) {
                $user->password = $this->request->getData('newPassword');
            }
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $errorMsg = "User could not be saved.";
            if ($user->errors()) {
                $errorList = [];
                foreach ($user->errors() as $errors) {
                    if (is_array($errors)) {
                        foreach ($errors as $error) {
                            $errorList[] = is_array($error) ? implode('', $error) : $error;
                        }
                    } else {
                        $errorList[] = $errors;
                    }
                }

                if (!empty($errorList)) {
                    $errorMsg .= ":<br />".implode("<br />", $errorList);
                }
            }

            $this->Flash->error($errorMsg, ['escape' => false]);
        }
        $roles = $this->Users->Roles->find('list', ["keyField" => "id", "valueField" => "role"])->order(["id" => "ASC"])->where(['id <=' => $this->Auth->User('role_id')])->toArray();
        $this->loadModel('Departments');
        $departments = $this->Departments->find('list', ['keyField' => 'id', 'valueField' => 'name'])->toArray();
        $this->set(compact('user', 'roles', 'departments'));
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
