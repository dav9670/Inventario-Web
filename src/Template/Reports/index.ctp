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
        </select>

        <label for="sort-order"><?= __('Sort by') ?></label>
        <select id="sort-order">
            <option value="name"><?= __('Name') ?></option>
            <option value="popularity"><?= __('Popularity') ?></option>
        </select>

        <label for="report-type"><?= __('Report type') ?></label>
        <select id="report-type">
            <option value="mentors"><?= __('Mentors') ?></option>
            <option value="rooms"><?= __('Rooms') ?></option>
            <option value="licences"><?= __('Licences') ?></option>
            <option value="equipments"><?= __('Equipments') ?></option>
        </select>

        <label for="date-from"><?= __('From') ?></label>
        <input type="text" class="datepicker">
        
        <label for="date-to"><?= __('To') ?></label>
        <input type="text" class="datepicker">
    </fieldset>

    <button type="submit"><?= __('Generate report') ?></button>

    <table cellpadding="0" cellspacing="0">
        <thead id='report-table-head'>
            <tr>
                <th scope="col"><?= __("Insert") ?></a></th>
                <th scope="col"><?= __("Your") ?></a></th>
                <th scope="col"><?= __("Fields") ?></th>
                <th scope="col"><?= __("Here") ?></th>
                <th scope="col"><?= __("For") ?></th>
                <th scope="col"><?= __("Your") ?></th>
                <th scope="col"><?= __("Report") ?></th>
            </tr>
        </thead>
        <tbody id='report-table-body'>
        </tbody>
    </table>
</div>

<script>
    function setHeadersMentors(){
        $('#report-table-head').empty();
        $('#report-table-head').append(`
            <tr>
                <th scope="col"><?= __("Email") ?></a></th>
                <th scope="col"><?= __("Hours loaned") ?></a></th>
                <th scope="col"><?= __("Times loaned") ?></th>
            </tr>
        `);
    }

    function setBodyMentors(){
        $.ajax({
            method: 'get',
            url : "/reports/mentors_report.json?start_date=" + '2000-01-01' + "&end_date=" + '2020-01-01',
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

    function setTableMentors(){
        setHeadersMentors();
        setBodyMentors();
    }

    $('document').ready(function(){
        $(".datepicker").datepicker();
    });
</script>