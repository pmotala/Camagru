<?php

function alert($string)
{
    if (is_array($string))
    {
        print "<script>
        alert('";
        foreach($string as $line)
        {
            print($line. '\n');
        }
        print "') </script>"; 
    }
    else
    {
        print "<script> alert('{$string}') </script>";
    }
}
?>