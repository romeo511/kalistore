var lang={"dir":"rtl", "align":"right"};

$('#login').submit(function(e){
    e.preventDefault();
    
    var errors = 0;
    
    if ($('input[name="username"]').val() == '') {
        errors++;
        
        $('input[name="username"]').notify(
            "قم بإدخال اسم المستخدم.", 
            { position:"top "+lang.align,className: 'error' }
        );
    }
    
    if ($('input[name="pwd"]').val() == '') {
        errors++;
        
        $('input[name="pwd"]').notify(
            "قم بإدخال كلمة المرور.", 
            { position:"top "+lang.align,className: 'error' }
        );
    }
    
    if (errors == 0) {
        $.post('login.php',{
            username: $('input[name="username"]').val(),
            pwd: $('input[name="pwd"]').val(),
            action: 'Login'
        },function(result,status) {
            if (result == 'error') {
                $('#login .ie').removeClass('hidden');
                
            }else{
                $('#login .ie').addClass('hidden');
                $('#login .id').removeClass('hidden');
                
                setTimeout(
                function() {
                    $('#login').slideToggle(400);
                    $('#upload').removeClass('hidden');
                    //$('#upload').slideToggle(400);                    
                }, 2000);
            }
        });
    }            
});

$('.logout').click(function(e){
    e.preventDefault();
    
    $.post('login.php',{
        username: '',
        pwd: '',
        action: 'Logout'
    },function(result,status) {
        $('#login .ie').addClass('hidden');
        $('#login .id').addClass('hidden');
        
        $('#login').slideToggle(400);
        $('#upload').addClass('hidden');
    });    
});

$('.app').click(function(){
    $('input[name="file"]').click();
});
$('.image').click(function(){
    $('input[name="image"]').click();
});

var App_file  = 0;
var App_image = 0;
var App_title = 0;
var App_desc  = 0;

$('input[name="file"]').change(function(){
    var files = $(this).prop("files");
    var file = files[0];
    
    if (file.name.split('.').pop() == "ipa") {
        App_file = 1;
        
        $('.app').removeClass('error');
        $('.app').addClass('success');
        
        if (App_image == 1 && App_title == 1 && App_desc == 1) {
            $('.btn-upload').removeClass('btn-gray');
        }
        
    }else{
        App_file = 1;
        
        $('.app').addClass('error');
        $('.app').removeClass('success');
        
        $('.btn-upload').addClass('btn-gray');
    }
});

$('input[name="image"]').change(function(){
    var files = $(this).prop("files");
    var file = files[0];
    
    if ($.inArray(file.type, ["image/jpeg","image/png","image/gif","image/bmp","image/jpg"]) != -1) {
        App_image = 1;
        
        $('.image').removeClass('error');
        $('.image').addClass('success');
        
        if (App_file == 1 && App_title == 1 && App_desc == 1) {
            $('.btn-upload').removeClass('btn-gray');
        }
        
    }else{
        App_image = 1;
        
        $('.image').addClass('error');
        $('.image').removeClass('success');
        
        $('.btn-upload').addClass('btn-gray');
    }
});

function check_title() {
    if ($('input[name="title"]').val().length > 4) {
        App_title = 1;
        
        if (App_file == 1 && App_image == 1 && App_desc == 1) {
            $('.btn-upload').removeClass('btn-gray');
        }
    
    }else{
        App_title = 0;
        $('.btn-upload').addClass('btn-gray');
    }
}

function check_desc() {
    if ($('textarea').val().length > 9) {
        App_desc = 1;
        
        if (App_file == 1 && App_image == 1 && App_title == 1) {
            $('.btn-upload').removeClass('btn-gray');
        }
    
    }else{
        App_desc = 0;
        $('.btn-upload').addClass('btn-gray');
    }
}

$('input[name="title"]').change(function(){
    check_title();
});
$('input[name="title"]').keyup(function(){
    check_title();
});

$('textarea').change(function(){
    check_desc();
});
$('textarea').keyup(function(){
    check_desc();
});

$('#upload').submit(function(e){
    e.preventDefault();
    
    if (App_file == 0) {
        $('input[name="file"]').notify(
            "اختر ملف تطبيق مناسب.", 
            { position:"top "+lang.align,className: 'error' }
        );
    }
    
    if (App_image == 0) {
        $('input[name="image"]').notify(
            "اختر صورة مناسبة للتطبيق.", 
            { position:"top "+lang.align,className: 'error' }
        );
    }
    
    if (App_file == 1 && App_image == 1) {
        check_title();
        check_desc();
    }
    
    if (App_title == 0) {
        $('input[name="title"]').notify(
            "قم بإدخال اسم التطبيق.", 
            { position:"top "+lang.align,className: 'error' }
        );
    }
    
    if (App_desc == 0) {        
        $('textarea').notify(
            "قم بكتابة وصف التطبيق.", 
            { position:"top "+lang.align,className: 'error' }
        );
    }
    
    if (App_file == 1 && App_image == 1 && App_title == 1 && App_desc == 1) {
        
        
        var formdata = new FormData();
        var files = $('input[name="file"]').prop("files");
        var file = files[0];
        formdata.append('file', file, file.name);
        
        var files2 = $('input[name="image"]').prop("files");
        var file2 = files2[0];
        formdata.append('image', file2, file2.name);
        
        formdata.append('title', $('input[name="title"]').val());
        formdata.append('desc', $("textarea").val());
        
        var ajax = new XMLHttpRequest();
        ajax.upload.addEventListener("progress", progressHandler, false);
        ajax.addEventListener("load", completeHandler, false);
        ajax.addEventListener("error", errorHandler, false);
        
        ajax.open("POST", "upload.php");
        
        ajax.send(formdata);
        
        $(".progress-bar").removeClass('hidden');
        $(".progress-bar").addClass('uploading');
        $('.progress-bar span').html('');
        
        $('#results').html('');
    }
});
function progressHandler(e) {
    var percent = Math.floor((e.loaded/e.total)*100)+"%";
    $(".progress-bar div").css('width', percent);
    $(".progress-bar span").html(percent);    
}
function completeHandler(e) { //alert(e.target.responseText);
    var response = e.target.responseText.split("|");
    
    if (response[0] == 'error') {
        $('#results').html(response[1]);
        
    }else{
        $('input[name="file"]').val('');
        $('input[name="image"]').val('');
        $('input[name="title"]').val('');
        $('textarea').val('');
        $('.app').removeClass('error');
        $('.app').removeClass('success');
        $('.image').removeClass('error');
        $('.image').removeClass('success');
        
        $('#results').html(response[1]);
    }
    
    $('.btn-upload').addClass('btn-gray');
    
    setTimeout(
    function() {
        $(".progress-bar").removeClass('uploading');
        $(".progress-bar").addClass('hidden');
        $(".progress-bar div").css('width', '0px');
        
    }, 2000);
}
function errorHandler(e) {
    $(".btn-upload").notify(
        "Upload Failed", 
        { position:"top "+lang.align,className: 'error' }
    );
    
    $('.btn-upload').addClass('btn-gray');
    
    setTimeout(
    function() {
        $(".progress-bar").removeClass('uploading');
        $(".progress-bar").addClass('hidden');
        $(".progress-bar div").css('width', '0px');
        
    }, 2000);
}