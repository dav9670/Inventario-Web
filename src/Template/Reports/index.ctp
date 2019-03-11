<?php

use Cake\I18n\I18n;

/**
 * @var \App\View\AppView $this
 */
?>
<div class="reports form large-12 medium-11 columns content">
    <fieldset>
        <legend><?= __('Report settings') ?></legend>

        <div class="left third-width">
            <label for="preset-dates"><?= __('Date Presets') ?></label>
            <select id="preset-dates">
                <option value="thisyear"><?= __('This year') ?></option>
                <option value="lastyear"><?= __('Last year') ?></option>
                <option value="thismonth"><?= __('This month') ?></option>
                <option value="lastmonth"><?= __('Last month') ?></option>
                <option value="thisweek"><?= __('This week') ?></option>
                <option value="lastweek"><?= __('Last week') ?></option>
                <option value="custom"><?= __('Custom') ?></option>
            </select>
        </div>

        <div class="right third-width">
            <label for="date-to"><?= __('To') ?></label>
            <input id='date-to' type="text" class="datepicker">
        </div>

        <div class="right third-width">
            <label for="date-from"><?= __('From') ?></label>
            <input id='date-from' type="text" class="datepicker">
        </div>

        <div style="clear: both;"></div>

        <label for="report-type"><?= __('Report type') ?></label>
        <select id="report-type">
            <option value="mentors"><?= __('Mentors') ?></option>
            <option value="rooms"><?= __('Rooms') ?></option>
            <option value="licences"><?= __('Licences') ?></option>
            <option value="equipments"><?= __('Equipments') ?></option>
        </select>
        
    </fieldset>

    <button type="submit" onclick='generateReport();'><?= __('Generate report') ?></button>

    <table id='report-table' cellpadding="0" cellspacing="0" style='display:none;'>
        <thead id='report-table-head'>
            <tr>
            </tr>
        </thead>
        <tbody id='report-table-body'>
        </tbody>
    </table>
</div>

