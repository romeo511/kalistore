<?php
include('config.php');

if (!get_magic_quotes_gpc() && $_POST) {
	$_POST = array_map('addslashes', $_POST);
}

$_POST = array_map('trim', $_POST);

extract($_POST);

$errors = 0;
$msg    = '';

if (isset($_FILES['file']['name'])) {
    $file_name = $_FILES['file']['name'];
    $file_ext  = strrchr($file_name, '.');
    $file_ext  = explode('.', $file_ext);
    $file_ext  = @strtolower($file_ext[1]);
    
    if ($file_ext != 'ipa') {
        $errors++;
        $msg .= '<div class="ie">برجاء اختيار تطبيق مناسب.</div>';
    }
    
}else{
    $msg .= '<div class="ie">برجاء اختيار تطبيق مناسب.</div>';
}

$imgExts = array('jpg','jpeg','png','bmp');
if (isset($_FILES['image']['name'])) {
    $image_name = $_FILES['image']['name'];
    $image_ext  = strrchr($image_name, '.');
    $image_ext  = explode('.', $image_ext);
    $image_ext  = @strtolower($image_ext[1]);
    
    $image = @getimagesize($_FILES['image']['tmp_name']);
    
    if (!in_array($image_ext, $imgExts) || !isset($image[0]) || $image[0] < 50) {
        $errors++;
        $msg .= '<div class="ie">صورة التطبيق غير مناسبة.</div>';       
    }
    
}else{
    $msg .= '<div class="ie">برجاء اختيار صورة مناسبة.</div>';
}

if (!isset($title[4])) {
    $errors++;
    $msg .= '<div class="ie">برجاء كتابة عنوان مناسب أكبر من 4 أحرف.</div>';    

}elseif (isset($title[150])) {
    $errors++;
    $msg .= '<div class="ie">برجاء كتابة عنوان مناسب بحد أقصى 150 حرف.</div>';    

}

if (!isset($desc[10])) {
    $errors++;
    $msg .= '<div class="ie">برجاء كتابة وصف مناسب.</div>';   
}

if ($errors == 0) {
    for ($a=0; $a<100; $a++) {
        $app_dir = strtolower(uniqid());
        
        if (!is_dir('apps/'.$app_dir)) {
        	mkdir('apps/'.$app_dir, 0777);
        	chmod('apps/'.$app_dir, 0777);
            
            $a = 1000;
        
        }else{
            $app_dir = '';
        }
    }
    
    $app_name = strtolower(uniqid());
    
    if ($app_dir != '') {
        if (!move_uploaded_file($_FILES['file']['tmp_name'], 'apps/'.$app_dir.'/'.$app_name.'.ipa')) {
            $errors++;
            $msg .= '<div class="ie">لم يتم رفع التطبيق. تأكد من التصاريح.</div>';
        }
        
        if ($errors == 0 && !move_uploaded_file($_FILES['image']['tmp_name'], 'apps/'.$app_dir.'/'.$app_name.'.'.$image_ext)) {
            $errors++;
            $msg .= '<div class="ie">لم يتم رفع صورة التطبيق. تأكد من التصاريح.</div>';
        }
        
        if ($errors == 0) {
            $filename = 'install.plist';
            $handle = fopen($filename, "r");
            $contents = fread($handle, filesize($filename));
            fclose($handle);
            
            $w = array($meta['url'].'apps/'.$app_dir.'/'.$app_name.'.ipa', $meta['url'].'apps/'.$app_dir.'/'.$app_name.'.'.$image_ext, $app_name, $title);
            $r = array('[App_URL]','[App_Image]','[App_Name]','[App_Title]');
            $contents = str_replace($r, $w, $contents);
            
            $fp = fopen('apps/'.$app_dir.'/install.plist', 'w+');
            fwrite($fp, $contents);
            fclose($fp);
            
            $msg .= '<div class="id">تم رفع التطبيق بنجاح.</div><input type="text" name="link" value="itms-services://?action=download-manifest&url='.$meta['url'].'apps/'.$app_dir.'/install.plist" readonly="readonly" onclick="javascript: this.select();" />';
        }
    }    
}

if ($errors > 0) {
    echo 'error|'.$msg;
    
}else{
    echo 'success|'.$msg;
}
?>