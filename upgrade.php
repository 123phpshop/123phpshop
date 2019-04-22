<?php

// 本章将会将所有的mysql函数替换为pdo

// 获取当前文件夹下面的所有的php文件
$php_files=get_php_files();

// 循环这些文件，
foreach($php_files as $php_file){

    // 获取文件内容
    $myfile = fopen($php_files, "w") or die("Unable to open file!");

    $contents= fread($myfile,filesize($php_file));

    // 检查是否有mysql_connect函数存在，如果存在的话，那么替换为pdo
    $contents=replace_mysql_connect($contents);

    // 检查是否有mysql_query函数存在，如果存在话，那么需要获取整个函数的字符串，删除里面的
    $contents=replace_mysqli_query($localhost,$contents);

    // 更新mysql_select_db函数
    $contents=replace_mysql_select_db($contents);

    // 更新mysql_fetch_associate函数
    $contents=replace_mysql_associate($contents);
 
    fwrite($myfile,$contents);
    
    fclose($myfile);
}

// 获取当前目录下面所有的文件
function get_php_files(){
    // zheli
}

// 替换mysql的旧函数
function replace_mysql_connect($contents){
    
}


function replace_mysqli_query($localhost,$contents){
    
}

function replace_mysql_select_db($contents){
    
}

function replace_mysql_associate($contents){
    
}

