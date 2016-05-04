$(function() {
    //layer config
    layer.config({
        path: Purl+'plugins/layer/' //layer.js所在的目录，可以是绝对目录，也可以是相对目录
    });
    $.pao = layer;
    $.pao.uri = new URI();
    //菜单选择
    $('.menu .menu-title').on('click', function(){
        $('.menu .menu-item').hide();
        $(this).next('.menu-item').show()
    });

    //列表全选
    $("#index table thead tr th input[type=checkbox], #index .btn-checkbox").on('click', function(){
        $('#index tbody input:checkbox').prop('checked', $(this).prop("checked"));
    });
    $("#btn-batch-select").on('click', function(){
        $('#index tbody input:checkbox').prop('checked',true);
    });
    $("#btn-batch-choice").on('click', function(){
        $('#index tbody input:checkbox').prop('checked','');
    });


    //公用
    //定位标签

    var tab = $.pao.uri.fragment();
    //$("#Tabs ul").idTabs(tab?tab:'index');
        if(tab != "") {
            $('#tab-'+tab+' a').click();
        }
    /*
    $("[name='status']").bootstrapSwitch({
     on: "{{e('enable')}}",
     off: "{{e('disable')}}",
     onClass: 'success',
     offClass: 'default',
     size: 'sm'
     });
     */

    //公共表单提交
    $("form").not("#search").on('submit', function(){
        $.post($(this).attr('action'), $(this).serialize() , function(json) {
            var icon = json.status ? '1' : '2';
            $.pao.alert(json.message,{icon: icon}, function(alert){
                if(icon == '1'){
                    location.reload();
                }
                $.pao.close(alert);
            });

        });
        return false;
    });

    //状态按扭
    $(".btn-status").on('click', function(){
        $.post($(this).attr('href'), {status:$(this).data('status')}, function(json){
            location.reload();

        });
        return false;
    });

    //修改按扭
    $("#index .btn-update").on('click', function(){
        var load = $.pao.load(0, {shade:[0.8 , '#fff' , true]});
        $.get($(this).attr('href'), function(json){
            $("#update form").html( $("#create fieldset").clone());
            $("#update form").find(":input").each(function() {
                switch(this.type){
                    case 'text':
                    case 'select-one':
                    case 'select-multiple':
                    case 'textarea':
                    case 'password':
                        //$(this).val(json[this.name]);
                        this.value = json[this.name];
                        break;
                    case 'checkbox':
                        if(json[this.name] || json[this.name]=='on'){
                            this.checked = true;
                        }
                        break;
                    case 'radio':
                        //console.log($("input[name='"+this.name+"'][value='"+json[this.name]+"']"));
                        //console.log(json(this.name));
                        if(this.value == json[this.name]){
                            this.click();
                        }
                        break;
                }
            });
            $("#tab-update").attr('style', 'display: block !important');
            $("#tab-update>a").click();
            $.pao.close(load);
        });
        return false;
    });

    //删除按扭
    $("#index .btn-delete").on('click', function(){
        var href = $(this).attr('href');
        $.pao.confirm($(this).data('confirm'), {icon:3,shade:[0.8 , '#fff' , true]}, function(confirm){

            $.get(href, function(json){
                if(json.status){
                    $.pao.close(confirm);
                }else{
                    $.pao.alert(json.message,{icon:2,shade:[0.8 , '#fff' , true]});
                }
                location.reload();
            });
        });
        return false;
    });

    //批量操作
    $(".btn-batch").on('click', function(){
        $.post($(this).val(), $("#index tbody form").serialize(), function(json){
            var icon = json.status ? '1' : '2';
            $.pao.alert(json.message,{icon: icon}, function(alert){
                if(icon=='1'){
                    location.reload();
                }
                $.pao.close(alert);
            });

        });
    });


});
