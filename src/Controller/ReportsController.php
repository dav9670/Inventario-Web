<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\I18n\Time;
use Cake\Datasource\ConnectionManager;

/**
 * Reports Controller
 */
class ReportsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
    }

    public function mentorsReport()
    {
        $start_date = $this->getRequest()->getQuery('start_date');
        $end_date = $this->getRequest()->getQuery('end_date');
        $sort_field = $this->getRequest()->getQuery('sort_field') != null ? $this->getRequest()->getQuery('sort_field') : 'email';
        $sort_dir = $this->getRequest()->getQuery('sort_dir') != null ? $this->getRequest()->getQuery('sort_dir') : 'asc';

        $conn = ConnectionManager::get('default');
        $proc_result = $conn->execute("call mentors_report(?,?,?,?)", [$start_date, $end_date, $sort_field, $sort_dir])->fetchAll('assoc');

        $this->set('report', $proc_result);
        $this->set('_serialize', 'report');
    }

    public function licencesReport()
    {
        $start_date = $this->getRequest()->getQuery('start_date');
        $end_date = $this->getRequest()->getQuery('end_date');
        $conn = ConnectionManager::get('default');
        $proc_result = $conn->execute("call licences_report(?,?)", [$start_date, $end_date])->fetchAll('assoc');
        
        foreach($proc_result as &$line)
        {
            $line["licence"] = substr($line["licence"], 0, 5) . "..." . substr($line["licence"], -5, 5);
            $line["used"] = $line["uses"] > 0;
            $line["expired"] = $line["expired"] == 1;
            $line["percent_used"] = "-";
        }
        unset($line);

        $this->set('report', $proc_result);
        $this->set('_serialize', 'report');
    }

    public function isAuthorized($user)
    {
        return $this->Auth->user('admin_status') == 'admin';
    }
}