<script>

    var sort_field = '';
    var sort_dir = '';

    let reportDict = {
        mentors: function(){
            setHeadersMentors();
            sort_field = '';
            sort_dir = '';
            sortSetter('email');
            setBodyMentors();
        },
        rooms: function(){
            setHeadersRooms();
            sort_field = '';
            sort_dir = '';
            sortSetter('name');
            setBodyRooms();
        },
        licences: function(){
            setHeadersLicences();
            setBodyLicences();
        },
        equipments: function(){
            setHeadersEquipments();
            sort_field = '';
            sort_dir = '';
            sortSetter('cat');
            setBodyEquipments();
        }
    }

    function setHeadersMentors(){
        $('#report-table-head').empty();
        $('#report-table-head').append(`
            <tr>
                <th scope="col"><a id="email_sort" onclick="sortSetter('email'); setBodyMentors();"><?= __("Email") ?></a></th>
                <th scope="col"><a id="hours_loaned_sort" onclick="sortSetter('hours_loaned'); setBodyMentors();"><?= __("Hours loaned") ?></a></th>
                <th scope="col"><a id="times_loaned_sort" onclick="sortSetter('times_loaned'); setBodyMentors();"><?= __("Times loaned") ?></a></th>
            </tr>
        `);
    }

    function setBodyMentors(){
        let start_date = $('#date-from').val();
        let end_date = $('#date-to').val();
        $.ajax({
            method: 'get',
            url : "/reports/mentors_report.json?start_date=" + start_date + "&end_date=" + end_date + "&sort_field=" + sort_field + "&sort_dir=" + sort_dir,
            headers: { 'X-CSRF-TOKEN': '<?=$this->getRequest()->getParam('_csrfToken');?>' },
            success: function( response ){
                $('#report-table-body').empty();
                
                var highest_mentors = [];

                var total_hours = 0;
                var total_times = 0;

                //field is the elem field, first is highest, second is second highest
                var highests = [
                    {
                        field:  'hours_loaned',
                        first:  0,
                        second: 0
                    },
                    {
                        field:  'times_loaned',
                        first:  0,
                        second: 0
                    }
                ];

                response.forEach(function(elem){
                    total_hours += parseInt(elem.hours_loaned);
                    total_times += parseInt(elem.times_loaned);

                    highests.forEach(function(highest){
                        var value = parseInt(elem[highest.field]);
                        if(value > highest.first){
                            highest.second = highest.first;
                            highest.first = value;
                        }
                        if(value > highest.second && value < highest.first){
                            highest.second = value;
                        }
                    });
                });

                response.forEach(function(elem){
                    highests.forEach(function(highest){
                        if(parseInt(elem[highest.field]) == highest.first && highest.first != highest.second && highest.second != 0){
                            elem[highest.field] = highest.first.toString() + ' (' + (highest.first - highest.second).toString() + ')';
                            if(highest.field == 'hours_loaned'){
                                highest_mentors.push(elem.email);
                            }
                        }
                    });
                });

                response.push({
                    email: 'Total',
                    hours_loaned: total_hours,
                    times_loaned: total_times
                });

                response.forEach(function(elem){
                    $('#report-table-body').append(`
                        <tr>
                            <td` + (highest_mentors.includes(elem.email) ? " class='highlighted-row'" : "") + `>` + elem.email + `</td>
                            <td>` + elem.hours_loaned + `</td>
                            <td>` + elem.times_loaned + `</td>
                        </tr>
                    `);
                });
            },
            error: function(jqXHR, textStatus, errorThrown){
                alert('The report could not be fetched');
                console.log(jqXHR.responseText);
            }
        });
    }

    function setHeadersLicences(){
        $('#report-table-head').empty();
        $('#report-table-head').append(`
            <tr>
                <th scope="col"><?= __("Product") ?></a></th>
                <th scope="col"><?= __("Platform") ?></a></th>
                <th scope="col"><?= __("Licence") ?></th>
                <th scope="col"><?= __("Used") ?></th>
                <th scope="col"><?= __("Expired") ?></th>
                <th scope="col"><?= __("Uses") ?></th>
                <th scope="col"><?= __("% Used") ?></th>
            </tr>
        `);
    }

    function setBodyLicences(){
        let start_date = $('#date-from').val();
        let end_date = $('#date-to').val();
        $.ajax({
            method: 'get',
            url : "/reports/licences_report.json?start_date=" + start_date + "&end_date=" + end_date,
            headers: { 'X-CSRF-TOKEN': '<?=$this->getRequest()->getParam('_csrfToken');?>' },
            success: function( response ){
                $('#report-table-body').empty();
                response.forEach(function(elem){
                    $('#report-table-body').append(`
                        <tr>
                            <td>` + elem.product + `</td>
                            <td>` + elem.platform + `</td>
                            <td></td>
                            <td>` + elem.used + `</td>
                            <td>` + elem.expired + `</td>
                            <td>` + elem.uses + `</td>
                            <td>` + elem.percent_used + `</td>
                        </tr>
                    `);
                    elem.licences.forEach(function(elem2){
                        $('#report-table-body').append(`
                            <tr>
                                <td></td>
                                <td></td>
                                <td>` + elem2.licence + `</td>
                                <td><img src='/img/` + (elem2.used ? "good" : "bad") + `.png' alt='Available' width=20 height=20></td>
                                <td><img src='/img/` + (elem2.expired ? "bad" : "good") + `.png' alt='Available' width=20 height=20></td>
                                <td>` + elem2.uses + `</td>
                                <td>-</td>
                            </tr>
                        `);
                    });
                    $('#report-table-body').append(`
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    `);
                });
            },
            error: function(jqXHR, textStatus, errorThrown){
                alert('The report could not be fetched');
                console.log(jqXHR.responseText);
            }
        });
    }

    function setHeadersRooms(){
        $('#report-table-head').empty();
        $('#report-table-head').append(`
            <tr>
                <th scope="col"><a id="name_sort" onclick="sortSetter('name'); setBodyRooms();"><?= __("Name") ?></a></th>
                <th><?= __("Services") ?></a></th>
                <th scope="col"><a id="total_sort" onclick="sortSetter('total'); setBodyRooms();"><?= __("Total by room") ?></a></th>
                <th id="h1" scope="col">08h - 09h</a></th>
                <th id="h2" scope="col">09h - 10h</a></th>
                <th id="h3" scope="col">10h - 11h</a></th>
                <th id="h4" scope="col">11h - 12h</a></th>
                <th id="h5"  scope="col">12h - 13h</a></th>
                <th id="h6"  scope="col">13h - 14h</a></th>
                <th id="h7"  scope="col">14h - 15h</a></th>
                <th id="h8"  scope="col">15h - 16h</a></th>
                <th id="h9"  scope="col">16h - 17h</a></th>
            </tr>
        `);
    }

    function setBodyRooms(){
        let start_date = $('#date-from').val();
        let end_date = $('#date-to').val();
        $.ajax({
            method: 'get',
            url : "/reports/rooms_report.json?start_date=" + start_date + "&end_date=" + end_date + "&sort_field=" + sort_field + "&sort_dir=" + sort_dir,
            headers: { 'X-CSRF-TOKEN': '<?=$this->getRequest()->getParam('_csrfToken');?>' },
            success: function( response ){
                $('#report-table-body').empty();
                var totalByHours = [0,0,0,0,0,0,0,0,0,0];
                var temp = response;
                var maxRoom = 0;
                temp.forEach(function(elem){
                    if (parseInt(elem[1]) > maxRoom){
                        maxRoom = parseInt(elem[1]);
                    }
                    for (var i = 1; i < elem.length - 1; i++  ){
                        totalByHours[i - 1] += parseInt(elem[i]);
                    }
                });
                var maxHour = 0;
                for (var i = 1; i < totalByHours.length; i++  ){
                    if (totalByHours[i] > maxHour){
                        maxHour = totalByHours[i];
                    }
                }
                for (var i = 1; i < totalByHours.length; i++  ){
                    if (totalByHours[i] == maxHour && maxHour != 0){
                        hourTitle = "h" + i;
                        $('#' + hourTitle).addClass('highlighted-header');
                    }
                }
                
                maxRoom = maxRoom.toString();
                response.forEach(function(elem){
                    $('#report-table-body').append(`
                        <tr>
                            <td id=` + elem[0] + `>` + elem[0] + `</td>
                            <td>` + elem[12] + `</td>
                            <td>` + elem[1] + `</td>
                            <td>` + elem[2] + `</td>
                            <td>` + elem[3] + `</td>
                            <td>` + elem[4] + `</td>
                            <td>` + elem[5] + `</td>
                            <td>` + elem[6] + `</td>
                            <td>` + elem[7] + `</td>
                            <td>` + elem[8] + `</td>
                            <td>` + elem[9] + `</td>
                            <td>` + elem[10] + `</td>
                        </tr>
                    `);
                    if (maxRoom == elem[1] && maxRoom != '0'){
                        $('#' + elem[0]).addClass('highlighted-row');
                    }
                });
                var totalArray = ['<?=__("Total")?>'];
                for (var i = 0; i < totalByHours.length; i++  ){
                    totalArray.push(totalByHours[i]);
                }
                $('#report-table-body').append(`
                    <tr>
                        <td>` + totalArray[0] + `</td>
                        <td>` + " " + `</td>
                        <td>` + totalArray[1] + `</td>
                        <td>` + totalArray[2] + `</td>
                        <td>` + totalArray[3] + `</td>
                        <td>` + totalArray[4] + `</td>
                        <td>` + totalArray[5] + `</td>
                        <td>` + totalArray[6] + `</td>
                        <td>` + totalArray[7] + `</td>
                        <td>` + totalArray[8] + `</td>
                        <td>` + totalArray[9] + `</td>
                        <td>` + totalArray[10] + `</td>
                    </tr>
                `);
            },
            error: function(jqXHR, textStatus, errorThrown){
                alert('The report could not be fetched');
                console.log(jqXHR.responseText);
            }
        });
    }

    function setHeadersEquipments(){
        $('#report-table-head').empty();
        $('#report-table-head').append(`
            <tr>
                <th scope="col"><a id="cat_sort" onclick="sortSetter('cat'); setBodyEquipments();"><?= __("Category") ?></a></th>
                <th scope="col"><?= __("Equipment Name") ?></th>
                <th scope="col"><a id="time_loans_sort" onclick="sortSetter('time_loans'); setBodyEquipments();"><?= __("Time loaned") ?></th>
                <th scope="col"><a id="late_loans_sort" onclick="sortSetter('late_loans'); setBodyEquipments();"><?= __("Late loans") ?></th>
                <th scope="col"><a id="hour_loans_sort" onclick="sortSetter('hour_loans'); setBodyEquipments();"><?= __("Overtime fee") ?></th>
                <th scope="col"><a id="available_sort" onclick="sortSetter('available'); setBodyEquipments();"><?= __("Available") ?></th>
            </tr>
        `);
    }

    function setBodyEquipments(){
        let start_date = $('#date-from').val();
        let end_date = $('#date-to').val();
        $.ajax({
            method: 'get',
            url : "/reports/equipments_report.json?start_date=" + start_date + "&end_date=" + end_date + "&sort_field=" + sort_field + "&sort_dir=" + sort_dir,
            headers: { 'X-CSRF-TOKEN': '<?=$this->getRequest()->getParam('_csrfToken');?>' },
            success: function( response ){
                $('#report-table-body').empty();

                response.forEach(function(elem){

                    if(elem.cat == "Total"){
                        $('#report-table-body').append(`
                        <tr id = \"highlight\">
                            <td>` + elem.cat + `</td>
                            <td>` + elem.equipmentName + `</td>
                            <td>` + elem.time_loans + `</td>
                            <td class=\"money\">` + elem.hour_loans + `</td>
                            <td>` + elem.late_loans + `</td>
                            <td>` + elem.available + `</td>
                        </tr>
                    `);
                    }else{
                        $('#report-table-body').append(`
                            <tr>
                                <td>` + elem.cat + `</td>
                                <td>` + elem.equipmentName + `</td>
                                <td>` + elem.time_loans + `</td>
                                <td class=\"money\">` + elem.hour_loans + `</td>
                                <td>` + elem.late_loans + `</td>
                                <td>` + elem.available + `</td>
                            </tr>
                        `);
                        if(elem.equipments.length > 0){
                            elem.equipments.forEach(function(equipment){
                                $('#report-table-body').append(`
                                    <tr>
                                        <td>` + equipment.cat + `</td>
                                        <td>` + equipment.equipmentName + `</td>
                                        <td>` + equipment.time_loans + `</td>
                                        <td class=\"money\">` + equipment.hour_loans + `</td>
                                        <td>` + equipment.late_loans + `</td>
                                        <td>` + equipment.available + `</td>
                                    </tr>
                                `);
                            });
                        }
                    }
                });
            },
            error: function(jqXHR, textStatus, errorThrown){
                alert('The report could not be fetched');
                console.log(jqXHR.responseText);
            }
        });
    }

    function generateReport(){
        if($('#date-from').val() < $('#date-to').val()){
            reportDict[$('#report-type').children("option:selected").val()]();
            $('#report-table').show();
        } else {
            alert('<?=__('"From" date cannot be higher than "To" date.')?>');
        }
    }

    function sortSetter( sort_field_param ){
        var oldHtmlFieldId = '#' + sort_field +'_sort';
        var newHtmlFieldId = '#' + sort_field_param +'_sort';
        
        $(oldHtmlFieldId).removeClass('asc');
        $(oldHtmlFieldId).removeClass('desc');
        $(newHtmlFieldId).removeClass('asc');
        $(newHtmlFieldId).removeClass('desc');

        sort_dir = sort_field != sort_field_param ? "asc" : sort_dir == "asc" ? "desc" : "asc";
        sort_field = sort_field_param;

        $(newHtmlFieldId).addClass(sort_dir);
    }

    $('document').ready(function(){
        $(".datepicker").datepicker({
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
			changeYear: true,
            onSelect: function(dateText,inst) {
                $('#preset-dates').val('custom');
                $('#date-from').datepicker('option', 'maxDate', $('#date-to').val());
                $('#date-to').datepicker('option', 'minDate', $('#date-from').val());
            }
        });

        $('#preset-dates').on('change', function(){
            let preset = $('#preset-dates').children("option:selected").val();
            
            let today = new Date();
            let start = new Date();
            let end = new Date();

            let setDate = true;

            switch(preset){
                case 'thisyear':
                    start.setFullYear(today.getFullYear());
                    start.setMonth(0);
                    start.setDate(1);
                    
                    end.setFullYear(today.getFullYear() + 1);
                    end.setMonth(0);
                    end.setDate(1);
                break;
                case 'lastyear':
                    start.setFullYear(today.getFullYear() - 1);
                    start.setMonth(0);
                    start.setDate(1);
                    
                    end.setFullYear(today.getFullYear());
                    end.setMonth(0);
                    end.setDate(1);
                break;

                case 'thismonth':
                    start.setFullYear(today.getFullYear());
                    start.setMonth(today.getMonth());
                    start.setDate(1);
                    
                    end.setFullYear(today.getFullYear());
                    end.setMonth(today.getMonth() + 1);
                    end.setDate(1);
                break;
                case 'lastmonth':
                    start.setFullYear(today.getFullYear());
                    start.setMonth(today.getMonth() - 1);
                    start.setDate(1);
                    
                    end.setFullYear(today.getFullYear());
                    end.setMonth(today.getMonth());
                    end.setDate(1);
                break;

                case 'thisweek':
                    start.setFullYear(today.getFullYear());
                    start.setMonth(today.getMonth());
                    start.setDate(today.getDate() - today.getDay());
                    
                    end.setFullYear(today.getFullYear());
                    end.setMonth(today.getMonth());
                    end.setDate(today.getDate() - today.getDay() + 7);
                break;
                case 'lastweek':
                    start.setFullYear(today.getFullYear());
                    start.setMonth(today.getMonth());
                    start.setDate(today.getDate() - today.getDay() -7);
                    
                    end.setFullYear(today.getFullYear());
                    end.setMonth(today.getMonth());
                    end.setDate(today.getDate() - today.getDay());
                break;
                
                case 'custom':
                    setDate = false;
                break;
            }
            if(setDate){
                $('#date-from').datepicker("setDate", start);
                $('#date-to').datepicker("setDate", end);
            }
        });
        $('#preset-dates').change();

        $('#date-from').datepicker('option', 'maxDate', $('#date-to').val());
        $('#date-to').datepicker('option', 'minDate', $('#date-from').val());
    });
</script>