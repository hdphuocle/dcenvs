<?php
if(file_exists('tags.json')){
    $tags = file_get_contents('tags.json');
}else{
    $tags = file_get_contents('https://registry.hub.docker.com/v1/repositories/wordpress/tags');
    file_put_contents('tags.json', $tags);
}
if($tags == false)
    die('Failed to fetch tags');
$tags = json_decode($tags, 1);
$dc = file_get_contents('docker-compose.yml');
foreach($tags as $tag){
    if(strpos($tag['name'], 'cli') !== false){
        continue;
    }
    $name =  'wordpress:'.$tag['name'];
    $mydc = str_replace('{tags}', $name, $dc);
    $myrd = '# Wordpress '.$tag['name'].'

<a href="https://www.vsplate.com/?docker-compose=https://github.com/vsplate/dcenvs/wordpress/'.$tag['name'].'"><img alt="VSPLATE GO" src="https://raw.githubusercontent.com/vsplate/images/master/vsgo_btn.png" width="200px"></a>

**Click the `VSPLATE GO` button to launch a demo online / 点击`VSPLATE GO`按钮创建在线环境**';
    $dname = $tag['name'];
    mkdir("../$dname");
    copy('wp-config.php', "../$dname/wp-config.php");
    file_put_contents("../$dname/docker-compose.yml", $mydc);
    file_put_contents("../$dname/README.md", $myrd);
    echo "../$dname\n";
}
