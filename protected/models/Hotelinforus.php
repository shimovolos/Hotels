<?php

/**
 * This is the model class for table "hotelinforus".
 *
 * The followings are the available columns in table 'hotelinforus':
 * @property string $hotelCode
 * @property string $hotelName
 * @property string $hotelAddress
 * @property string $hotelDescription
 * @property string $hotelAmenities
 * @property string $roomAmenities
 */
class Hotelinforus extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Hotelinforus the static model class
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
		return 'hotelinforus';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('hotelCode, hotelName, hotelAddress, hotelDescription, hotelAmenities, roomAmenities', 'required'),
			array('hotelCode, hotelName, hotelAddress, hotelDescription, hotelAmenities, roomAmenities', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('hotelCode, hotelName, hotelAddress, hotelDescription, hotelAmenities, roomAmenities', 'safe', 'on'=>'search'),
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
            'list'=>array(self::BELONGS_TO,'Hotelslist','HotelCode'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'hotelCode' => 'Hotel Code',
			'hotelName' => 'Hotel Name',
			'hotelAddress' => 'Hotel Address',
			'hotelDescription' => 'Hotel Description',
			'hotelAmenities' => 'Hotel Amenities',
			'roomAmenities' => 'Room Amenities',
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

		$criteria->compare('hotelCode',$this->hotelCode,true);
		$criteria->compare('hotelName',$this->hotelName,true);
		$criteria->compare('hotelAddress',$this->hotelAddress,true);
		$criteria->compare('hotelDescription',$this->hotelDescription,true);
		$criteria->compare('hotelAmenities',$this->hotelAmenities,true);
		$criteria->compare('roomAmenities',$this->roomAmenities,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}