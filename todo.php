<?php
/*
CodeUp Day 6
Dev: Frank Pigeon
Date: May 13 2014
Description:
Create a new directory in your vagrant-lamp directory named todo_list with a file named todo.php containing the code above.
Use git init to initialize a new local repository in that directory and commit your code.
Create a new remote repository on GitHub called CLI_Todo_List and add the remote to your local repository so you can push your code.

After each exercise item, commit and push changes to your GitHub repository.

Update the code to allow upper and lowercase inputs from user for all menu items. Test adding, removing, and quitting.

Update the program to start numbering the list with 1 instead of 0. Make sure remove still works as expected.

WEEK 3
1) Open your todo.php file for editing. Commit changes and push to GitHub for each step.

2) Add a (S)ort option to your menu. When it is chosen, it should call a function called sort_menu().

3) When sort menu is opened, show the following options "(A)-Z, (Z)-A, (O)rder entered, (R)everse order entered".

4) When a sort type is selected, order the TODO list accordingly and display the results.

Push and Pop
1) When a new item is added to a TODO list, ask the user if they want to add it to the beginning or end of the list.
Default to end if no input is given.

2) Allow a user to enter F at the main menu to remove the first item on the list.
This feature will not be added to the menu, and will be a special feature that is only available to "power users".
Also add a L option that grabs and removes the last item in the list.
*/


//functions

function saveFile($filename, $list_array){
    if($filename == ''){
        $filename='data/default.txt';    
    } //if user just hits enter
    $handle = fopen($filename, 'w');
    if (is_writeable($filename)){        
        foreach ($list_array as $list_item) {
            fwrite($handle, $list_item . PHP_EOL);
        }//end of foreach
        fclose($handle);
        return TRUE;
    } //end of ovewrite ok
    else {
        return FALSE;
    } // end of else
} //end of SaveFile

/*function save_file($filename, $list_array){
    if($filename == ''){
        $filename='data/default.txt';    
    } //if user just hits enter
    $handle = fopen($filename, 'w');

    if (file_exists($filename)) {
        echo "This file exists, OVERWRITE? (Y) or (N): ";
        $overwrite = get_input(TRUE);
        
        if ($overwrite == 'Y'){
            if (is_writeable($filename)){        
                foreach ($list_array as $list_item) {
                    fwrite($handle, $list_item . PHP_EOL);
                }//end of foreach
                fclose($handle);
                return TRUE;
            } //end of end writable  
        } //end of ovewrite ok
            else {
                return FALSE;
            } //end 
    } //end of if file exists
    else { //file doesn't exists so go
        if (is_writeable($filename)){        
                foreach ($list_array as $list_item) {
                    fwrite($handle, $list_item . PHP_EOL);
                }//end of foreach
            fclose($handle);
            return TRUE;
        } //end of ovewrite ok
        else {
            return FALSE;
        } // end of else
    }//end of file found
    
}//end of save file*/

function open_file($filename){
    if($filename == ''){
        $filename='data/list.txt';    
    } //if user just hits enter

    if (is_readable($filename)){
        $handle = fopen($filename, 'r');
        $contents = fread($handle, filesize($filename));
        fclose($handle);
        //echo $contents;    
        $arrayed = explode(PHP_EOL, $contents);
        return $arrayed; 
    }//end of file found
    else {
        echo 'Error Reading File' . PHP_EOL;
        return FALSE;
    }//file not found


    
}//end of open file

function sort_menu($array) {
    echo "(A)-Z, (Z)-A, (O)rder entered, (R)everse order entered: ";
    $input = get_input(TRUE); //capture input and UPPERCASE IT

    switch ($input){
        case 'A':
            asort($array);
            break; //end of alphabetic
        case 'Z':
            arsort($array);
            break;
        case 'O':
             ksort($array);
             break;
        case 'R':
            krsort($array);
            break;
    } //end of switch

    return $array;
    
}// end of sort_menu

// List array items formatted for CLI
function list_items($list){
    $result = '';
    foreach ($list as $key => $item) {
        $result .= '[' . ($key + 1) .'] '. $item . PHP_EOL;
    } //end of foreach

    return $result;
} // end of list_items

// Get STDIN, strip whitespace and newlines, 
// and convert to uppercase if $upper is true
function get_input($upper = FALSE) 
{
    $result = trim(fgets(STDIN));
    return $upper ? strtoupper($result) : $result;    
} //end of get_input

//variables
// Create array to hold list of todo items
$items = array();

// The loop!
do {
    // Iterate through list items
    echo list_items($items); //NEW    

    // Show the menu options
    echo '(N)ew item, (R)emove item, (S)ort, (O)pen file, S(A)ve, (Q)uit: ';

    // Get the input from user
    $input = get_input(TRUE); //NEW
    
    switch ($input) {
         case 'A':
            echo "Enter the path and file name: ";
            $file_path=get_input();

            if (file_exists($file_path)) {
                echo "This file exists, OVERWRITE? (Y) or (N): ";
                $overwrite = get_input(TRUE);        
                
                if ($overwrite == 'Y'){
                    $saved = saveFile($file_path, $items);
                    if ($saved){
                        echo 'File saved successfully!' . PHP_EOL;
                    }//saved success
                    else {
                        echo 'Error saving file' . PHP_EOL;
                    } //bad save
                } //end of Y
             } //end of file exists
             else {
                $saved = saveFile($file_path, $items);
                    if ($saved){
                        echo 'File saved successfully!' . PHP_EOL;
                    }//saved success
                    else {
                        echo 'Error saving file' . PHP_EOL;
                    } //bad save 
                } // end of else   
            break;

         case 'O':
            echo "Enter the path and file name: ";
            $file_path=get_input();
            $file_items = open_file($file_path);
            if ($file_items !== FALSE){
                foreach ($file_items as $list_item) {
                    array_push($items, $list_item); //add to the end of the array
                } //end of foreach
            } // add to the array if found
            
            break;

         case 'N':
            echo '(B)eggining or (E)nd of the List: ';
            $list_placement = get_input(TRUE); //capture input
            // Ask for entry
            echo 'Enter item: ';
            // Add entry to list array
            $item = get_input();

            if ($list_placement == 'B') {           
                array_unshift($items, $item); //add to beggining of the array
            } // begining of the list
            else{
                array_push($items, $item); //add to the end of the array
            }// end of default 
            break;
         
         case 'R':
            // Remove which item?
            echo 'Enter item number to remove: ';
            // Get array key
            $key = get_input();
            // Remove from array
            unset($items[$key - 1]);
            break;

        case 'S':
            $items = sort_menu($items);
            break;

        case 'F':
            array_shift($items); //remove first index in array
            break;

        case 'L':
            array_pop($items); //removes last itemin array
            break;
     } // end of switch statment
	
// Exit when input is (Q)uit or (q)uit
} while ($input != 'Q');

// Say Goodbye!
echo "Goodbye!\n";

// Exit with 0 errors
exit(0);