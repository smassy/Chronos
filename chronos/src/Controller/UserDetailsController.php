<?php
namespace App\Controller;
use App\Controller\AppController;

/**
 * This is the UserDetails controller.
 * It primarily serves as a slim controller to perform user searches.
*/
class UserDetailsController extends AppController {

    public function initialize() {
        parent::initialize();
    }

    public function isAuthorize() {
        // All logged in users can search
        return true;
    }

    public function search() {
        $showResults = false;
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
        }
        ob_start();
        var_dump(array($conditions));
        $this->log(ob_get_clean(), 'debug');
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
