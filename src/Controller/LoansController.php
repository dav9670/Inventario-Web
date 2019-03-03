<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Loans Controller
 *
 * @property \App\Model\Table\LoansTable $Loans
 *
 * @method \App\Model\Entity\Loan[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class LoansController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $loans = $this->Loans->find('all');
        $this->set(compact('loans'));
    }

    /**
     * View method
     *
     * @param string|null $id Loan id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $loan = $this->Loans->get($id, [
            'contain' => ['Users', 'Items']
        ]);

        $this->set('loan', $loan);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $loan = $this->Loans->newEntity();
        if ($this->request->is('post')) {
            $loan = $this->Loans->patchEntity($loan, $this->request->getData());
            if ($this->Loans->save($loan)) {
                $this->Flash->success(__('The loan has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The loan could not be saved. Please, try again.'));
        }
        $users = $this->Loans->Users->find('list', ['limit' => 200]);
        $items = $this->Loans->Items->find('list', ['limit' => 200]);
        $this->set(compact('loan', 'users', 'items'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Loan id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $loan = $this->Loans->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $loan = $this->Loans->patchEntity($loan, $this->request->getData());
            if ($this->Loans->save($loan)) {
                $this->Flash->success(__('The loan has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The loan could not be saved. Please, try again.'));
        }
        $users = $this->Loans->Users->find('list', ['limit' => 200]);
        $items = $this->Loans->Items->find('list', ['limit' => 200]);
        $this->set(compact('loan', 'users', 'items'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Loan id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $loan = $this->Loans->get($id);
        if ($this->Loans->delete($loan)) {
            $this->Flash->success(__('The loan has been deleted.'));
        } else {
            $this->Flash->error(__('The loan could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function search()
    {
        ini_set('memory_limit', '-1');

        if($this->isApi()){
            $this->getRequest()->allowMethod('get', 'post');
        }else {
            $this->getRequest()->allowMethod('get', 'ajax');
        }
   
        $keyword = "";
        $sort_field = "item";
        $sort_dir = "asc";

        $search_items = true;
        $search_labels = true;
        $search_users = true;
        $date_from = '';
        $date_to = '';

        if ($this->isApi()){
            $jsonData = $this->getRequest()->input('json_decode', true);
            $keyword = $jsonData['keyword'];
            $sort_field = $jsonData['sort_field'];
            $sort_dir = $jsonData['sort_dir'];
        } else {
            $keyword = $this->getRequest()->getQuery('keyword');
            $sort_field = $this->getRequest()->getQuery('sort_field');
            $sort_dir = $this->getRequest()->getQuery('sort_dir');
            
            $filters = $this->getRequest()->getQuery('filters');
            $search_items = $filters['search_items'] == 'true';
            $search_labels = $filters['search_labels'] == 'true';
            $search_users = $filters['search_users'] == 'true';
            $search_labels = $filters['date_from'];
            $search_users = $filters['date_to'];
        }

        $query = null;

        $options = [];
        if($this->isApi())
        {
            //$options = ['contain' => ['Labels']];
        }
        
        if($keyword == '')
        {
            $query = $this->Loans->find('all', $options);
        }
        else
        {
            $union_query = null;

            if($search_items)
            {
                $query = $this->Loans->find('all', $options)
                    ->where(["match (Loans.email, Loans.first_name, Loans.last_name, Loans.description) against(:search in boolean mode)
                        or Loans.email like :like_search or Loans.first_name like :like_search or Loans.last_name like :like_search or Loans.description like :like_search"])
                    ->bind(":search", $keyword, 'string')
                    ->bind(":like_search", '%' . $keyword . '%', 'string');
            }
            if($search_labels)
            {
                if($query != null){
                    $union_query = $query;
                }   

                $query = $this->Loans->find('all')
                    ->innerJoinWith('Labels')
                    ->where(["match (Labels.name, Labels.description) against(:search in boolean mode)
                        or Labels.name like :like_search or Labels.description like :like_search"])
                    ->bind(":search", $keyword, 'string')
                    ->bind(":like_search", '%' . $keyword . '%', 'string');
                
                if($union_query != null){
                    $query->union($union_query);
                }
            }
            if($search_users)
            {
                if($query != null){
                    $union_query = $query;
                }   

                $query = $this->Loans->find('all')
                    ->innerJoinWith('Labels')
                    ->where(["match (Labels.name, Labels.description) against(:search in boolean mode)
                        or Labels.name like :like_search or Labels.description like :like_search"])
                    ->bind(":search", $keyword, 'string')
                    ->bind(":like_search", '%' . $keyword . '%', 'string');
                
                if($union_query != null){
                    $query->union($union_query);
                }
            }
        }

        if ($query != null)
        {
            //$connection = ConnectionManager::get('default');
            //$query->epilog($connection->newQuery()->order(['Loans_' . $sort_field => $sort_dir]));
            $query->order(["Loans.".$sort_field => $sort_dir]);
        }
        
        $query = $this->Loans->find('all');

        $loans = [];
        $returnedLoans = [];
        $allLoans = $query->toList();
        foreach ($allLoans as $loan){
            if ($loan->returned != null) {

                if (!in_array($loan,$returnedLoans))
                {
                    array_push($returnedLoans, $loan);
                }
            } else {
                if (!in_array($loan,$loans))
                {
                    array_push($loans, $loan);
                }
            }
        }
        
        $this->set(compact('loans', 'returnedLoans'));
        $this->set('_serialize', ['loans', 'returnedLoans']);
    }

    public function isAuthorized($user)
    {
        return $this->Auth->user('admin_status') == 'admin';
    }
}
