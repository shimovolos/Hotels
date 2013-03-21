<?php

/**
 * This is the model class for table "hoteldestinationsrus".
 *
 * The followings are the available columns in table 'hoteldestinationsrus':
 * @property string $DestinationId
 * @property string $Country
 * @property string $City
 * @property string $State
 */
class Hoteldestinationsrus extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Hoteldestinationsrus the static model class
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
		return 'hoteldestinationsrus';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('DestinationId, Country, City, State', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('DestinationId, Country, City, State', 'safe', 'on'=>'search'),
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
			'DestinationId' => 'Destination',
			'Country' => 'Country',
			'City' => 'City',
			'State' => 'State',
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

		$criteria->compare('DestinationId',$this->DestinationId,true);
		$criteria->compare('Country',$this->Country,true);
		$criteria->compare('City',$this->City,true);
		$criteria->compare('State',$this->State,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}