<?
use yii\widgets\ActiveForm;
use yii\helpers\Url;
//use app\widgets\CKEditor;
use dosamigos\ckeditor\CKEditor;

$this->beginPage();
$this->beginBody();

$form = ActiveForm::begin([
   'options' => ['id'=>'content-edit-form', 'class' => 'form-horizontal', 'role' => 'form'],
]);

$inputOptions = [
    'labelOptions' => ['class' =>'col-sm-1 control-label'],
    'template' => "{label}\n<div class=\"col-sm-11\">{input}\n{hint}\n{error}</div>",
];
?>

<?= $form->field($model, 'place')->hiddenInput()->label(false);?>
<div id="editStatus" class="hide2 box-body"></div>
   <?= $form->field($model, 'content', $inputOptions)->widget(CKEditor::className(), Yii::$app->getModule('livecontent')->editorOptions) ?>

<?php ActiveForm::end(); ?>
<?php $this->endBody() ?>
<?php $this->endPage() ?>

<script type="text/javascript">
   CKEDITOR.on('instanceReady', function(){      
      CKEDITOR.instances['contentdata-content'].resize('100%', 300);        
   })

   $("#content-edit-form").submit(function(event) {
      event.preventDefault();      
      $("#content-edit-form #editStatus").hide();
      $("#contentdata-content").val(CKEDITOR.instances['contentdata-content'].getData());      
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
                  container.html(CKEDITOR.instances['contentdata-content'].getData());
                  resetEdit($(this), container);                  
               });
            }
         }
      });      
   });

   $(document).ready(function(){
      $('#editContentDialog .modal-dialog').css({'width':1000});
      $('<button/>', {
         id: 'content_save_button',
         class: 'btn btn-primary',
         type: 'submit',
         form: 'content-edit-form',
         text: '<?= \Yii::t('app','Save');?>'
      }).appendTo('#editContentDialogButtons');         
   });
</script>
