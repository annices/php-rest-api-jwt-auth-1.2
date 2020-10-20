<?php
// Class to handle settings file:
class File {

    /*
    Method to read a file.
    */
    function parse_file($file) {

        // Fetch the contents of the specified file:
        $content = file_get_contents($file);
        // Split the content by line break:
        $lines = explode("\n",$content);
        // Define an array to tag every property on:
        $array = array();
        // Read all lines:
        foreach ($lines as $value) {
            // If the first character of the line isn't a # (comment character) continue, else ignore:
            if(substr($value,0,1) != "#"){
                // Split the line at "=" to fetch the property name and value.
                $lineSplit = explode("=",$value);
                // Define the property name:
                $propName = $lineSplit[0];
                // Define the property value:
                $propValue = $lineSplit[1];
                // Get rid of whitespaces:
                $propValue = substr($propValue,0,strlen($propValue)-1);
                // Set the property in the array equal to it's value:
                $array[$propName] = $propValue;
            }
        }
        return $array;
    }

    /*
    Method to edit a file property.
    */
    function edit_file($property,$equals,$file){

        // Get the file contents:
        $content = file_get_contents($file);
        // Split the content into an array by line break:
        $lines = explode("\n",$content);
        // Define array for new lines, throwing edited one in the mix:
        $newLines = array();
        // Counter used to read more than one line:
        $count = 0;
        // Loop to read through the file:
        foreach($lines as $value) {
            // Split the line by "=" to know where property name ends: 
            $lineSplit = explode("=",$value);
            // Check if the beginning of a line is equal to the property name:
            if($lineSplit[0] == $property){
            // If so, add the new line:
                $newLines[$count] = "$property=$equals";
            }else {
            // Else, just add the previous one:
                $newLines[$count] = $value;
            }
            // Increment
            $count= $count+1;
        }
        $final;
        // Read the new array values, and append it to the final string with a line break:
        foreach($newLines as $i){
            // Make sure extra lines aren't added at top of the file:
            if(!isset($final))
                $final = $i;
            else
                $final .= "\n".$i;
        }
        // write the new file:
        $write = fopen($file,"w");
        fwrite($write,$final);
        fclose($write);
    }

} // End class.
?>