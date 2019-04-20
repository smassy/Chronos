<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\I18n\Time;
use Cake\Routing\Router;
use Cake\Mailer\Email;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 *
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsersController extends AppController
{

    public function initialize() {
        parent::initialize();
        $this->Auth->allow(['pwresetreq', 'pwreset']);
    }

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
            'contain' => ['Roles', 'Appointments', 'IntAppointments', 'MessageInstances', 'Messages', 'Notes', 'SecretarialRelationships', 'Tokens', 'UserDetails' => ['Departments']]
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
            if ($user->role_id > $this->Auth->user('role_id')) {
                $this->Flash->error('Privilege escalation attempt detected. This will be logged.');
                $this->log('Privilege escalation attempt detected. Offender: ' . $this->Auth->user('username'), 'warning');
                return $this->redirect(['controller' => 'Users', 'action' => 'index']);
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
        if ($this->Auth->user('role_id') < $user->role_id) {
            $this->Flash->error('You may not edit a user with higher privilege than yours.');
            return $this->redirect(['controller' => 'Users', 'action' => 'index']);
        }
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($user->role_id > $this->Auth->user('role_id')) {
                $this->Flash->error('Privilege escalation attempt detected. This will be logged.');
                $this->log('Privilege escalation attempt detected. Offender: ' . $this->Auth->user('username'), 'warning');
                return $this->redirect(['controller' => 'Users', 'action' => 'index']);
            }
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
            if($user && $user->role_id > INACTIVE)
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

    private function cleanExpiredTokens() {
        $now = Time::now();
        $expiredTokens = $this->Tokens->find()
            ->where(['expiry <' => $now]);
        if (!empty($expiredTokens)) {
            foreach ($expiredTokens as $expiredToken) {
                $this->Tokens->delete($expiredToken);
            }
        }
    }

    public function pwresetreq() {
        $this->loadModel('Tokens');
        $this->cleanExpiredTokens();
        // No reason to be here if already logged in
        if ($this->Auth->user('id') != null) {
            $this->Flash->set('You are already logged in. No need for password reset.');
            return $this->redirect(HOME);
        }
        // User posts a reset request 
        if ($this->request->is('post')) {
            $foundUser = $this->Users->find('all', ['contain' => 'UserDetails'])
                ->where(['username' => $this->request->getData('username')])->first();
            if (!empty($foundUser)) {
                if ($foundUser->role_id >= ADMIN) {
                    $this->Flash->error("System administrators may not use this service for security reasons. Please contact a colleague for manual reset.");
                    return $this->redirect(['controller' => 'Users', 'action' => 'login']);
                }
                if (!empty($foundUser->tokens)) {
                    $this->Flash->error('Sorry, a link has already been sent to you in the last 24 hours. Please check your SPAM folder to make sure it did not get miscategorised. If you did not get a link, please contact administration.');
                    return $this->redirect(['controller' => 'Users', 'action' => 'login']);
                }
                $newToken = $this->Tokens->newEntity();
                $newToken->user_id = $foundUser->id;
                $newToken->token = bin2hex(random_bytes(64));
                $newToken->expiry = Time::now()->addDays(1);
                if ($this->Tokens->save($newToken)) {
                    $resetLink = Router::url(['controller' => 'Users', 'action' => 'pwreset', $newToken->token], true);
                    // Send E-mail
                    $message = new email();
                                        $message->from(['noreply@localhost' => 'Chronos System'])
                        ->to($foundUser->user_detail->email)
                        ->subject('Resetting Your Password')
                        ->send("Hello,\nPlease use the following link in order to reset your password:\n" . $resetLink . "\nYou have 24 hours to use this link after which it will become invalid and you will need to request a new one.\nSincerely,\nThe Chronos Team");
                    $this->log("Issued reset link for $foundUser->username: " . $resetLink, 'debug');
                    return $this->redirect(['controller' => 'Pages', 'action' => 'pwresetsent']);
                } else {
                    $this->Flash->error("An error occurred. Please contact administration.");
                }
            }
        }
        // Get an empty user model to pass to the form
        $this->set('user', $this->Users->newEntity());
    }

    public function pwreset($token = null) {
        $this->loadModel('Tokens');
        $this->cleanExpiredTokens();
        if (!$token) {
            return $this->redirect(['controller' => 'Users', 'action' => 'pwresetreq']);
        }
        $foundToken = $this->Tokens->find('all', ['contain' => 'Users'])
            ->where(['token' => $token])->first();
        if (!$foundToken) {
            $this->Flash->error('Your token was invalid or may have expired; please make a request or contact an administrator.');
            return $this->redirect(['controller' => 'Users', 'action' => 'pwresetreq']);
        }
        if ($this->request->is('post')) {
            $foundToken->user->password = $this->request->getData('newPassword');
            if ($this->Users->save($foundToken->user)) {
                $this->Flash->success('Password successfully updated. Now log in.');
                $this->Tokens->delete($foundToken);
                return $this->redirect(['controller' => 'Users', 'action' => 'login']);
            } else {
                $this->Flash->error('The password could not be updated.');
            }
        }
        $user = $this->Users->newEntity();
        $this->set(compact('user'));
    }
}
