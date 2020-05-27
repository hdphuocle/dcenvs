<?php
//判断是否安装，修改安装包数据
if(!file_exists('./source')){
    die('Error: source not found');
}

if(!file_exists('./source/config.inc.php')){
    copy('./source/config.inc.php.dist', './source/config.inc.php');
}

//禁止错误页面自动跳转
$offline_file = './source/offline.html';
if(file_exists($offline_file)){
    $c = file_get_contents($offline_file);
    $c = str_replace('<meta http-equiv="refresh" content="10; URL=/index.php">', '', $c);
    file_put_contents($offline_file, $c);
}

// 安装文件不存在，表示已经完成安装
if(!file_exists('./source/Setup/index.php')){
    header('Location: ./source');
    exit;
}

// sysreq 自动提交表单
$file = './source/Setup/tpl/systemreq.php';
if(file_exists($file)){
    $c = file_get_contents($file);
    if(strpos($c, 'document.forms[1].submit()') === false){
        $c .= "\n?><script>document.forms[1].submit();</script>";
        file_put_contents($file, $c);
    }
}

// welcome 自动提交表单
$file = './source/Setup/tpl/welcome.php';
if(file_exists($file)){
    $c = file_get_contents($file);
    if(strpos($c, 'document.forms[0].submit()') === false){
        $c .= "\n?><script>document.forms[0].submit();</script>";
        file_put_contents($file, $c);
    }
}

// license 自动提交表单
$file = './source/Setup/tpl/license.php';
if(file_exists($file)){
    $c = file_get_contents($file);
    if(strpos($c, 'document.forms[0].submit()') === false){
        $c = str_replace('value="0" checked>', 'value="0">', $c);
        $c = str_replace('value="1">', 'value="1" checked>', $c);
        $c .= "\n?><script>document.forms[0].submit();</script>";
        file_put_contents($file, $c);
    }
}

// dbinfo 自动提交表单
$file = './source/Setup/tpl/dbinfo.php';
if(file_exists($file)){
    $c = file_get_contents($file);
    if(strpos($c, 'document.forms[0].submit()') === false){
        $c = str_replace('<?php echo( $aDB[\'dbHost\']);?>', 'db', $c);
        $c = str_replace('<?php echo( $aDB[\'dbPort\']);?>', '3306', $c);
        $c = str_replace('<?php echo( $aDB[\'dbName\']);?>', 'oxid', $c);
        $c = str_replace('<?php echo( $aDB[\'dbUser\']);?>', 'oxid', $c);
        $c = str_replace('<?php echo( $aDB[\'dbPwd\']);?>', 'oxid', $c);
        $c .= "\n?><script>document.forms[0].submit();</script>";
        file_put_contents($file, $c);
    }
}

// dbinfo 自动提交表单
$file = './source/Setup/tpl/dirsinfo.php';
if(file_exists($file)){
    $c = file_get_contents($file);
    if(strpos($c, 'document.forms[0].submit()') === false){
        $c = str_replace('<?php echo( $aAdminData[\'sLoginName\']);?>', 'demo@vulnspy.com', $c);
        $c = str_replace('type="password"', 'type="text" value="123456"', $c);
        $c .= "\n?><script>document.forms[0].submit();</script>";
        file_put_contents($file, $c);
    }
}

// finish 自动提交表单
$file = './source/Setup/tpl/finish.php';
if(file_exists($file)){
    $c = file_get_contents($file);
    if(strpos($c, 'window.location.href') === false){
        $c .= "\n?><script>window.location.href='/source/';</script>";
        file_put_contents($file, $c);
    }
}

header('Location: ./source/Setup');
exit;
