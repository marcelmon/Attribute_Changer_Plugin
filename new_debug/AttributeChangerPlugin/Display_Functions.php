<?php

	

	//print_r($GLOBALS['plugins']['AttributeChangerPlugin']);

	function Get_Attribute_File_Column_Match() {
		
		$attribute_changer = $GLOBALS['plugins']['AttributeChangerPlugin'];

        //multiple per column as ,"val, val, val",
        if($attribute_changer->Current_Session == null) {
            return "ERROR NO CURRENT SESSION";
        }

        $Session = $attribute_changer->Current_Session;

        if($Session->file_location == null || !file_exists($Session->file_location)) {
            return "ERROR WITH SESSION FILE LOCATION";
        }
        
        // $column_match_return_string = '';
        // $fp = fopen($Session->file_location, 'r');
        // if(!$fp) {
        //     return "ERRORORORO FILE POINTER BAD";
        // }

        // $columns = array();
        // $current_word = '';
        // $current_char ='';

        // $first_block = '';

        // while(!feof($fp)) {
        //     $first_line = fgets($fp);
        // }
        // if(feof($fp)) {
        //     fclose($fp);
        //     return 'error no values set';
        // }

        // $columns = explode(',', $first_line);
        // $current_row = 0;

        // while($current_row < 10) {
        //     $first_few_rows[$current_row] = fgets($fp);
            
        //     if(substr($first_few_rows[$current_row], -1) === '\n') {
        //         substr_replace($first_few_rows[$current_row], "", -1);
        //     }

     
        //     if(feof($fp){
        //         if(count(explode(',', $first_few_rows[--$current_row])) <  count($columns)) {
        //             if($current_row == 0) {
        //                 return "ERROR, THERE EXISTS ONLY ONE CSV LINE, AND IT IS WITHOUT ENOUGH COLUMNS";
        //             }
        //             unset($first_few_rows[$current_row]);
        //         }
        //         break;
        //     }
        //     $current_row++;

        // }

        // $number_of_rows = 10;
        // if(count($first_few_rows) < 10) {
        //     $number_of_rows = count($first_few_rows);
        // }

        // if(count($Session->attribute_list)==0){
        //     return "ERROR NO ATTRIBUTES TO CHOOSE FROM";
        // }


        // $column_match_return_string = '
        // <form action="" method="post" id="file_column_select_form">
        // <table id="column_match_table><tr>';
        // $column_match_return_string = $column_match_return_string.sprintf('<input type="hidden" name="file_location" value="%s">', $new_file_loc);
        // //create radios for each
        // foreach ($columns as $column_key => $column_value) {
        //     $cell_string = sprintf('<td> Set : %s  to : <br>', $column_value);

        //     foreach ($Session->attribute_list as $attribute_id => $attribute_info) {
        //         $cell_string = $cell_string.sprintf('<input type="radio" name="attribute_to_match[%d]" value="%d" class="%s"><br>', $attribute_id, $column_key, $column_value);
        //     }
        //     $cell_string = $cell_string.sprintf('<input type="radio" name="attribute_to_match[%s]" value="%d" class="%s"><br>', 'email', $column_key, "email_class");

        //     $cell_string = $cell_string.sprintf('<input type="button" id="clear_%s" value="Clear Column" onClick="Clear_Column_Select(\'%s\')"', $column_value, $column_value);
        //     $column_match_return_string = $cell_string.'</td>';
        // }
        // $column_match_return_string = $column_match_return_string.'</tr>';

        // $value_row = '';

        // for(i=1; i < $number_of_rows; i++) {
        //     $value_row = '<tr>';
        //     foreach ( (explode(',', $first_few_rows[$i])) as $key => $table_value) {
        //         $value_row=$value_row.sprintf('<td>%s</td>', $table_value);
        //     }
        //     $column_match_return_string = $column_match_return_string.$value_row.'</tr>';
        // }

        // $column_match_return_string = $column_match_return_string.'</table><input type="submit" name="File_Column_Match_Submit" value="submit"> </form>';

        // return $column_match_return_string;
    }


?>