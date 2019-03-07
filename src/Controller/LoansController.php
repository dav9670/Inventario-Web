<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Utility\Inflector;
use Cake\ORM\TableRegistry;

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
        $options = [
            'contain'=>[
                'Mentors' => [
                    'Skills'
                ]
            ]
        ];

        $query = $this->Loans->find('all', $options);
        $loan = $query->toList()[1];

        //dd($query->sql());
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
            'contain' => ['Users', 'Mentors.Skills', 'Rooms.Services', 'Licences.Products', 'Equipments.Categories']
        ]);
        $this->set(compact('loan'));
        if($this->isApi()){
            $this->set('_serialize', 'loan');
        }
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $loan = $this->Loans->newEntity();
        $success = false;
        if ($this->request->is('post')) {
            $loan = $this->Loans->patchEntity($loan, $this->request->getData());
            if ($this->Loans->save($loan)) {
                if($this->isApi()){
                    $success = true;
                } else {
                    $this->Flash->success(__('The loan has been saved.'));

                    return $this->redirect(['action' => 'index']);
                }
            }else if($this->isApi()){
                $success = false;
            }else {
                $this->Flash->error(__('The loan could not be saved. Please, try again.'));
            }
        }

        if($this->isApi()){
            $this->set(compact('success'));
            $this->set('_serialize', ['success']);
        } else {
            $this->set(compact('loan'));
        }
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
        $this->set(compact('loan', 'users'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Loan id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function return($id = null)
    {
        $loan = $this->Loans->get($id, [
            'contain' => ['Users', 'Mentors.Skills', 'Rooms.Services', 'Licences.Products', 'Equipments.Categories']
        ]);
        if ($this->request->is(['patch', 'post', 'put', 'delete'])) {
            $success = false;
            $loan = $this->Loans->patchEntity($loan, $this->request->getData());
            if ($this->Loans->save($loan)) {
                $success = true;
                if($this->isApi()){
                    $this->set(compact('success'));
                    $this->set('_serialize', ['success']);
                    return;
                } else {
                    $this->Flash->success(__('The loan has been saved.'));

                    return $this->redirect(['action' => 'index']);
                }
            }
            $success = false;
            if($this->isApi()){
                $this->set(compact('success'));
                $this->set('_serialize', ['success']);
                return;
            } else {
                $this->Flash->error(__('The loan could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('loan'));
        if($this->isApi()){
            $this->set('_serialize', 'loan');
        }
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

    /**
     *  Used to construct string (match(...) or string like '%a%') using the name of the table and an array of strings
    */
    private function makeStringSearch($table, $searchFields)
    {
        $whereQuery = '';
        $whereQuery .= 'match (';
        for($i = 0, $size = count($searchFields); $i < $size; ++$i)
        {
            $whereQuery .= $table . '.' . $searchFields[$i] . ($i < $size - 1 ? ', ' : ')');
        }
        $whereQuery .= ' against(:search in boolean mode) or ';
        for($i = 0, $size = count($searchFields); $i < $size; ++$i)
        {
            $whereQuery .= $table . '.' . $searchFields[$i] . ' like :like_search' . ($i < $size - 1 ? ' or ' : '');
        }

        return $whereQuery;
    }

    public function search()
    {
        ini_set('memory_limit', '-1');

        if($this->isApi()){
            $this->getRequest()->allowMethod(['get', 'post']);
        }else {
            $this->getRequest()->allowMethod(['get', 'ajax']);
        }
   
        $keyword = "";
        $sort_field = "item";
        $sort_dir = "asc";

        $search_items = true;
        $search_labels = true;
        $search_users = true;
        $item_type = 'all';
        $start_time = '';
        $end_time = '';

        if ($this->isApi()){
            $jsonData = $this->getRequest()->input('json_decode', true);
            $keyword = $jsonData['keyword'];
            $item_type = $jsonData['itemType'];
            $start_time = $jsonData['startTime'];
            $end_time = $jsonData['endTime'];
            $search_items = $jsonData['searchItems'] == 'true';
            $search_labels = $jsonData['searchLabels'] == 'true';
            $search_users = $jsonData['searchUsers'] == 'true';
        } else {
            $keyword = $this->getRequest()->getQuery('keyword');
            $sort_field = $this->getRequest()->getQuery('sort_field');
            $sort_dir = $this->getRequest()->getQuery('sort_dir');
            
            $filters = $this->getRequest()->getQuery('filters');
            $search_items = $filters['search_items'] == 'true';
            $search_labels = $filters['search_labels'] == 'true';
            $search_users = $filters['search_users'] == 'true';
            $item_type = $filters['item_type'];
            $start_time = $filters['start_time'];
            $end_time = $filters['end_time'];
        }

        $options = [
            'contain'=>[
                'Users',
                'Mentors',
                'Rooms',
                'Licences',
                'Equipments'
            ]
        ];

        $identifiers = [
            'mentors'=>[
                'identifier' => 'email',
                'description' => 'description',
                'labels' => 'skills_list',
                'image' => 'image'
            ],
            'rooms'=>[
                'identifier' => 'name',
                'description' => 'description',
                'labels' => 'services_list',
                'image' => 'image'
            ],
            'licences'=>[
                'identifier' => 'name',
                'description' => 'description',
                'labels' => 'products_list',
                'image' => 'image'
            ],
            'equipments'=>[
                'identifier' => 'name',
                'description' => 'description',
                'labels' => 'categories_list',
                'image' => 'image'
            ]
        ];

        $query = null;

        if($keyword == '' || ($search_items == false && $search_labels == false && $search_users == false))
        {
            $query = $this->Loans->find('all', $options);
        }
        else
        {
            $needs_union = false;
            $union_query = null;

            if($search_items)
            {
                $searchFieldsType = [
                    'mentors' => [
                        'email',
                        'first_name',
                        'last_name',
                        'description'
                    ],
                    'rooms' => [
                        'name',
                        'description'
                    ],
                    'licences' => [
                        'name',
                        'description'
                    ], 
                    'equipments' => [
                        'name',
                        'description'
                    ]
                ];

                $whereQuery = '';

                if($item_type == 'all')
                {
                    $i = 0;
                    foreach($searchFieldsType as $type => $searchFields)
                    {
                        $table = ucfirst($type);

                        $subQuery = $this->makeStringSearch($table, $searchFields);
                        $whereQuery .= $subQuery . ($i < count($searchFieldsType) - 1 ? ' or ' : '');
                        
                        ++$i;
                    }
                }
                else
                {
                    $table = ucfirst($item_type);
                    $searchFields = $searchFieldsType[$item_type];

                    $whereQuery = $this->makeStringSearch($table, $searchFields);
                    //var_dump($whereQuery);
                }

                $query = $this->Loans->find('all', $options)
                    ->where($whereQuery)
                    ->bind(":search", $keyword, 'string')
                    ->bind(":like_search", '%' . $keyword . '%', 'string');
            }
            if($search_labels)
            {
                if($query != null){
                    $union_query = $query;
                }   

                $searchFieldsType = [
                    'mentors' => [
                        'label_table' => 'skills',
                        'fields' => [
                            'name',
                            'description'
                        ]
                    ],
                    'rooms' => [
                        'label_table' => 'services',
                        'fields' => [
                            'name',
                            'description'
                        ]
                    ],
                    'licences' => [
                        'label_table' => 'products',
                        'fields' => [
                            'name',
                            'description'
                        ]
                    ], 
                    'equipments' => [
                        'label_table' => 'categories',
                        'fields' => [
                            'name',
                            'description'
                        ]
                    ]
                ];

                $whereQuery = '';
                $matchings = [];
                $joins = [];

                if($item_type == 'all')
                {
                    $i = 0;
                    foreach($searchFieldsType as $type => $searchFields)
                    {
                        $table = ucfirst($type);
                        $labelTable = ucfirst($searchFields['label_table']);

                        $subQuery = $this->makeStringSearch($labelTable, $searchFields['fields']);
                        $whereQuery .= $subQuery . ($i < count($searchFieldsType) - 1 ? ' or ' : '');
                        
                        ++$i;
                    }
                }
                else
                {
                    $table = ucfirst($item_type);
                    $labelTable = ucfirst($searchFields['label_table']);

                    $searchFields = $searchFieldsType[$item_type];

                    $whereQuery = $this->makeStringSearch($labelTable, $searchFields['fields']);
                }


                $query = $this->Loans->find('all', $options)
                    ->where($whereQuery)
                    ->bind(":search", $keyword, 'string')
                    ->bind(":like_search", '%' . $keyword . '%', 'string');
                
                var_dump($query->sql());

                if($union_query != null){
                    $query->union($union_query);
                }
            }
            if($search_users)
            {
                if($query != null){
                    $union_query = $query;
                }   

                $query = $this->Loans->find('all', $options)
                    ->where('Users.email like :like_search')
                    ->bind(":like_search", '%' . $keyword . '%', 'string');

                if($union_query != null){
                    $query->union($union_query);
                }
            }
        }

        if($item_type != 'all')
        {
            $query
                ->where('Loans.item_type like :item_type')
                ->bind(':item_type', $item_type, 'string');
        }
        if($start_time != '')
        {
            $query
                ->where('Loans.start_time > :start_time')
                ->bind(':start_time', $start_time);
        }
        if($end_time != '')
        {
            $query
                ->where('Loans.start_time < :end_time')
                ->bind(':end_time', $end_time);
        }

        $sqlSorted = false;

        if($item_type != 'all' || ($sort_field != 'item' && $sort_field != 'description'))
        {
            //Sort by sql
            $table = null;
            switch($sort_field)
            {
                case 'item':
                    $table = ucfirst($item_type);
                    $sort_field = $identifiers[$item_type]['identifier'];
                break;
                case 'description':
                    $table = ucfirst($item_type);
                    $sort_field = $identifiers[$item_type]['description'];
                break;
                case 'user':
                    $table = 'Users';
                    $sort_field = 'email';
                break;
                case 'start_time':
                    $table = 'Loans';
                break;
                case 'end_time':
                    $table = 'Loans';
                break;
                case 'returned':
                    $table = 'Loans';
                break;
            }
            $query->order([$table . "." . $sort_field => $sort_dir]);
            $sqlSorted = true;
        }

        $loans = [];
        $returnedLoans = [];
        $allLoans = $query->toList();

        foreach ($allLoans as $loan){

            $formattedLoan = [
                'item' => [
                    'id' => $loan->item_id,
                    'type' => $loan->item_type,
                    'identifier' => $loan[Inflector::singularize($loan->item_type)][$identifiers[$loan->item_type]['identifier']],
                    'description' => $loan[Inflector::singularize($loan->item_type)][$identifiers[$loan->item_type]['description']],
                    'labels' => $loan[Inflector::singularize($loan->item_type)][$identifiers[$loan->item_type]['labels']],
                    'image' => $loan[Inflector::singularize($loan->item_type)][$identifiers[$loan->item_type]['image']]
                ],
                'user' => [
                    'id' => $loan->user->id,
                    'identifier' => $loan->user->email
                ],
                'id' => $loan['id'],
                'start_time' => $loan['start_time'],
                'end_time' => $loan['end_time'],
                'returned' => $loan['returned']
            ];

            if ($formattedLoan['returned'] != null) {
                if (!in_array($formattedLoan,$returnedLoans))
                {
                    array_push($returnedLoans, $formattedLoan);
                }
            } else {
                if (!in_array($formattedLoan,$loans))
                {
                    array_push($loans, $formattedLoan);
                }
            }
        }
        
        if(!$sqlSorted)
        {
            //description stays same
            $sort_field_local = '';
            if($sort_field == 'item')
            {
                $sort_field_local = 'identifier'; 
            }
            else
            {
                $sort_field_local = $sort_field;
            }
            
            $sort_dir_local = $sort_dir == 'asc' ? 1 : -1;


            usort($loans, function($a, $b) use ($sort_field_local, $sort_dir_local){
                return strnatcmp($a['item'][$sort_field_local], $b['item'][$sort_field_local]) * $sort_dir_local;
            });
            usort($returnedLoans, function($a, $b) use ($sort_field_local, $sort_dir_local){
                return strnatcmp($a['item'][$sort_field_local], $b['item'][$sort_field_local]) * $sort_dir_local;
            });
        }

        $this->set(compact('loans', 'returnedLoans'));
        $this->set('_serialize', ['loans', 'returnedLoans']);
    }

    public function isAuthorized($user)
    {
        return $this->Auth->user('admin_status') == 'admin';
    }
}
