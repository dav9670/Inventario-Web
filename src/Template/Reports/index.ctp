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
        let start_date = $('#date-from').datepicker('option', 'dateFormat', 'yy-mm-dd').val();
        let end_date = $('#date-to').datepicker('option', 'dateFormat', 'yy-mm-dd').val();
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
        let start_date = $('#date-from').datepicker('option', 'dateFormat', 'yy-mm-dd').val();
        let end_date = $('#date-to').datepicker('option', 'dateFormat', 'yy-mm-dd').val();
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
                            <td>` + elem.licence + `</td>
                            <td><img src='/img/` + (elem.used ? "good" : "bad") + `.png' alt='Available' width=20 height=20></td>
                            <td><img src='/img/` + (elem.expired ? "bad" : "good") + `.png' alt='Available' width=20 height=20></td>
                            <td>` + elem.uses + `</td>
                            <td>` + elem.percent_used + `</td>
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
            onSelect: function(date) {
                $('#preset-dates').val('custom');
            }
        });

        $('#preset-dates').on('change', function(){
            let preset = $('#preset-dates').children("option:selected").val();
            let today = new Date();
            let target = new Date();
            switch(preset){
                case 'thisyear':
                    target.setFullYear(today.getFullYear() + 1);
                    
                    $('#date-from').datepicker("setDate", today);
                    $('#date-to').datepicker("setDate", target);
                break;
                case 'lastyear':
                    target.setFullYear(today.getFullYear() - 1);
                    
                    $('#date-from').datepicker("setDate", target);
                    $('#date-to').datepicker("setDate", today);
                break;

                case 'thismonth':
                    target.setMonth(today.getMonth() + 1);
                    
                    $('#date-from').datepicker("setDate", today);
                    $('#date-to').datepicker("setDate", target);
                break;
                case 'lastmonth':
                    target.setMonth(today.getMonth() - 1);
                    
                    $('#date-from').datepicker("setDate", target);
                    $('#date-to').datepicker("setDate", today);
                break;

                case 'thisweek':
                    target.setDate(today.getDate() + 7);
                    
                    $('#date-from').datepicker("setDate", today);
                    $('#date-to').datepicker("setDate", target);
                break;
                case 'lastweek':
                    target.setDate(today.getDate() - 7);
                    
                    $('#date-from').datepicker("setDate", target);
                    $('#date-to').datepicker("setDate", today);
                break;
                
                case 'custom':
                    //$('#date-from').datepicker("setDate", null);
                    //$('#date-to').datepicker("setDate", null);
                break;
            } 
        });
        $('#preset-dates').change();
    });
</script>