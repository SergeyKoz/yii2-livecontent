<?php
use yii\helpers\Url;
use ssoft\livecontent\ContentAsset;

$asset = ContentAsset::register($this);
?>
<style>
	span[content] {		
		border-style:none;
		display: inline;		
	}

	i.edit-element{
		font-size: 11px;
		position:relative;
		left:2px;
		top:-12px;
		display: inline;
		cursor: pointer;
		color: black;
        z-index: 1000;
	}	
</style>
<div id="editContentDialog" class="modal fade" role="dialog" dialog-url="<?=Url::to(['livecontent/editcontent/edit'])?>">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?= \Yii::t('app','Edit content');?></h4>
            </div>
            <div class="modal-body">
                <?= \Yii::t('app','Download...');?>
            </div>
            <div class="modal-footer" id='editContentDialogButtons'>
                <button class="btn btn-default" data-dismiss="modal" aria-hidden="true"><?= \Yii::t('app','Close');?></button>           
            </div>
        </div>

    </div>
</div>
