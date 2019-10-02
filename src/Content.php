<?php
/**
 * Live content class
 *
 * @author Sergii Kozin
 */
namespace ssoft\livecontent;

use Yii;
use yii\base\Widget;
use ssoft\livecontent\models\ContentData;

class Content extends Widget {
	
	public static function IsEdit(){
    	return Yii::$app->getModule('livecontent')->IsEdit();
    }

	static public function GetKey($id){
		$key='';
		$script = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);
		$module = Yii::$app->getModule('livecontent');
		$fileInfo = pathinfo($script[1]['file']);
		$file = $fileInfo['dirname'].'/'.$fileInfo['filename'];
		if (!$module->HasFile($file)){
			$fileID = str_replace(Yii::getAlias('@app'), '', $file);
			$fileID = str_replace(['\\'], '/', $fileID);
			$module->SetFile($file, $fileID);
		} else {			
			$fileID = $module->GetFile($file);
		}
		$key = $fileID.':'.$id;
		$module->SetDialog();
		return $key;
	}

	static public function Get($key){
		$content='';
		$module = Yii::$app->getModule('livecontent');
		if ($module->HasContent($key)){
			$content = $module->GetContent($key);
		}else{
			$content = Yii::$app->cache->get('content_'.md5($key));
			if($content===false){
				if (($model = ContentData::place($key)) !== null) {
		            $content = $model->content;
		            Yii::$app->cache->set('content_'.md5($key), $content, $module->cacheContentDuration);
		        }
		    }
		}
		return $content;
	}

	public static function Set($key, $content){
		$module = Yii::$app->getModule('livecontent');
    	$module->SetContent($key, $content);
    	Yii::$app->cache->set('content_'.md5($key), $content, $module->cacheContentDuration);
    }

	static public function __callStatic($name, $additional){		
		if ($additional[0]!=''){
			$key = self::GetKey($additional[0]);
			$content = self::Get($key);
			return self::IsEdit() && $additional[1] ? '<div><i class="edit-element glyphicon glyphicon-pencil" place=\''.$key.'\' content=\''.$name.'\'></i>' . $content . '</div>' : $content;
		}
	}

	static public function block($place, $isAllow, $blockLogic){
		$key = Content::GetKey($place);
		$data = unserialize(Content::Get($key));
		if (!is_array($data)){
			$data = [];
		}
		$templateData=[];

		$editfield = '';
		foreach($blockLogic['rules'] as $field => $rule){
			if ($rule['type']!='editcontrol'){
				$value = isset($data[$field]) && $data[$field]!='' ? $data[$field] : '';
				$templateData[$field] = $value;
				$blockLogic['rules'][$field]['value'] = $value;
			} else {
				$editfield = $field;
			}
		}

		if ($editfield!=''){
			$editcontrol = '';
			if (Content::IsEdit() && $isAllow){	
				$editcontrol = '<i class="edit-element glyphicon glyphicon-pencil" place=\''.$key.'\' content=\'block\' data-block=\''.base64_encode(json_encode($blockLogic)).'\'></i>';
			}
			$templateData[$editfield] = $editcontrol;

		}
		return self::parseBlock($templateData, $blockLogic['template']);
	}

	/**
	* Template parser 
	* 
	* Templates features
	* 	text
	* 	{title}
	* 	{title is null}Заголовок пуст{/title is null}
	* 	{title is not null}Заголовок не пуст{/title is not null}
	* 	{is_premium on}Флаг пуст{/is_premium on}
	* 	{is_premium off}Флаг не пуст{/is_premium off}
	* @param type $name
	* @return type 
	*/
	static public function parseBlock($data, $template){
		$text = $template;
		$fields= array_keys($data);

		// is not null
		$patterns = [];
		$replacements = [];
		foreach($fields as $field){
			array_push($patterns, '/\{'.$field.' is not null\}((.|\n)*?)\{\/'.$field.' is not null\}/m');
			array_push($replacements, $data[$field] != '' ? '$1' : '');
		}
		$text  = preg_replace($patterns, $replacements, $text);
		
		$patterns = [];
		$replacements = [];
		foreach($fields as $field){
			array_push($patterns, '/\{'.$field.' is null\}((.|\n)*?)\{\/'.$field.' is null\}/m');
			array_push($replacements, $data[$field] != '' ? '' : '$1');
		}
		$text  = preg_replace($patterns, $replacements, $text);

		//on
		$patterns = [];
		$replacements = [];
		foreach($fields as $field){
			array_push($patterns, '/\{'.$field.' on\}((.|\n)*?)\{\/'.$field.' on\}/m');
			array_push($replacements, $data[$field] == 1 ? '$1' : '');
		}
		$text  = preg_replace($patterns, $replacements, $text);

		//off
		$patterns = [];
		$replacements = [];
		foreach($fields as $field){
			array_push($patterns, '/\{'.$field.' off\}((.|\n)*?)\{\/'.$field.' off\}/m');
			array_push($replacements, $data[$field] != 1 ? '$1' : '');
		}
		$text  = preg_replace($patterns, $replacements, $text);

		$patterns = [];
		$replacements = [];
		foreach($fields as $field){
			array_push($patterns, '{'.$field.'}');
			array_push($replacements, $data[$field]);
		}
		$text = str_replace($patterns, $replacements, $text);

		return $text;
	}	
}