<?php

/**
 * This is the model class for table "admin_menu".
 *
 * The followings are the available columns in table 'admin_menu':
 * @property integer $id
 * @property string $name
 * @property string $page_url
 * @property string $controller
 * @property integer $order
 * @property integer $id_admin_menu
 */
class AdminMenu extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return AdminMenu the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'am_admin_menu';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, page_url, controller, order, id_admin_menu', 'required'),
			array('order, id_admin_menu', 'numerical', 'integerOnly'=>true),
			array('name, page_url, controller', 'length', 'max'=>200),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, page_url, controller, order, id_admin_menu', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'page_url' => 'Page Url',
			'controller' => 'Controller',
			'order' => 'Order',
			'id_admin_menu' => 'Id Admin Menu',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('page_url',$this->page_url,true);
		$criteria->compare('controller',$this->controller,true);
		$criteria->compare('order',$this->order);
		$criteria->compare('id_admin_menu',$this->id_admin_menu);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}