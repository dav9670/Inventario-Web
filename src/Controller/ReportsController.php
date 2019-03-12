<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\I18n\Time;
use Cake\Datasource\ConnectionManager;
use Cake\ORM\TableRegistry;

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

    public function roomsReport(){

        $conn = ConnectionManager::get('default');
        
        $start_date = $this->getRequest()->getQuery('start_date');
        $end_date = $this->getRequest()->getQuery('end_date');
        $sort_field = $this->getRequest()->getQuery('sort_field') != null ? $this->getRequest()->getQuery('sort_field') : 'name';
        $sort_dir = $this->getRequest()->getQuery('sort_dir') != null ? $this->getRequest()->getQuery('sort_dir') : 'asc';

        $query = TableRegistry::get('Loans')->find('all',[
            'conditions' => [
            'or' =>    [
                            ['Loans.start_time >= ' => $start_date,
                                  'Loans.start_time < ' => $end_date
                            ]
                        ]   ],
            'or' => [
                        ['Loans.returned >= ' => $start_date,
                            'Loans.returned < ' => $end_date
                        ]
                    
                    ],
            'or' => [
                ['Loans.end_time >= ' => $start_date,
                    'Loans.end_time < ' => $end_date
                ]
            ],  
            'contain' => ['rooms.services']
        ])->where(['item_type' => 'rooms']);

        $loans = $this->paginate($query);

        $loansForResult = [];

        foreach ($loans as $loan){
            if($loan->end_time < Time::now() && $loan->returned == null){
                
            }else{
                array_push($loansForResult, $loan);
            }
        }
    
        $loansResult = [];

        foreach ($loansForResult as $loan){
            array_push($loansResult,$loan->_getTimePresetsForRooms($start_date, $end_date, $loan['Rooms']));
            
        }

        $finalResults = [];

        foreach($loansResult as $result){
            $taken = false;
            $index = 0;
            foreach($finalResults as $oneFinalResult){
                $nameResult = key($result);
                $nameFinalResult = key($oneFinalResult);
                if((string)$nameResult == (string)$nameFinalResult && $taken!= true){
                    $taken = true;
                    $tempResult = $finalResults[$index];
                    for($i = 0; $i < 10; $i++){
                        $tempResult[key($result)][$i] += $result[key($result)][$i];
                    }
                    $finalResults[$index] = $tempResult;
                }
                $index++;
            }
            
            if($taken == false){
                array_push($finalResults, $result);
            }
        }

        $usableResults = [];

        for($i = 0; $i < sizeof($finalResults); $i++)
        {
            $usable = [15];
            $usable[0] = (string)key($finalResults[$i]);
            for($j = 0; $j < sizeof($finalResults[$i][key($finalResults[$i])]); $j++)
            {
                $usable[$j + 1] = (string)$finalResults[$i][key($finalResults[$i])][$j];
            }
            array_push($usableResults, $usable);
        }

        $change = true;
        while($change){
            $change = false;
            for ($i = 0; $i < sizeof($usableResults) - 1; $i++) {
                if ( $sort_field == 'name' && $sort_dir == 'desc') {
                    if ($usableResults[$i][0] < $usableResults[$i + 1][0]){
                        $temp = $usableResults[$i];
                        $usableResults[$i] = $usableResults[$i + 1];
                        $usableResults[$i + 1] = $temp;
                        $change = true;
                    }
                } else if ( $sort_field == 'total' && $sort_dir == 'asc') {
                    if ((int)$usableResults[$i][1] > (int)$usableResults[$i + 1][1]){
                        $temp = $usableResults[$i];
                        $usableResults[$i] = $usableResults[$i + 1];
                        $usableResults[$i + 1] = $temp;
                        $change = true;
                    }
                } else if ( $sort_field == 'total' && $sort_dir == 'desc') {
                    if ((int)$usableResults[$i][1] < (int)$usableResults[$i + 1][1]){
                        $temp = $usableResults[$i];
                        $usableResults[$i] = $usableResults[$i + 1];
                        $usableResults[$i + 1] = $temp;
                        $change = true;
                    }
                } else {
                    if ($usableResults[$i][0] > $usableResults[$i + 1][0]){
                        $temp = $usableResults[$i];
                        $usableResults[$i] = $usableResults[$i + 1];
                        $usableResults[$i + 1] = $temp;
                        $change = true;
                    }
                } 
            }
        }

        if($this->isApi()){
            $this->set(compact('usableResults'));
        $this->set('_serialize', ['usableResults']);
        } else {
            $this->set('report', $usableResults);
            $this->set('_serialize', 'report');
        }
    }

    public function equipmentsReport()
    {
        $start_date = $this->getRequest()->getQuery('start_date');
        $end_date = $this->getRequest()->getQuery('end_date');
        $sort_field = $this->getRequest()->getQuery('sort_field') != null ? $this->getRequest()->getQuery('sort_field') : 'c.name';
        $sort_dir = $this->getRequest()->getQuery('sort_dir') != null ? $this->getRequest()->getQuery('sort_dir') : 'asc';
        $conn = ConnectionManager::get('default');
        $proc_result = $conn->execute("call equipments_report(?,?,?,?)", [$start_date, $end_date, $sort_field, $sort_dir])->fetchAll('assoc');
        
        $time = 0;
        $hour = 0;
        $avai = 0;
        $late = 0;

        $totalAvailable = 0;
        $finalArray = array();
        $id = 0;
        $categories = TableRegistry::get('Categories')->find('all', ['contain' => ['Equipments']]);

        foreach($categories as $category){

            $finalArray[$id]["cat"] = $category["name"];
            $finalArray[$id]['equipments'] = array();
            $finalArray[$id]['equipmentName'] = " ";
            $finalArray[$id]["hour_loans"] =  0;
            $finalArray[$id]["late_loans"] = " ";
            $finalArray[$id]["time_loans"] = " ";
            $finalArray[$id]["available"] = " ";

            $available =0;
            $price = 0;
            $late = 0;
            $timesLoans =0;
            foreach ($category->toArray()['equipments'] as $key => $equipment){
                $equipArray = array();
                $value = 0;
                $lateLoans = 0;
                $query2 = TableRegistry::get('Loans')->find('all', ['contain' => ['Equipments']])
                ->where('Loans.item_type like \'equipments\' and Loans.item_id = :id')
                ->bind(':id', $equipment['id']);
                $equipmentLoans = $query2->toArray();
                foreach($equipmentLoans as $loan){
                    if($loan["overtime_fee"] != 0){
                        $value += $loan['overtime_fee'];
                        $lateLoans += 1;
                    }
                    
                }
                $query3 = TableRegistry::get('Loans')->find('all', ['contain' => ['Equipments']])
                ->where('Loans.item_type like \'equipments\' and Loans.item_id = :id and (Loans.start_time >= :start and Loans.end_time <= :end)')
                ->bind(':id', $equipment['id'])
                ->bind(':start', $start_date)
                ->bind(':end', $end_date);
                $equipArray['cat'] = " ";
                $equipArray['equipmentName'] = $equipment['name'];
                $equipArray["hour_loans"] =  money_format('%.2n', $value) . "$";
                $equipArray["late_loans"] = $lateLoans;
                $equipArray["time_loans"] = sizeof($query3->toArray());
                $equipArray["available"] = $equipment["available"];
                $finalArray[$id]["equipments"][$key] = $equipArray;
                $price += $value;
                $late += $lateLoans;
                $available += $equipment['available'] ? 1 :0;
                $timesLoans += sizeof($query3->toArray());
            }

            $finalArray[$id]['time_loans'] = $timesLoans;
            $finalArray[$id]['available'] = $available;
            $finalArray[$id]['late_loans'] =$late;
            $finalArray[$id]['hour_loans'] =money_format('%.2n', $price);
            $id = $id +1;
            $hour += money_format('%.2n', $price);
        }


        $change = true;
        while($change){
            $change = false;
            if($sort_field == "hour_loans" && $sort_dir == "asc"){
                for($i = 0; $i < sizeof($finalArray) - 1; $i++){
                    if ($finalArray[$i]['hour_loans'] < $finalArray[$i + 1]['hour_loans']){
                        $temp = $finalArray[$i];
                        $finalArray[$i] = $finalArray[$i + 1];
                        $finalArray[$i + 1] = $temp;
                        $change = true;
                    }
                }
            } elseif($sort_field == "hour_loans" && $sort_dir == "desc"){
                for($i = 0; $i < sizeof($finalArray) - 1; $i++){
                    if ($finalArray[$i]['hour_loans'] > $finalArray[$i + 1]['hour_loans']){
                        $temp = $finalArray[$i];
                        $finalArray[$i] = $finalArray[$i + 1];
                        $finalArray[$i + 1] = $temp;
                        $change = true;
                    }
                }
            } elseif($sort_field == "late_loans" && $sort_dir == "asc"){
                for($i = 0; $i < sizeof($finalArray) - 1; $i++){
                    if ($finalArray[$i]['late_loans'] < $finalArray[$i + 1]['late_loans']){
                        $temp = $finalArray[$i];
                        $finalArray[$i] = $finalArray[$i + 1];
                        $finalArray[$i + 1] = $temp;
                        $change = true;
                    }
                }
            } elseif($sort_field == "late_loans" && $sort_dir == "desc"){
                for($i = 0; $i < sizeof($finalArray) - 1; $i++){
                    if ($finalArray[$i]['late_loans'] > $finalArray[$i + 1]['late_loans']){
                        $temp = $finalArray[$i];
                        $finalArray[$i] = $finalArray[$i + 1];
                        $finalArray[$i + 1] = $temp;
                        $change = true;
                    }
                }
            } elseif($sort_field == "available" && $sort_dir == "asc"){
                for($i = 0; $i < sizeof($finalArray) - 1; $i++){
                    if ($finalArray[$i]['available'] < $finalArray[$i + 1]['available']){
                        $temp = $finalArray[$i];
                        $finalArray[$i] = $finalArray[$i + 1];
                        $finalArray[$i + 1] = $temp;
                        $change = true;
                    }
                }
            } elseif($sort_field == "available" && $sort_dir == "desc"){
                for($i = 0; $i < sizeof($finalArray) - 1; $i++){
                    if ($finalArray[$i]['available'] > $finalArray[$i + 1]['available']){
                        $temp = $finalArray[$i];
                        $finalArray[$i] = $finalArray[$i + 1];
                        $finalArray[$i + 1] = $temp;
                        $change = true;
                    }
                }
            } elseif($sort_field == "time_loans" && $sort_dir == "asc"){
                for($i = 0; $i < sizeof($finalArray) - 1; $i++){
                    if ($finalArray[$i]['time_loans'] < $finalArray[$i + 1]['time_loans']){
                        $temp = $finalArray[$i];
                        $finalArray[$i] = $finalArray[$i + 1];
                        $finalArray[$i + 1] = $temp;
                        $change = true;
                        
                    }
                }
            } elseif($sort_field == "time_loans" && $sort_dir == "desc"){
                for($i = 0; $i < sizeof($finalArray) - 1; $i++){
                    if ($finalArray[$i]['time_loans'] > $finalArray[$i + 1]['time_loans']){
                        $temp = $finalArray[$i];
                        $finalArray[$i] = $finalArray[$i + 1];
                        $finalArray[$i + 1] = $temp;
                        $change = true;
                    }
                }
            } elseif($sort_field == "cat" && $sort_dir == "asc"){
                for($i = 0; $i < sizeof($finalArray) - 1; $i++){
                    if ($finalArray[$i]['cat'] < $finalArray[$i + 1]['cat']){
                        $temp = $finalArray[$i];
                        $finalArray[$i] = $finalArray[$i + 1];
                        $finalArray[$i + 1] = $temp;
                        $change = true;
                    }
                }
            } elseif($sort_field == "cat" && $sort_dir == "desc"){
                for($i = 0; $i < sizeof($finalArray) - 1; $i++){
                    if ($finalArray[$i]['cat'] > $finalArray[$i + 1]['cat']){
                        $temp = $finalArray[$i];
                        $finalArray[$i] = $finalArray[$i + 1];
                        $finalArray[$i + 1] = $temp;
                        $change = true;
                    }
                }
            }
        }

        $query4 = TableRegistry::get('Loans')->find('all', ['contain' => ['Equipments']])
        ->where('Loans.item_type like \'equipments\' and (Loans.end_time <= Loans.returned or (Loans.end_time <= now() and returned is null))');
        $availables = TableRegistry::get('Equipments')->find('all', ['contain' => ['Loans']])->toArray();

        foreach($availables as $value){
            $totalAvailable += $value['available'] ? 1 : 0;
        }
        $totalLate = sizeof($query4->toArray());
        $finalArray[$id]["cat"] = "Total";
        $finalArray[$id]["equipmentName"] = "";
        $finalArray[$id]["hour_loans"] =  money_format('%.2n', $hour) . "$";
        $finalArray[$id]["late_loans"] = $totalLate;
        $finalArray[$id]["time_loans"] = $time;
        $finalArray[$id]["available"] = $totalAvailable;
        
        for($i = 0; $i < sizeof($finalArray) - 1; $i++){
            $finalArray[$i]['hour_loans'] = $finalArray[$i]['hour_loans'] == 0 ? '---' : $finalArray[$i]['hour_loans'] . '$';
            $finalArray[$i]["late_loans"] = ($finalArray[$i]["late_loans"] == null) ? '---' : $finalArray[$i]["late_loans"];
            $finalArray[$i]["time_loans"] = ($finalArray[$i]["time_loans"] == null) ? '---' : $finalArray[$i]["time_loans"];
            $finalArray[$i]["available"] = $finalArray[$i]["available"] == null ? '---' : $finalArray[$i]["available"];

        }
        $this->set('report', $finalArray);
        $this->set('_serialize', 'report');
    }

    public function isAuthorized($user)
    {
        return $this->Auth->user('admin_status') == 'admin';
    }
}
