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

    /**
     * Delete method
     *
     * @param string|null $id Mentor id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $mentor = $this->Mentors->get($id);
        if ($this->Mentors->delete($mentor)) {
            $this->Flash->success(__('The mentor has been deleted.'));
        } else {
            $this->Flash->error(__('The mentor could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
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

        $query->order([$sort_field => $sort_dir]);
        
        $mentors = [];
        $archivedMentors = [];
        $allMentors = $this->paginate($query);
        foreach ($allMentors as $mentor){
            if($search_available && $mentor->available || $search_unavailable && !$mentor->available){
                if ($mentor->deleted != null && $mentor->deleted != "") {
                
                    array_push($archivedMentors, $mentor);
                } else {
                    array_push($mentors, $mentor);
                }
            }
        }
        
        $this->set(compact('mentors', 'archivedMentors'));
        $this->set('_serialize', ['mentors', 'archivedMentors']);
    }

}
