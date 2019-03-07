<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User[]|\Cake\Collection\CollectionInterface $users
 */
?>

<div class="users index large-12 medium-11 columns content">
    
    <div class="left">
        <h3><?= __('Users') ?></h3>
    </div>

    <div style="clear: both;"></div>

    <div class="search-container">
        <a href="/users/add">
            <img class="plus" src="/img/plus.png" alt="Plus">
        </a>

        <div class="search-bar">
            <label for="search"><?= __('Search') ?></label>
            <input type="text" name="search" id="search">
        </div>
    </div>

    <br>
    <br>
    <br>

    <br>
    <div class="tab">
        <button id='table_activated_button' class="tablinks active" onclick="show_table('table_activated')"><?= __("Activated") ?></button>
        <button id='table_archived_button' class="tablinks" onclick="show_table('table_archived')"><?= __("Archived") ?></button>
    </div>
    <div class="tabcontent">
        <table cellpadding="0" cellspacing="0">
            <thead>
                <tr>
                    <th scope="col"></th>
                    <th scope="col"><a id='email_sort'><?= __("Email Adress") ?></a></th>
                    <th scope="col"><a id='admin_sort'><?= __("Admin Status") ?></a></th>
                    
                </tr>
            </thead>
            <tbody id="table_activated">
            </tbody>
            <tbody id="table_archived" hidden="hidden">
            </tbody>
        </table>
    </div>
</div>

<script>
    var sort_field = "id";
    var sort_dir = "asc";

    var current_table = "table_activated";

    function searchUsers( keyword ){
        var data = keyword;


        $.ajax({
                method: 'get',
                url : "/users/search.json",
                data: {keyword:data, sort_field:sort_field, sort_dir:sort_dir},
                success: function( response ){
                    
                    for(var i=0; i<2; i++){
                        var table_name = "";
                        var array_name = "";
                        if(i == 0){
                            table_name = "table_activated";
                            array_name = "users";
                        } else if(i == 1){
                            table_name = "table_archived";
                            array_name = "archivedUsers";
                        }
                        var table = $("#" + table_name);
                        table.empty();

                        usersArray = response[array_name];
                        $.each(usersArray, function(idx, elem){


                            table.append(`
                                <tr>
                                    <td><a href='/users/` + elem.id + `'><img src='data:image/png;base64,` + elem.image + `' alt='` + elem.email + ` ` + elem.password + `' width=100/></a></td>
                                    <td><a href='/users/` + elem.id + `'>` + elem.email + `</a></td>
                                    <td><a href='/users/` + elem.id + `'>` + elem.admin_status + `</a></td>
                                </tr>
                            `);
                        });
                    }
                    
                },
                error: function(jqXHR, textStatus, errorThrown){
                    alert("Failed to fetch users");
                    console.log(jqXHR.responseText);
                }
        });
    };

    function show_table(table_name){
        $('#' + current_table).hide();
        $('#' + current_table + '_button').removeClass('active');
        current_table = table_name;
        $('#' + current_table).show();
        $('#' + current_table + '_button').addClass('active');
    }

    function sort_setter( sort_field_param ){
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
         $('#search').keyup(function(){
            var searchkey = $(this).val();
            searchUsers( searchkey );
         });

         $('#email_sort').click( function(e) {
            sort_setter('email');
            $('#search').keyup();
         });
         $('#password_sort').click( function(e) {
            sort_setter('password');
            $('#search').keyup();
         });
         $('#id_sort').click( function(e) {
            sort_setter('id');
            $('#search').keyup();
         });
         $('#admin_sort').click( function(e) {
            sort_setter('admin_status');
            $('#search').keyup();
         });
        
         $('#search').keyup();
    });

</script>
