<?php

if (!defined('PHPLISTINIT')) die(); ## avoid pages being loaded directly
if ($GLOBALS["commandline"]) {
 echo 'not to oppened by command line';
 die();
}

require_once(dirname(__FILE__).'Attribute_Changer_Plugin.php');
require_once(dirname(__FILE__).'Display_Functions.php');
require_once(dirname(__FILE__).'Display_Adjustment_Functions.php');


$javascript_src = dirname(__FILE__) . '/Attribute_Changer_PLugin/Script_For_Attribute_Changer.js';

$page_print =  '
<div>Attribute Changer</div>
<div id="error_printing"></div>
<form action="upload_file.php" method="post" enctype="multipart/form-data" id="file_upload_form">
    Select file to upload:
    (must be comma separated text)
    <input type="file" name="attribute_changer_file_to_upload" id="attribute_changer_file_to_upload">
    <input type="button" value="attribute_changer_upload_file_button" name="attribute_changer_upload_file_button" id="attribute_changer_upload_file_button" onClick="Test_Upload_File()">
</form>
<form action="" method="post" enctype="multipart/form-data" id="text_upload_form">
    Copy file to upload:
    (must be comma separated text)
    <input type="text" name="attribute_changer_text_to_upload" id="attribute_changer_text_to_upload">
    <input type="button" value="attribute_changer_upload_text" name="attribute_changer_upload_text" onClick="Test_Upload_Text()">
    desired_file_name:<input type="text" name="attribute_changer_text_name">
</form>';

$attribute_changer = $GLOBALS['plugins']['Attribute_Changer_Plugin'];

$FILE_LOCATION;

if(!isset($_POST)) {
    
    print('<html><head><script src="'.$javascript_src.'"></script></head><body>'.$page_print.'</body></html>');
}


else if(isset($_POST['submit']['File_Column_Match_Submit'])) {

    if(!isset($_POST['attribute_to_match'])) {
        //shouldnt happen .... else user needs to be WARNEDDDDD
    }
    else if(!isset($_POST['attribute_to_match']['email'])) {

        //this can be done in JS
        $display_html = "<html><body>no email column selected</body></html>";
    }
    else{
        $FILE_LOCATION = $GLOBALS['plugins']['Attribute_Changer_Plugin']->$Current_Session->Get_File_Location();

        asort($_POST['attribute_to_match'], SORT_NUMERIC);
        //so that the columns are matched, easier to read the file from comma to comma
        $fp = fopen($FILE_LOCATION, 'r');

        $first_line = fgets($fp);
        if(feof($fp)) {
            //....only 1 line whhaaat
        }
        $number_columns = count(explode(',',$first_line));

        $file_attribute_value_array = array();

        $current_block = '';
        $lines = array();


        while(!feof($fp)) {
            //read 10kb at a time

            $current_line_csv = fgetcsv($fp);

            if(count($current_line_csv) != $number_columns) {

                //SOME WEIRD ERROR, CHECK EOF
            }


            $new_attribute_value_array = array();

            foreach ($_POST['attribute_to_match'] as $attribute_id => $col_number) {
                if(isset($current_line_csv[$col_number]) && $current_line_csv[$col_number] != '') {
                    if($attribute_id === 'email') {
                        $new_attribute_value_array[$attribute_id] = $current_line_csv[$col_number];
                    }
                    else if($Session->attribute_list[$attribute_id]['type'] === "radio"|"checkboxgroup"|"select"|"checkbox") {
                        $new_attribute_value_array[$attribute_id] = explode(',', $current_line_csv[$col_number]);
                    }

                    else {
                        $new_attribute_value_array[$attribute_id] = $current_line_csv[$col_number];
                    }
                }
            }
            if(isset($new_attribute_value_array['email'])) {
                $attribute_changer->Test_Entry($new_attribute_value_array);
            }
        }

        fclose($fp);
        $display_html ='<html><body>';
        $new_entry_table_html = '';
        if(Initialize_New_Entries_Display()!=null) {
            $display_html = $display_html.Get_New_Entry_Table_Block().'</body></html>';
        }
        else{
            if(Initialize_Modify_Entries_Display()!=null) {
                $display_html = $display_html.Get_Modify_Entry_Table_Block().'</body></html>';
            }
            else{
                $display_html = $display_html.'There is nothing new or to modify</body></html>'
            }
        }
    }

    print($display_html);      
}


?>