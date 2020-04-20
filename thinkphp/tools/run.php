<?php
$framework_json = file_get_contents('framework.json');
$think_json = file_get_contents('think.json');
$framework_list = json_decode($framework_json, 1);
$think_list = json_decode($think_json, 1);
$download = '';
$thinks = array();
$frameworks = array();
foreach($framework_list as $item){
    $zipball_url = $item['zipball_url'];
    $tag_name = strtolower($item['tag_name']);
    $frameworks[$tag_name] = $item;
    $zipname = "../framework_{$tag_name}.zip";
    if(!file_exists($zipname) || filesize($zipname) <= 10){
        $cmd = "wget -O '$zipname' '$zipball_url'";
        $download .= "$cmd\n";
    }
}
foreach($think_list as $item){
    $zipball_url = $item['zipball_url'];
    $tag_name = strtolower($item['tag_name']);
    $thinks[$tag_name] = $item;
    $zipname = "../think_{$tag_name}.zip";
    if(!file_exists($zipname) || filesize($zipname) <= 10){
        $cmd = "wget -O '$zipname' '$zipball_url'";
        $download .= "$cmd\n";
    }
}
if($download != false){
    die($download);
}
foreach($frameworks as $framework){
    $selected_think = false;
    $v1 = trim($framework['tag_name'], 'v');
    $v1 = substr($v1, 0, 3);
    // think中查找日期最相近的版本
    foreach($think_list as $think){
        if($selected_think == false){
            $selected_think = $think;
            continue;
        }
        // 如果大版本不同则跳过
        $v2 = trim($think['tag_name'], 'v');
        $v2 = substr($v2, 0, 3);
        if($v1 != $v2)
            continue;
        // 比较日期
        if(abs(strtotime($framework['published_at']) - strtotime($think['published_at'])) < abs(strtotime($framework['published_at']) - strtotime($selected_think['published_at']))){
            $selected_think = $think;
        }
    }
    echo "Framework {$framework['tag_name']} -> Think {$selected_think['tag_name']}\n";
    // 创建文件夹
    $dir = "../{$framework['tag_name']}";
    if(!file_exists($dir)){
        mkdir($dir);
    }
    $framework_zip = "../framework_{$framework['tag_name']}.zip";
    $think_zip = "../think_{$selected_think['tag_name']}.zip";
    if(!file_exists($framework_zip)){
        die("$framework_zip not found");
    }
    if(!file_exists($think_zip)){
        die("$think_zip not found");
    }
    // 5.2及其以上版本暂不处理，需要使用composer方式安装
    if(version_compare(trim($selected_think['tag_name'], 'v'), '5.2', '>=')){
        continue;
    }else{
        $cmd = "rm -rf $dir/top-think-* \
                && rm -rf $dir/html \
                && unzip -o -d '$dir' '$framework_zip' \
                && unzip -o -d '$dir' '$think_zip' \
                && mv $dir/top-think-think-* $dir/html \
                && mv $dir/top-think-framework-* $dir/html/thinkphp \
                && cp index.php $dir/html/index.php \
                && cp docker-compose.yml $dir/docker-compose.yml";
        system($cmd);
    }
}
