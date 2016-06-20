<?php
/**
 * контроллер редактирования сайта
 *
 * @author Sergey Kozin
 * @package Controllers
 */
namespace ssoft\livecontent\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use ssoft\livecontent\models\ContentData;
use ssoft\livecontent\models\DynamicModel;
use ssoft\livecontent\Content;
use yii\helpers\Json;

class EditcontentController extends Controller {

	public $layout=false;

	public $module;

	public function behaviors(){
        return [
        	'access' => [
                'class' => AccessControl::className(), 
                'rules' => $this->module->accessRules
            ],
        ];
    }

	public function actionEdit(){
		$post = Yii::$app->request->post();
		$action = $post['content'];
		if ($post!=''){
			Yii::$app->runAction('livecontent/editcontent/'.$action);
		}		
	}

	// text actions
	public function actionText() {
		$post = Yii::$app->request->post();
		$model=ContentData::place($post['place']);
		if (!isset($model)){
			$model = new ContentData;
			$model->place = $post['place'];
		}
		echo $this->render('text',['model' => $model]);
	}

	public function actionTextsave() {
		$return = [
			'class' => 'danger',
			'msg' => Yii::t('app','Saving error'),
		];
		$post = Yii::$app->request->post();

		if(isset($post['ContentData'])){
			$data = $post['ContentData'];
			$model = ContentData::place($data['place']);
			if (!isset($model)){
				$model = new ContentData;			
				$model->place = $data['place'];
				$model->content = $data['content'];
			}else{
				$model->content = $data['content'];
			}
		}else{
			$model = new ContentData;
		}
		$model->type = 'text';

		if ($model->validate() && $model->save()){			
			Content::Set($model->place, $model->content);
			$return = [
				'class' => 'success',
				'msg' => Yii::t('app','Text is saved'),
			];
		}else{
			$return = [
				'class' => 'danger',
				'msg' => Yii::t('app','Saving errors').': '.self::arrayToStr($model->getErrors()),
			];
		}		
		echo JSON::encode($return);
	}

	// textarea actions
	public function actionTextarea() {
		$post = Yii::$app->request->post();
		$model=ContentData::place($post['place']);
		if (!isset($model)){
			$model = new ContentData;
			$model->place = $post['place'];
		}
		echo $this->render('textarea',['model' => $model]);
	}

	public function actionTextareasave() {
		$return = [
			'class' => 'danger',
			'msg' => Yii::t('app','Saving error'),
		];
		$post = Yii::$app->request->post();

		if(isset($post['ContentData'])){
			$data = $post['ContentData'];
			$model = ContentData::place($data['place']);
			if (!isset($model)){
				$model = new ContentData;			
				$model->place = $data['place'];
				$model->content = $data['content'];
			}else{
				$model->content = $data['content'];
			}
		}else{
			$model = new ContentData;
		}
		$model->type = 'textarea';

		if ($model->validate() && $model->save()){			
			Content::Set($model->place, $model->content);
			$return = [
				'class' => 'success',
				'msg' => Yii::t('app','Text is saved'),
			];
		}else{
			$return = [
				'class' => 'danger',
				'msg' => Yii::t('app','Saving errors').': '.self::arrayToStr($model->getErrors()),
			];
		}		
		echo JSON::encode($return);
	}

	// html actions
	public function actionHtml() {
		$post = Yii::$app->request->post();
		$model=ContentData::place($post['place']);
		if (!isset($model)){
			$model = new ContentData;
			$model->place = $post['place'];
		}
		echo $this->render('html',['model' => $model]);
	}

	public function actionHtmlsave() {
		$return = [
			'class' => 'danger',
			'msg' => Yii::t('app','Saving error'),
		];
		$post = Yii::$app->request->post();		
		if(isset($post['ContentData'])){
			$data = $post['ContentData'];			
			$model = ContentData::place($data['place']);
			if (!isset($model)){
				$model = new ContentData;			
				$model->place = $data['place'];
				$model->content = $data['content'];
			}else{
				$model->content = $data['content'];
			}
		}else{
			$model = new ContentData;
		}
		$model->type = 'html';

		if ($model->validate() && $model->save()){			
			Content::Set($model->place, $model->content);
			$return = [
				'class' => 'success',
				'msg' => Yii::t('app','Text is saved'),
			];
		}else{
			$return = [
				'class' => 'danger',
				'msg' => Yii::t('app','Saving errors').': '.self::arrayToStr($model->getErrors()),
			];
		}		
		echo JSON::encode($return);
	}

	// block actions
	public function actionBlock() {
		$post = Yii::$app->request->post();
		$model=ContentData::place($post['place']);
		if (!isset($model)){
			$model = new ContentData;
			$model->place = $post['place'];
		}

		$blockData = $post['data-block'];
		$data = json_decode(base64_decode($blockData));
		$dataModel = $this->createContentFormModel($data);

		echo $this->render(
			'block',
			[
				'model' => $model,
				'dataModel' => $dataModel,				
				'data' => $data,
				'blockData' => $blockData
			]
		);
	}

	private function createContentFormModel($data){
		$modelFields = [];
		$requiredFields = [];
		$attributeLabels = [];		
		foreach($data->rules as $key => $item){
			if ($item->type !='editcontrol'){				
				$modelFields[$key] = $item->value;
				$attributeLabels[$key]=$item->name;
				if (isset($item->required) && $item->required ==1){
					$requiredFields[]=$key;
				}
			}
		}

		$model = new DynamicModel($modelFields);
		$model->setAttributeLabels($attributeLabels);

		if (!empty($requiredFields)){
			$model->addRule($requiredFields, 'required');
		}
		return $model;
	}

	public function actionBlocksave() {
		$return = [
			'class' => 'danger',
			'msg' => Yii::t('app','Saving error'),
		];

		$post = Yii::$app->request->post();

		if(isset($post['ContentData'])){
			$contentData = $post['ContentData'];			
			$model = ContentData::place($contentData['place']);
			if (!isset($model)){
				$model = new ContentData;			
				$model->place = $contentData['place'];			
			}
			$model->content= isset($post['DynamicModel']) ? serialize($post['DynamicModel']) : '';
		}else{
			$model = new ContentData;
		}
		$model->type = 'block';

		$blockData = $post['data-block'];
		$data = json_decode(base64_decode($blockData));
		$dataModel = $this->createContentFormModel($data);
		foreach($post['DynamicModel'] as $key =>$value){
			$dataModel->$key = $value;
		}
		if ($model->validate() && $dataModel->validate() && $model->save()){			
			Content::Set($model->place, $model->content);
			$return = array(
				'class' => 'success',
				'msg' => Yii::t('app','Data is saved'),
			);
		}else{			
			$errors=$dataModel->getErrors();			
			$return = array(
				'class' => 'danger',
				'msg' => Yii::t('app','Saving error'),
				'error_fields' => array_keys($errors)
			);
		}		
		echo JSON::encode($return);
	}

	/**
	 * преобразование массива в строку
	 * 
	 * @param  array 	$array массив данных
	 * @param  string $sep   разделитель элементов
	 * @return string
	 */
	public static function arrayToStr($array, $sep = '; ') {
		$return = [];
		foreach ($array as $key => $value) {			
			if(!is_array($value)){
				$return[] = $value;
			} else {
				$return[] = self::arrayToStr($value, $sep);
			}
		}
		return implode($sep, $return);
	}
}