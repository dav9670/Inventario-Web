<?php

use Cake\I18n\I18n;

/**
 * @var \App\View\AppView $this
 */
?>
<div class="reports form large-12 medium-11 columns content">
    <fieldset>
        <legend><?= __('Report settings') ?></legend>
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

        <label for="report-type"><?= __('Report type') ?></label>
        <select id="report-type">
            <option value="mentors"><?= __('Mentors') ?></option>
            <option value="rooms"><?= __('Rooms') ?></option>
            <option value="licences"><?= __('Licences') ?></option>
            <option value="equipments"><?= __('Equipments') ?></option>
        </select>

        <label for="date-from"><?= __('From') ?></label>
        <input id='date-from' type="text" class="datepicker">
        
        <label for="date-to"><?= __('To') ?></label>
        <input id='date-to' type="text" class="datepicker">
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
                response.forEach(function(elem){
                    $('#report-table-body').append(`
                        <tr>
                            <td>` + elem.email + `</td>
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
                <th scope="col"><a id="total_sort" onclick="sortSetter('total'); setBodyRooms();"><?= __("Total") ?></a></th>
                <th id="h1" scope="col">08h - 09h</a></th>
                <th id="h2" scope="col">09h - 10h</a></th>
                <th id="h3" scope="col">10h - 11h</a></th>
                <th id="h4" scope="col">11h - 12h</a></th>
                <th id="h5"  scope="col">12h - 13h</a></th>
                <th id="h5"  scope="col">13h - 14h</a></th>
                <th id="h6"  scope="col">14h - 15h</a></th>
                <th id="h7"  scope="col">15h - 16h</a></th>
                <th id="h8"  scope="col">16h - 17h</a></th>
                <th id="h9"  scope="col">17h - 18h</a></th>
                <th id="h10"  scope="col">18h - 19h</a></th>
                <th id="h11"  scope="col">19h - 20h</a></th>
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
                var totalByHours = [0,0,0,0,0,0,0,0,0,0,0,0];
                var temp = response;
                temp.forEach(function(elem){
                    for (var i = 2; i < elem.length - 1; i++  ){
                        totalByHours[i - 2] += parseInt(elem[i]);
                    }
                });
                var maxHour = 0;
                for (var i = 0; i < totalByHours.length; i++  ){
                    if (totalByHours[i] > maxHour){
                        maxHour = totalByHours[i];
                    }
                }
                for (var i = 0; i < totalByHours.length; i++  ){
                    if (totalByHours[i] == maxHour){
                        hourTitle = "h" + i + 1;
                        $('#' + hourTitle).addClass('highlighted-header');
                    }
                }
                response.forEach(function(elem){
                    $('#report-table-body').append(`
                        <tr>
                            <td>` + elem[0] + `</td>
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
                            <td>` + elem[11] + `</td>
                            <td>` + elem[12] + `</td>
                            <td>` + elem[13] + `</td>
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

    function generateReport(){
        reportDict[$('#report-type').children("option:selected").val()]();
        $('#report-table').show();
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
            onSelect: function(date) {
                $('#preset-dates').val('custom');
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
    });
</script>