<?
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\Html;
use dosamigos\ckeditor\CKEditor;

$this->beginPage();
$this->beginBody();

$form = ActiveForm::begin([
  'options' => ['id'=>'content-edit-form', 'class' => 'form-horizontal', 'role' => 'form'],
]);

$inputOptions = [
  'labelOptions' => ['class' =>'col-sm-2 control-label'],
  'template' => "{label}\n<div class=\"col-sm-10\">{input}\n{hint}\n{error}</div>",
];

$checkboxOptions = [    
    'template' => "<div class=\"col-sm-2 control-label\"></div>\n<div class=\"col-sm-10\">{input}\n{hint}\n{error}</div>",
];
?>
   <?= $form->field($model, 'place')->hiddenInput()->label(false);?>
   <?= Html::hiddenInput('data-block', $blockData);?>

   <div id="editStatus" class="hide2 box-body"></div>
   <?php foreach($data->rules as $key => $item):?>
    <?php
       if ($item->type =='text'){
          echo $form->field($dataModel, $key, $inputOptions)->textInput(['maxlength' => true]);
       }

       if ($item->type =='textarea'){          
          echo $form->field($dataModel, $key, $inputOptions)->textarea(['maxlength' => true, 'style' =>'resize: vertical;']);
       }

       if ($item->type =='html'){
           echo $form->field($dataModel, $key, $inputOptions)->widget(CKEditor::className(), Yii::$app->getModule('livecontent')->editorOptions);
       }

       if ($item->type =='select'){
          $options = [];
          foreach($item->options as $option){
             $options[$option->value]=$option->option;
          }
          echo $form->field($dataModel, $key, $inputOptions)->dropDownList($options);          
       }

       if ($item->type =='checkbox'){
          echo $form->field($dataModel, $key, $checkboxOptions)->checkbox();
       }
    ?>

   <?php endforeach;?>

<?php ActiveForm::end(); ?>
<?php $this->endBody() ?>
<?php $this->endPage() ?>


<script type="text/javascript">
  if (typeof CKEDITOR =='object'){
    CKEDITOR.on('instanceReady', function(item){
      item.editor.resize('100%', 300);
    });

    $("#content-edit-form").on('beforeValidate', function(event, messages, deferred, attribute) {      
      $.each(CKEDITOR.instances, function( index, editor ) {
        $("#"+editor.name).val(editor.getData());         
      });
    });
  }

  $("#content-edit-form").submit(function(event) {
    event.preventDefault();  
    $("#content-edit-form #editStatus").hide();
    
    var yiiActiveForm = $("#content-edit-form").data('yiiActiveForm');
    if (!yiiActiveForm.validated){
      return false;
    }
    yiiActiveForm.validated = false;

    $.ajax({
       url: '<?= Url::to(["/livecontent/editcontent/blocksave"]) ?>',
       type: 'POST',
       data: $("#content-edit-form").serialize(),
       dataType: 'JSON',         
       success: function(data) {
          $("#content-edit-form #editStatus").attr('class','alert alert-'+data.class).html(data.msg); 
          if (data.error_fields !== undefined) {
             $.each(data.error_fields, function(index, value) {                  
                $("#content-edit-form #field-row-"+value).addClass('has-error');
             });
          }
          $("#content-edit-form #editStatus").slideDown();            
          if(data.class == 'success') {
             $("#content-edit-form div[id^='field-row']").removeClass('has-error');               
             window.liveContentChanged = 1;
          }
       }
    });      
  });

   $(document).ready(function(){
      window.liveContentChanged = 0;
      $('<button/>', {
         id: 'content_save_button',
         class: 'btn btn-primary',
         type: 'submit',
         form: 'content-edit-form',
         text: '<?= \Yii::t('app','Save');?>'
      }).appendTo('#editContentDialogButtons');

      $('#editContentDialog').on('hidden.bs.modal', function () {        
         if (window.liveContentChanged == 1){
            window.location.reload();
         }
      });

      $('#editContentDialog .modal-dialog').css({'width':1000});      
      $("#content-edit-form div[id^='field-row']").removeClass('has-error');
   });
</script>
