<?php
/**
 * 删除目录
 */
function rmdirs($dir,$rm_self=false)  
{  
    if(!is_dir($dir)) return false;
    $d = dir($dir);  
    while (false !== ($child = $d->read())){  
        if($child != '.' && $child != '..'){  
            if(is_dir($dir.'/'.$child)){
                rmdirs($dir.'/'.$child,true);  
            } 
            else{
                unlink($dir.'/'.$child);  
            } 
        }  
    }  
    $d->close();  
    $rm_self && rmdir($dir);  
}
?>