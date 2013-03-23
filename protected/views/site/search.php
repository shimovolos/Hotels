<?php
Yii::app()->getClientScript()->registerCoreScript( 'jquery.ui' );
registerCss('/public/css/jquery.qtip.min.css');
registerCss('/public/css/jquery.ui.min.css');
registerScript('/public/js/chosen.jquery.js');
registerScript("/public/js/search_form.js");
registerScript('/public/js/jquery.qtip.min.js');
registerScript('/public/js/jquery.validate.min.js');
registerScript('/public/js/messages_ru.js');
registerCss('/public/css/chosen.css');
unset(Yii::app()->session['adv_param']);
unset(Yii::app()->session['responseData']);
?>
<script type="text/javascript">
    $(document).ready(function(){
        var form = $('#search');
        function refreshSelects(){
            var selects = form.find('select');
            selects.chosen();
            selects.unbind('change').bind('change',function(){
                var selected = $(this).find('option').eq(this.selectedIndex);
                $('#city_id').attr('value', selected.attr('value'));
                $('#search_city').attr('value', selected.text());
                var connection = selected.data('connection');
                selected.closest('#search tr').nextAll().remove();
                if(connection){
                    fetchSelect(connection);
                }
            });
        }
        var working = false;
        function fetchSelect(val){
            if(working){
                return false;
            }
            working = true;
            $.getJSON('<?=Yii::app()->createUrl('site/autocomplete')?>',{key:val},function(r){
                var connection, options = '';
                $.each(r.items,function(k,v){
                    connection = '';
                    if(v){
                        connection = 'data-connection="'+v+'"';
                    }
                    if(k.search(';')!=-1){
                        var data = k.split(';');
                        options+= '<option value="'+data[1]+'" '+connection+'>'+data[0]+'</option>';
                    }
                    else{
                        options+= '<option value="'+k+'" '+connection+'>'+k+'</option>';
                    }
                });
                if(r.defaultText){
                    options = '<option></option>'+options;
                }
                $('<tr><td>\
				<p>'+r.title+'</p>\
				<select style="width: 260px" data-placeholder="'+r.defaultText+'">\
					'+ options +'\
				</select>\
				<span class="divider"></span>\
			</td></tr>').appendTo(form);
                refreshSelects();
                working = false;
            });
        }
        $('#preloader').ajaxStart(function(){
            $(this).show();
        }).ajaxStop(function(){
                $(this).hide();
            });
        fetchSelect('countrySelect');
    });

</script>
<div id="info">
    Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusamus adipisci aspernatur autem eum facere illum in, iure mollitia nobis obcaecati, officiis pariatur perferendis placeat quis recusandae repellendus, reprehenderit sapiente sit ut voluptatum? Aspernatur, blanditiis culpa ea eos explicabo mollitia natus omnis quibusdam suscipit temporibus! Alias at autem beatae consequatur delectus distinctio illo labore libero, nemo non sint tempore vel, vero? Deleniti enim laboriosam perferendis quibusdam vero. Accusantium at dolorum laboriosam? Alias aspernatur aut beatae culpa cum cupiditate eligendi excepturi illum impedit ipsa ipsam itaque labore laborum, minus nam natus, quia quis, quod rerum sed sequi similique suscipit velit vero voluptate.
</div>
<form name=search_form id=search_form action='<?php echo baseUrl() ?>/site/hotels' method='post'>
    <table id='search'>
    </table>
    <div id="preloader"></div>
    <table>
        <tr>
            <td>
                <input type="hidden" name="param[city_id]" id="city_id"/>
                <input type="hidden" name="param[search_city]" id="search_city"/>
            </td>
        </tr>
        <tr>
            <td>
                <input type="text" class="date_picker" style="width: 114px" name="param[coming_date]" id="coming_date" autocomplete="off"
                       value placeholder="прибытие"/> -
                <input type="text" class="date_picker" style="width: 114px" name="param[leaving_date]" id="leaving_date" autocomplete="off"
                       value placeholder="отъезд"/>
            </td>
        </tr>
        <tr>
            <td>
                <input type="text" name="param[adult_paxes]" autocomplete="off" value placeholder="взрослых" style="width: 114px"/>
                <input type="checkbox" name="param[is_child]" id="is_child" onchange="hide_block()"/><label>&nbsp c детьми</label><br/>
            </td>
        </tr>
        <tr id="add_child">
            <td>
                <input type="text" name="param[children_paxes]" id="children_paxes" autocomplete="off" style="width: 114px" value
                       placeholder="детей"/><br/>
            </td>
        </tr>
        <tr id="children_age">
            <td id="container">
            </td>
        </tr>
        <tr>
            <td>
                <div id="button_wrap">
                    <input type=submit name=search value="Поехали!"/>
                </div>
            </td>
        </tr>
    </table>
</form>
<!--<div id='load'>-->
<!--    <img class="loader"  src='--><?// echo baseUrl().'/public/images/loader.gif';?><!--'/>-->
<!--</div>-->