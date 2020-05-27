<?php
$version = isset($argv[1])?trim($argv[1], 'v'):false;
if($version == false){
    die("Usage: {$argv[0]} 6.5.5\n");
}
foreach($argv as $k => $v){
    if($k == 0)
        continue;
    gen($v);
}

function gen($version){
    if(file_exists($version)){
        echo "Version {$version} exists\n";
        return false;
    }
    $cmd = "cp -R template {$version} && sed -i 's/{VERSION}/{$version}/g' ./{$version}/Dockerfile && sed -i 's/{VERSION}/{$version}/g' ./{$version}/dc/docker-compose.yml && sed -i 's/{VERSION}/{$version}/g' ./{$version}/dc/README.md";
    exec($cmd);

    if(file_exists("./{$version}/dc/docker-compose.yml")){
        echo "Done\n";
        return true;
    }
    echo "Error\n";
    return false;
}
