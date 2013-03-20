<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />
    <?php
        registerCss("/css/style.css");
        registerCss("/css/menu.css");
        registerScript("/js/upButton.js");
        Yii::app()->getClientScript()->registerCoreScript('jquery');
    ?>
    <title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>
<body>
<div id="total">
    <div id="main">
        <div id="header">
            <div id="logo">
                <a href="index">
                    <img src="<? echo baseUrl().'/assets/images/my_Logo.png';?>" alt="logo"/>
                </a>

            </div>
            <div id="menu">
                <?php $this->widget('UserMenu'); ?>
            </div>
        </div>
        <div id="content">
            <?php echo $content; ?>
        </div>
    </div>
    <div id="footer_space"></div>
</div>
    <div id="footer">

        &copy Copyright

    </div>
<!---->
</body>
</html>
