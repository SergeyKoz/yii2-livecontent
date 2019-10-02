<?php

use yii\widgets\ActiveForm;
use yii\helpers\Url;

$form = ActiveForm::begin([
   'options' => ['id'=>'content-edit-form', 'class' => 'form-horizontal', 'role' => 'form'],
]);

$inputOptions = [
    'labelOptions' => ['class' =>'col-sm-2 control-label'],
    'template' => "{label}\n<div class=\"col-sm-10\">{input}\n{hint}\n{error}</div>",
];
?>
<?= $form->field($model, 'place')->hiddenInput()->label(false);?>
<div id="editStatus" class="hide2 box-body"></div>
<?= $form->field($model, 'content', $inputOptions)->textInput(['maxlength' => true]) ?>

<?php ActiveForm::end(); ?>

<script type="text/javascript">
   $("#content-edit-form").submit(function(event) {
      event.preventDefault();
      $("#content-edit-form #editStatus").hide();
      $.ajax({
         url: '<?= Url::to(["/livecontent/editcontent/htmlsave"]) ?>',
         type: 'POST',
         data: $("#content-edit-form").serialize(),
         dataType: 'JSON',         
         success: function(data) {
            $("#content-edit-form #editStatus").attr('class','alert alert-'+data.class).html(data.msg);
            $("#content-edit-form #editStatus").slideDown();
            if(data.class == 'success') {
               $('i[place="<?= $model->place;?>"]').each(function(i){
                  var container = $(this).parent();                  
                  container.html($("#contentdata-content").val());
                  resetEdit($(this), container);
               });
            }
         }
      });      
   });

   $(document).ready(function(){
      $('#editContentDialog .modal-dialog').css({'width':700});
      $('<button/>', {
         id: 'content_save_button',
         class: 'btn btn-primary',
         type: 'submit',
         form: 'content-edit-form',
         text: '<?= \Yii::t('app','Save');?>'
      }).appendTo('#editContentDialogButtons');         
   });
</script>