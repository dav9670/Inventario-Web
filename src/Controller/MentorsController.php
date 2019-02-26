<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\I18n\Time;
/**
 * Mentors Controller
 *
 * @property \App\Model\Table\MentorsTable $Mentors
 *
 * @method \App\Model\Entity\Mentor[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class MentorsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        ini_set('memory_limit', '-1');
        $mentors = $this->Mentors->find('all', [
            'contain' => ['Skills']
        ])->where('deleted is null')->order(['email' => 'asc']);
        $archivedMentors = $this->Mentors->find('all', [
            'contain' => ['Skills']
        ])->where('deleted is not null')->order(['email' => 'asc']);

        $this->set(compact('mentors', 'archivedMentors'));
        $this->set('_serialize', ['mentors', 'archivedMentors']);
    }

    /**
     * View method
     *
     * @param string|null $id Mentor id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $mentor = $this->Mentors->get($id, [
            'contain' => ['Skills']
        ]);

        $this->set('mentor', $mentor);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $mentor = $this->Mentors->newEntity();
        if ($this->request->is('post')) {

            $data = $this->request->getData();
            $image = $data['image'];
            if($image['tmp_name'] != '') {
                $imageData  = file_get_contents($image['tmp_name']);
                $b64   = base64_encode($imageData);
                $data['image'] = $b64;
            }
            $mentor = $this->Mentors->patchEntity($mentor, $data);
            
            
            if ($this->Mentors->save($mentor)) {
                $this->Flash->success(__('The mentor has been saved.'));
                
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The mentor could not be saved. Please, try again.'));
        }
        $this->set(compact('mentor'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Mentor id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $mentor = $this->Mentors->get($id, [
            'contain' => ['Skills']
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $mentor = $this->Mentors->patchEntity($mentor, $this->request->getData());
            if ($this->Mentors->save($mentor)) {
                $this->Flash->success(__('The mentor has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The mentor could not be saved. Please, try again.'));
        }
        $skills = $this->Mentors->Skills->find('list', ['limit' => 200]);
        $this->set(compact('mentor', 'skills'));
    }

    public function consult($id = null)
    {
        $mentor = $this->Mentors->get($id, [
            'contain' => ['Skills']
        ]);
        if($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            $image = $data['image'];
            if($image['tmp_name'] != '') {
                $imageData  = file_get_contents($image['tmp_name']);
                $b64   = base64_encode($imageData);
                $data['image'] = $b64;
            } else {
                $data['image'] = $mentor->image;
            }

            $mentor = $this->Mentors->patchEntity($mentor, $data);
            if ($this->Mentors->save($mentor)) {
                $this->Flash->success(__('The mentor has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The mentor could not be saved. Please, try again.'));
        }
        $skills = $this->Mentors->Skills->find('list', ['limit' => 200]);
        $this->set(compact('mentor', 'skills'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Mentor id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->getRequest()->allowMethod(['get', 'post', 'delete']);
        $mentor = $this->Mentors->get($id);
        $success = false;
        if ($this->Mentors->delete($mentor)) {
            if($this->isApi()){
                $success = true;
            } else {
                $this->Flash->success(__('The mentor has been deleted.'));
            }
        } else {
            if($this->isApi()){
                $success = false;
            } else {
                $this->Flash->error(__('The mentor could not be deleted. Please, try again.'));
            }
        }

        if($this->isApi()){
            $this->set(compact('success'));
            $this->set('_serialize', ['success']);
        } else {
            return $this->redirect(['action' => 'index']);
        }
    }

    /**
     * function deactivate
     * désactive un equipement, set sa date de delete à now
     */
    public function deactivate($id = null){
        $this->setDeleted($id, Time::now());
    }

    /**
     * function reactivate
     * reactive un equipement, set sa valeur deleted à null.
     */
    public function reactivate($id = null){
        $this->setDeleted($id, null);
    }

    private function setDeleted($id, $deleted){
        $this->getRequest()->allowMethod(['get', 'post']);
        $mentor = $this->Mentors->get($id);
        $mentor->deleted = $deleted;
        $success = false;

        $state = $deleted == null ? 'reactivated' : 'deactivated';

        if ($this->Mentors->save($mentor)) {
            if($this->isApi()){
                $success = true;
            } else {
                $this->Flash->success(__('The mentor has been ' . $state .'.'));
            }
        } else {
            if($this->isApi()){
                $success = false;
            } else {
                $this->Flash->error(__('The mentor could not be ' . $state . '. Please, try again.'));
            }
        }

        if($this->isApi()){
            $this->set(compact('success'));
            $this->set('_serialize', ['success']);
        } else {
            return $this->redirect($this->referer());
        }
    }
    
    public function isAuthorized($user)
    {
        return $this->Auth->user('admin_status') == 'admin';
    }

    /**
     * fonction search qui est appelée par la ajax request de la page mentors/index
     * en fonction des requetes ajax retourne différentes liste de mentors
     */

    public function search()
    {
        ini_set('memory_limit', '-1');

        if($this->isApi()){
            $this->getRequest()->allowMethod('post');
        }else {
            $this->getRequest()->allowMethod('ajax');
        }
   
        $keyword = "";
        $sort_field = "email";
        $sort_dir = "asc";

        $search_available = true;
        $search_unavailable = true;
        $search_mentors = true;
        $search_skills = true;

        if ($this->getRequest()->is('ajax')){
            $keyword = $this->getRequest()->getQuery('keyword');
            $sort_field = $this->getRequest()->getQuery('sort_field');
            $sort_dir = $this->getRequest()->getQuery('sort_dir');
            
            $filters = $this->getRequest()->getQuery('filters');
            $search_available = $filters['search_available'] == 'true';
            $search_unavailable = $filters['search_unavailable'] == 'true';
            $search_mentors = $filters['search_mentors'] == 'true';
            $search_skills = $filters['search_skills'] == 'true';
        
        } else if ($this->getRequest()->is('post')){
            $jsonData = $this->getRequest()->input('json_decode', true);
            $keyword = $jsonData['keyword'];
            $sort_field = $jsonData['sort_field'];
            $sort_dir = $jsonData['sort_dir'];
        }
        
        if($keyword == '')
        {
            $query = $this->Mentors->find('all');
        }
        else
        {
            if ($this->isApi()){
                $queryMentors = $this->Mentors->find('all', [
                    'contain' => ['Skills']
                ])
                    ->where(["match (Mentors.email, Mentors.first_name, Mentors.last_name, Mentors.description) against(:search in boolean mode)"])
                    ->bind(":search", $keyword . '*', 'string');
                $querySkills = $this->Mentors->find('all', [
                    'contain' => ['Skills']
                ])
                    ->innerJoinWith('Skills')
                    ->where(["match (Skills.name, Skills.description) against(:search in boolean mode)"])
                    ->bind(":search", $keyword . '*', 'string');

                $queryMentors->union($querySkills);
                $query = $queryMentors;
            }
            else if($search_mentors && $search_skills)
            {
                $queryMentors = $this->Mentors->find('all')
                    ->where(["match (Mentors.email, Mentors.first_name, Mentors.last_name, Mentors.description) against(:search in boolean mode)"])
                    ->bind(":search", $keyword . '*', 'string');
                $querySkills = $this->Mentors->find('all')
                    ->innerJoinWith('Skills')
                    ->where(["match (Skills.name, Skills.description) against(:search in boolean mode)"])
                    ->bind(":search", $keyword . '*', 'string');

                $queryMentors->union($querySkills);
                $query = $queryMentors;
            }
            else if($search_mentors)
            {
                $query = $this->Mentors->find('all')
                    ->where(["match (Mentors.email, Mentors.first_name, Mentors.last_name, Mentors.description) against(:search in boolean mode)"])
                    ->bind(":search", $keyword . '*', 'string');
            }
            else if($search_skills)
            {
                $query = $this->Mentors->find('all')
                    ->innerJoinWith('Skills')
                    ->where(["match (Skills.name, Skills.description) against(:search in boolean mode)"])
                    ->bind(":search", $keyword . '*', 'string');
            }

            
        }

        if (!is_null($query))
        {
            $query->order(["Mentors.".$sort_field => $sort_dir]);
        }
        
        $mentors = [];
        $archivedMentors = [];
        $allMentors = $this->paginate($query);
        foreach ($allMentors as $mentor){
            if($search_available && $mentor->available || $search_unavailable && !$mentor->available){
                if ($mentor->deleted != null && $mentor->deleted != "") {

                    if (!in_array($mentor,$archivedMentors))
                    {
                        array_push($archivedMentors, $mentor);
                    }
                } else {
                    if (!in_array($mentor,$mentors))
                    {
                        array_push($mentors, $mentor);
                    }
                }
            }
        }
        
        $this->set(compact('mentors', 'archivedMentors'));
        $this->set('_serialize', ['mentors', 'archivedMentors']);
    }

}
