<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
?>
<div id='flash' hidden></div>
<div class="users form large-12 medium-11 columns content">
    <?= $this->Form->create($user, ['id' => 'ajouter', 'type' => 'file']) ?>
    <fieldset>
        <legend><?= __('Add User') ?></legend>
        <div class="left twothirds-width">
            <?php
                echo $this->Form->control('email');
                echo $this->Form->control('password');
                echo $this->Form->select('admin_status', ['user', 'admin'], ['id' => 'admin']);
            ?>
        </div>
        <div class="right third-width">
            <?php echo $this->Form->control('image', ['type' => 'file', 'accept'=> 'image/*', 'onchange' => 'loadFile(event)']); ?>
            <img id='output' <?php if($user->image != null) { echo "src='data:image/png;base64," . $user->image . "'"; } ?>/>
        </div>
        <div style="clear: both;"></div>
    </fieldset>
    <button type="button" id="saveButton" class='right editdone' onClick='popUp()'><?=__('Save')?></button>
</div>  

<script>
    function loadFile(event) {
        $('#output').attr('src', URL.createObjectURL(event.target.files[0])); 
    }

    function popUp(){

        if ($('#admin').val() == 1){
            
            var password = prompt("Please enter your password to add an admin", "");
            var name = "<?php echo $this->request->getSession()->read('Auth.User.email'); ?>";
            $.ajax({
                method: 'post',
                url : "/users/verify.json",
                data: {email:name, password:password},
                beforeSend: function (xhr) { // Add this line
                    xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
                },
                success: function( response ){
                    if(response){
                        $('#ajouter').submit();
                    }else{
                        showFlash("<strong>Alert:</strong> Wrong password");
                    }
                },
                error: function(jqXHR, textStatus, errorThrown){
                    alert("Failed");
                    console.log(jqXHR.responseText);
                }
            });
        }else{
            $('#ajouter').submit();
        }
    }

    $('.flash-message:first').slideDown(function() {
        setTimeout(function() {
            $('.flash-message:first').slideUp();
        }, 5000);
    });

    function showFlash (message) {
        jQuery('#flash').html(message);
        jQuery('#flash').toggleClass('cssClassHere');
        jQuery('#flash').slideDown('slow');
        jQuery('#flash').click(function () { $('#flash').toggle('highlight') });
        setTimeout(function() {
            $('#flash').slideUp();
            }, 5000);
        };

</script>