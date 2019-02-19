<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Skills Controller
 *
 * @property \App\Model\Table\SkillsTable $Skills
 *
 * @method \App\Model\Entity\Skill[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class SkillsController extends AppController
{
    public $paginate = [
        'Skills' => [
            'limit' => 15,
            'order' => [
                'Skills.name' => 'asc'
            ]
        ],
        'Mentors' => [
            'limit' => 15,
            'order' => [
                'Mentors.email' => 'asc'
            ]
        ]
    ];

    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Paginator');
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $skills = $this->paginate($this->Skills);

        $this->set(compact('skills'));
        $this->set('_serialize', ['skills']);
    }

    /**
     * View method
     *
     * @param string|null $id Skill id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $skill = $this->Skills->get($id, [
            'contain' => ['Mentors']
        ]);

        $this->set('skill', $skill);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $skill = $this->Skills->newEntity();
        if ($this->getRequest()->is('post')) {
            $skill = $this->Skills->patchEntity($skill, $this->getRequest()->getData());
            if ($this->Skills->save($skill)) {
                $this->Flash->success(__('The skill has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The skill could not be saved. Please, try again.'));
        }
        $mentors = $this->Skills->Mentors->find('list', ['limit' => 200]);
        $this->set(compact('skill', 'mentors'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Skill id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $skill = $this->Skills->get($id, [
            'contain' => ['Mentors']
        ]);
        if ($this->getRequest()->is(['patch', 'post', 'put'])) {
            $skill = $this->Skills->patchEntity($skill, $this->getRequest()->getData());
            if ($this->Skills->save($skill)) {
                $this->Flash->success(__('The skill has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The skill could not be saved. Please, try again.'));
        }
        $mentors = $this->Skills->Mentors->find('list', ['limit' => 200]);
        $this->set(compact('skill', 'mentors'));
    }

    /**
     * Consult method
     *
     * @param string|null $id Skill id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function consult($id = null)
    {
        $skill = $this->Skills->get($id, [
            'contain' => ['Mentors']
        ]);
        if ($this->getRequest()->is(['patch', 'post', 'put'])) {
            $skill = $this->Skills->patchEntity($skill, $this->getRequest()->getData());
            if ($this->Skills->save($skill)) {
                $this->Flash->success(__('The skill has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The skill could not be saved. Please, try again.'));
        }
        $mentors = $this->Skills->Mentors->find('list', ['limit' => 200]);
        $this->set(compact('skill', 'mentors'));
    }
    

    /**
     * Delete method
     *
     * @param string|null $id Skill id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->getRequest()->allowMethod(['post', 'delete']);
        $skill = $this->Skills->get($id);
        if ($this->Skills->delete($skill)) {
            $this->Flash->success(__('The skill has been deleted.'));
        } else {
            $this->Flash->error(__('The skill could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function unlink()
    {
        $this->getRequest()->allowMethod(['post', 'delete']);
        $skill = $this->Skills->get($this->getRequest()->getQuery('skill'));
        $mentor = $this->Skills->Mentors->get($this->getRequest()->getQuery('mentor'));

        if ($this->Skills->Mentors->unlink($skill, [$mentor])) {
            $this->Flash->success(__('The association has been deleted.'));
        } else {
            $this->Flash->error(__('The association could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'consult', $skill->id]);
    }

    public function search()
    {
        $this->getRequest()->allowMethod('ajax');
   
        $keyword = $this->getRequest()->getQuery('keyword');
        $sort_field = $this->getRequest()->getQuery('sort_field');
        $sort_dir = $this->getRequest()->getQuery('sort_dir');
        if($keyword == '')
        {
            $query = $this->Skills->find('all');
        }
        else
        {
            $query = $this->Skills->find('all')
                ->where(["match (name, description) against(:search in boolean mode)"])
                ->bind(":search", $keyword . '*', 'string');
        }

        $query->order([$sort_field => $sort_dir]);
        
        $this->set('skills', $this->paginate($query));
        $this->set('_serialize', ['skills']);
    }

    public function isAuthorized($user)
    {
        return $this->Auth->user('admin_status') == 'admin';
    }
}
