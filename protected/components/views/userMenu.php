<ul id="menu">
    <li><?php echo CHtml::link('На главную',array(Yii::app()->createUrl('site'))); ?></li>
    <li><?php echo CHtml::link('О проетке',array('about')); ?></li>
    <li><?php echo CHtml::link('Заказы',array('booking/bookingstatus')); ?></li>
</ul>