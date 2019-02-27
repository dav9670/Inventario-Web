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

        $final_result = array();
        $prev_line = null;
        $id = 0;
        foreach($proc_result as &$line)
        {
            if ($prev_line != null && ($prev_line["product"] != $line["product"] || $prev_line["platform"] != $line["platform"]))
            {
                $final_result[$id]["percent_used"] = ($final_result[$id]["used"] * 100 / count($final_result[$id]["licences"])) . "%";
                $id += 1;
            }

            $final_result[$id]["product"] = $line["product"];
            $final_result[$id]["platform"] = $line["platform"];

            $final_result[$id]["used"] = $final_result[$id]["used"] ?? 0;
            $final_result[$id]["used"] += $line["used"] ? 1 : 0;

            $final_result[$id]["expired"] = $final_result[$id]["expired"] ?? 0;
            $final_result[$id]["expired"] += $line["expired"] ? 1 : 0;

            $final_result[$id]["uses"] = $final_result[$id]["uses"] ?? 0;
            $final_result[$id]["uses"] += $line["uses"];

            $new_licence["licence"] = $line["licence"];
            $new_licence["used"] = $line["used"];
            $new_licence["expired"] = $line["expired"];
            $new_licence["uses"] = $line["uses"];

            $final_result[$id]["licences"][] = $new_licence;

            $prev_line = $line;
        }
        unset($line);

        $final_result[$id]["percent_used"] = ($final_result[$id]["used"] * 100 / count($final_result[$id]["licences"])) . "%";

        $this->set('report', $final_result);
        $this->set('_serialize', 'report');
    }

    public function isAuthorized($user)
    {
        return $this->Auth->user('admin_status') == 'admin';
    }
}
