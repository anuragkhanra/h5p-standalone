<?php
    function injectData($file, $data, $position) {
        $fpFile = fopen($file, "rw+");
        $fpTemp = fopen('php://temp', "rw+");
    
        $len = stream_copy_to_stream($fpFile, $fpTemp); // make a copy
    
        fseek($fpFile, $position); // move to the position
        fseek($fpTemp, $position); // move to the position
    
        fwrite($fpFile, $data); // Add the data
    
        stream_copy_to_stream($fpTemp, $fpFile); // @Jack
    
        fclose($fpFile); // close file
        fclose($fpTemp); // close tmp
    }
?>