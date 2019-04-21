<?php
namespace App\Controller;
use App\Controller\AppController;
use Cake\Event\Event;

/**
 * This is the UserDetails controller.
 * It primarily serves as a slim controller to perform user searches.
*/
class UserDetailsController extends AppController {

    public function initialize() {
        parent::initialize();
        $this->loadComponent('Csrf');
    }

    public function isAuthorize() {
        // All logged in users can search
        return true;
    }

    public function beforeFilter(Event $event) {
        parent::beforeFilter($event);
        if (in_array($this->request->param('action'), ['search'])) {
                $this->getEventManager()->off($this->Csrf);
        }
    }

    public function search() {
        $fields = ['last_name', 'first_name', 'title', 'department', 'extension'];
        if ($this->request->is('post')) {
            $keywords = explode(' ', $this->request->getData('searchFor'));
            $query = $this->UserDetails->find()
                ->contain(['Departments', 'Users'])
                ->where([['Users.role_id >' => INACTIVE], ['Users.role_id <' => SUPERADMIN]]);
            foreach ($keywords as $keyword) {
                $keyword = trim($keyword);
                $likeKeyword = '%' . $keyword . '%';
                $conditions = ["OR" => []];
                foreach ($fields as $field) {
                    if ($this->request->getData($field)) {
                        if ($field == 'department') {
                            $conditions['OR']['Departments.name LIKE'] = $likeKeyword;
                        } else if ($field == 'extension') {
                            if (is_numeric($keyword)) {
                                $conditions['OR'][$field] = intval($keyword);
                            }
                        } else {
                            $conditions['OR'][$field . ' LIKE'] = $likeKeyword;
                        }
                    }
                }
                if (!empty($conditions)) {
                    $query->where(array($conditions));
                }
            }
            if ($this->request->is('ajax')) {
                $this->set([
                    'response' => $query,
                    '_serialize' => 'response'
                ]);
                $this->RequestHandler->renderAs($this, 'json');
                return;
            }
        }
        $this->paginate = [
            'contain' => ['Departments'],
            'sortWhitelist' => ['last_name', 'first_name', 'Departments.name', 'title', 'extension', 'office'],
            'order' => ['last_name' => 'asc']
        ];
        if (isset($query)) {
            $this->set('results', $this->paginate($query));
        }
    }

    public function directory() {
        $this->paginate = [
            'contain' => ['Departments'],
            'sortWhitelist' => ['last_name', 'first_name', 'Departments.name', 'title', 'extension', 'office'],
            'order' => ['last_name' => 'asc']
        ];
        $results = $this->UserDetails->find()
            ->contain(['Users', 'Departments'])
            ->where([['Users.role_id >' => INACTIVE], ['Users.role_id <' => SUPERADMIN]]);
        $results = $this->paginate($results);
        $this->set(compact('results'));
    }
        
}


?>
