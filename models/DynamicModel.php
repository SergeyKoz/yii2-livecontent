<?php
/**
 * Model class for dynamic form in live editing of form
 * 
 * @author Sergey Kozin
 * @package Forms
 * @subpackage Site
 */
namespace ssoft\livecontent\models;

class DynamicModel extends \yii\base\DynamicModel {

	private $_attributeLabels = array();

	public function setAttributeLabels($labels){
		$this->_attributeLabels = $labels;
	}  

	public function attributeLabels(){
		return $this->_attributeLabels;	
	}  
}
