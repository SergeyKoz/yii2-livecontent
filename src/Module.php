<?php
namespace ssoft\livecontent;

use Yii;
use yii\base\Application;
use yii\base\BootstrapInterface;
use yii\web\View;

class Module extends \yii\base\Module implements BootstrapInterface
{
    public $controllerNamespace = 'ssoft\livecontent\controllers';

    public $accessRules = [
        [
            'allow' => true,
            'verbs' => ['POST']
        ],
    ];
    public $editorOptions = [
        'clientOptions'=>[
            'rows' => 6,
            'autoParagraph'=>false 
        ],
        'preset' => 'full'
    ];

    public $cacheContentDuration = 30 * 24 * 3600;

    private $_edit = false;
    private $_editDialogRender = false;
    
	private $_fileData = [];    
    private $_contentData = [];

    /**
     * @inheritdoc
     */
    public function bootstrap($app){
        if (!Yii::$app->getRequest()->getIsAjax()) {
	        $app->on(Application::EVENT_BEFORE_REQUEST, function () use ($app) {
	            $app->getView()->on(View::EVENT_END_BODY, [$this, 'renderEditDialog']);
	        });
	        $this->SetEdit(true);
    	}
    }

    public function IsEdit(){
    	return $this->_edit;
    }

    public function SetEdit($edit){
    	$this->_edit = $edit;
    }

    public function SetDialog(){
    	if (!$this->_editDialogRender){
    		$this->_editDialogRender = true;
    	}
    }

     public function HasContent($key){    	
    	return isset($this->_contentData[$key]);
    }

    public function SetContent($key, $content){
		$this->_contentData[$key] = $content;
	}

	public function GetContent($key){
		return $this->_contentData[$key];
	}

    public function HasFile($file){    	
    	return isset($this->_fileData[$file]);
    }

    public function SetFile($file, $fileID){
		$this->_fileData[$file] = $fileID;
	}

	public function GetFile($file){
		return $this->_fileData[$file];
	}

     /**
     * Renders edit dialog at the end of page body.
     *
     * @param \yii\base\Event $event
     */
    public function renderEditDialog($event){
		if ($this->_editDialogRender){
	    	echo Yii::$app->getView()->render(
	    		'@vendor/sergeykoz/yii2-livecontent/views/dialog'                
	    	);
    	}
    }
}
