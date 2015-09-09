<?php


	$attribute_changer = $GLOBALS['plugins']['Attribute_Changer_Plugin'];


	function Get_Attribute_File_Column_Match() {


        //multiple per column as ,"val, val, val",
        if($attribute_changer->Current_Session == null) {
            return "ERROR NO CURRENT SESSION";
        }

        $Session = $attribute_changer->Current_Session;

        if($Session->file_location == null || !file_exists($Session->file_location)) {
            return "ERROR WITH SESSION FILE LOCATION";
        }

        $column_match_return_string = '';
        $fp = fopen($Session->file_location, 'r');
        if(!$fp) {
            return "ERRORORORO FILE POINTER BAD";
        }

        $columns = array();
        $current_word = '';
        $current_char ='';

        $first_block = '';

        while(!feof($fp)) {
            $first_line = fgets($fp);
        }
        if(feof($fp)) {
            fclose($fp);
            return 'error no values set';
        }

        $columns = explode(',', $first_line);
        $current_row = 0;

        while($current_row < 10) {
            $first_few_rows[$current_row] = fgets($fp);
            
            if(substr($first_few_rows[$current_row], -1) === '\n') {
                substr_replace($first_few_rows[$current_row], "", -1);
            }

     
            if(feof($fp){
                if(count(explode(',', $first_few_rows[--$current_row])) <  count($columns)) {
                    if($current_row == 0) {
                        return "ERROR, THERE EXISTS ONLY ONE CSV LINE, AND IT IS WITHOUT ENOUGH COLUMNS";
                    }
                    unset($first_few_rows[$current_row]);
                }
                break;
            }
            $current_row++;

        }

        $number_of_rows = 10;
        if(count($first_few_rows) < 10) {
            $number_of_rows = count($first_few_rows);
        }

        if(count($Session->attribute_list)==0){
            return "ERROR NO ATTRIBUTES TO CHOOSE FROM";
        }


        $column_match_return_string = '
        <form action="" method="post" id="file_column_select_form">
        <table id="column_match_table><tr>';
        $column_match_return_string = $column_match_return_string.sprintf('<input type="hidden" name="file_location" value="%s">', $new_file_loc);
        //create radios for each
        foreach ($columns as $column_key => $column_value) {
            $cell_string = sprintf('<td> Set : %s  to : <br>', $column_value);

            foreach ($Session->attribute_list as $attribute_id => $attribute_info) {
                $cell_string = $cell_string.sprintf('<input type="radio" name="attribute_to_match[%d]" value="%d" class="%s"><br>', $attribute_id, $column_key, $column_value);
            }
            $cell_string = $cell_string.sprintf('<input type="radio" name="attribute_to_match[%s]" value="%d" class="%s"><br>', 'email', $column_key, "email_class");

            $cell_string = $cell_string.sprintf('<input type="button" id="clear_%s" value="Clear Column" onClick="Clear_Column_Select(\'%s\')"', $column_value, $column_value);
            $column_match_return_string = $cell_string.'</td>';
        }
        $column_match_return_string = $column_match_return_string.'</tr>';

        $value_row = '';

        for(i=1; i < $number_of_rows; i++) {
            $value_row = '<tr>';
            foreach ( (explode(',', $first_few_rows[$i])) as $key => $table_value) {
                $value_row=$value_row.sprintf('<td>%s</td>', $table_value);
            }
            $column_match_return_string = $column_match_return_string.$value_row.'</tr>';
        }

        $column_match_return_string = $column_match_return_string.'</table><input type="submit" name="File_Column_Match_Submit" value="submit"> </form>';

        return $column_match_return_string;
    }



    function Get_New_Entry_Table_Block() {
        
        $Current_New_Entry_Block = array_slice($Session->New_Entry_List, $Session->Current_New_Entry_Block_Number*$Session->Current_New_Entries_Display_Amount, $Session->Current_New_Entries_Display_Amount);

        $HTML_Display_Text = sprintf('<form name="New_Entry_Submit_Form_Block__%d" action="%s" method="post"><input type="hidden" name="New_Entry_Form_Submitted" value="submitted">', $Session->Current_New_Entry_Block_Number, 'New_And_Modify_Entry_Table_Display.php');
        $HTML_Display_Text = $HTML_Display_Text.sprintf('<table id="New_User_Attribute_Select_Table_Block__%d">', $Session->Current_New_Entry_Block_Number);
        $HTML_table_row = sprintf('<tr><td>EMAIL<br><input type="button" id="New_Entry_Include_All_Emails" name="New_Entry_Include_All_Emails" value="Include All Emails" onClick="checkAll_NewEntry_Emails()"></input>');
        $HTML_table_row = $HTML_table_row.sprintf('<input type="button" id="New_Entry_Remove_All_Emails" name="New_Entry_Include_Remove_Emails" value="Remove All Emails" onClick="removeAll_NewEntry_Emails()"></input></td>');

        foreach ($Session->attribute_list as $attribute_id => $attribute_info) {
            $HTML_table_row = $HTML_table_row.sprintf('<td>Attribute: %s<br><input type="checkbox" name="New_Entry_Attribute_Column_Select[%s]" value="checked">Include This Attribute</input>', $attribute_info['name'], $attribute_id);
            if($attribute_info['type'] === 'checkboxgroup') {
                $HTML_table_row = $HTML_table_row.sprintf('<br><input type="button" name="New_Entry_Include_All_Checkboxgroup_%s" id="New_Entry_Include_All_Checkboxgroup_%s" value="Include All Checkboxgroup Values" onClick="checkAll_NewEntry_CheckboxGroup(\'%s\')"></input>', $attribute_id, $attribute_id, $attribute_id);
                $HTML_table_row = $HTML_table_row.sprintf('<br><input type="button" name="New_Entry_Remove_All_Checkboxgroup_%s" id=="New_Entry_Remove_All_Checkboxgroup_%s" value="Remove All Checkboxgroup Values" onClick="removeAll_NewEntry_CheckboxGroup(\'%s\')"></input>', $attribute_id, $attribute_id, $attribute_id);
            }
            else{
                $HTML_table_row = $HTML_table_row.sprintf('<br><input type="button" name="New_Entry_Include_All_Safe_Values_%s" value="Include All Safe Values" onClick="checkAll_NewEntry_SafeValues(\'%s\')"></input></td>', $attribute_id, $attribute_id);
                $HTML_table_row = $HTML_table_row.sprintf('<br><input type="button" name="New_Entry_Include_All_Safe_Values_Or_Checked_%s" value="Include All Safe Values Or Checked" onClick="checkAll_NewEntry_SafeValues_OrChecked(\'%s\')"></input></td>', $attribute_id, $attribute_id);
                $HTML_table_row = $HTML_table_row.sprintf('<br><input type="button" name="New_Entry_Remove_All_Safe_Values_%s" value="Remove All Safe Values" onClick="removeAll_NewEntry_SafeValues(\'%s\')"></input></td>', $attribute_id, $attribute_id);
                $HTML_table_row = $HTML_table_row.sprintf('<br><input type="button" name="New_Entry_Remove_All_Safe_Values_Or_Checked_%s" value="Remove All Safe Values Or Checked" onClick="removeAll_NewEntry_SafeValues_OrChecked(\'%s\')"></input></td>', $attribute_ide, $attribute_id);
            }
        }
        $HTML_Display_Text = $HTML_Display_Text.$HTML_table_row.'</tr>';
        foreach ($Current_New_Entry_Block as $email_key => $new_user_attributes_and_values) {
            if(isset($Session->Commited_New_Entires[$email_key]) {
                $HTML_table_row = sprintf('<tr><td>%s<br><input type="checkbox" class="New_Entry_Email" name=$Session->"New_Entry_List[%s][\'include\']" value="include" checked>Include This Email</input><input type="hidden" name="Hidden$Session->_New_Entry_List[%s]" value="submitted"></td>',$email_key, $email_key, $email_key);
            }
            else{
                $HTML_table_row = sprintf('<tr><td>%s<br><input type="checkbox" class="New_Entry_Email" name=$Session->"New_Entry_List[%s][\'include\']" value="include">Include This Email</input><input type="hidden" name="Hidden$Session->_New_Entry_List[%s]" value="submitted"></td>',$email_key, $email_key, $email_key);
            }
            //commited_new_entries[email]: attribute,value
            foreach ($Session->attribute_list as $attribute_name => $attribute_info) {
                if(!isset($new_user_attributes_and_values[$attribute_name])) {
                    $HTML_table_row = $HTML_table_row.'<td></td>';
                }
                else {
                    $HTML_table_row= $HTML_table_row.'<td>';

                    foreach ($new_user_attributes_and_values[$attribute_name] as $key => $attribute_value) {

                        switch($attribute_info['type']){

                            case "textarea"|"textline"|"checkbox"|"hidden"|"date": 
                                if(isset($Session->Commited_New_Entires[$email_key] && isset($Session->Commited_New_Entires[$email_key][$attribute_name]) && $Session->Commited_New_Entires[$email_key][$attribute_name] === $attribute_value)) {
                                    //if the attribute value is the already selected, mark as checked
                                    if($key == 0) {
                                        $HTML_attribute_value_input = sprintf('<input type="radio" class="New_Entry_Safe_Value_Attribute_%s" name=$Session->"New_Entry_List[%s][%s]" value="%s" checked>%s</input>', $attribute_name, $email_key, $attribute_name, $attribute_value, $attribute_value);
                                    }
                                    else{
                                        $HTML_attribute_value_input = sprintf('<input type="radio" class="New_Entry_Attribute_%s" name=$Session->"New_Entry_List[%s][%s]" value="%s" checked>%s</input>', $email_key, $attribute_name, $attribute_value, $attribute_value);
                                    }
                                     
                                }
                                else{
                                    //else not yet selected so just create the input
                                    if($key == 0) {
                                        $HTML_attribute_value_input = sprintf('<input type="radio" class="New_Entry_Safe_Value_Attribute_%s" name=$Session->"New_Entry_List[%s][%s]" value="%s">%s</input>', $attribute_name, $email_key, $attribute_name, $attribute_value, $attribute_value);
                                    }
                                    else{
                                        $HTML_attribute_value_input = sprintf('<input type="radio" name=$Session->"New_Entry_List[%s][%s]" value="%s">%s</input>', $email_key, $attribute_name, $attribute_value, $attribute_value);
                                    }
                                }
                                $HTML_table_row= $HTML_table_row.$HTML_attribute_value_input.'<br>';
                                break;
                             
                            case "checkboxgroup": 
                                if(isset($Session->Commited_New_Entires[$email_key]) && isset($Session->Commited_New_Entires[$email_key][$attribute_name]) && in_array($attribute_value, $Session->Commited_New_Entires[$email_key][$attribute_name])) {
                                    //the current attribute value should already be checked
                                    $HTML_attribute_value_input = sprintf('<input type="checkbox" class="New_Entry_Checkbox_Value_Attribute_%s" name=$Session->"New_Entry_List[%s][%s][%s]" value="%s" checked>%s</input><br>', $attribute_name, $email_key, $attribute_name, $attribute_value, $attribute_value, $attribute_value);
                                }
                                else{
                                    //not already checked
                                    $HTML_attribute_value_input = sprintf('<input type="checkbox" class="New_Entry_Checkbox_Value_Attribute_%s" name=$Session->"New_Entry_List[%s][%s][%s]" value="%s">%s</input><br>', $attribute_name, $email_key, $attribute_name, $attribute_value, $attribute_value, $attribute_value);
                                }
                                $HTML_table_row= $HTML_table_row.$HTML_attribute_value_input.'<br>';
                                break;
                            default:
                                break;
                        }
                    }
                    //have cycled through each of possible new values for the attribute
                    $HTML_table_row= $HTML_table_row.'</td>';
                }

            }
            $HTML_Display_Text = $HTML_Display_Text.$HTML_table_row.'</tr>';
             
        }
        $HTML_Display_Text = $HTML_Display_Text.'</table>';
        $HTML_submit_buttons = '<input type="submit" name="New_Entries_Table_Submit_All" value="New_Entries_Table_Submit_All"></input>';
        if($Session->Current_New_Entry_Block_Number > 0) {
            $HTML_submit_buttons = $HTML_submit_buttons.'<input type="submit" name="value="New_Entries_Table_Previous_Page" value="New_Entries_Table_Previous_Page"></input>';
        }
        if($Session->Current_New_Entry_Block_Number < $Session->New_Entires_Number_Of_Blocks - 1) {
            $HTML_submit_buttons = $HTML_submit_buttons.'<input type="submit" name="New_Entries_Table_Next_page" value="New_Entries_Table_Next_page"></input>';
        }
        switch($Session->Current_New_Entries_Display_Amount){
            case 10:
                $HTML_Display_Size_Submit = '<select name="New_Entries_New_Display_Amount"><option value="10" checked>10</option><option value="100">100</option><option value="1000">1000</option><option value="10000">10000</option><option value="all">all</option>';
            case 100:
                $HTML_Display_Size_Submit = '<select name="New_Entries_New_Display_Amount"><option value="10">10</option><option value="100" checked>100</option><option value="1000">1000</option><option value="10000">10000</option><option value="all">all</option>';
            case 1000:
                $HTML_Display_Size_Submit = '<select name="New_Entries_New_Display_Amount"><option value="10">10</option><option value="100">100</option><option value="1000" checked>1000</option><option value="10000">10000</option><option value="all">all</option>';
            case 10000:
                $HTML_Display_Size_Submit = '<select name="New_Entries_New_Display_Amount"><option value="10">10</option><option value="100">100</option><option value="1000">1000</option><option value="10000" checked>10000</option><option value="all">all</option>';
            case 'all':
                $HTML_Display_Size_Submit = '<select name="New_Entries_New_Display_Amount"><option value="10">10</option><option value="100">100</option><option value="1000">1000</option><option value="10000">10000</option><option value="all" checked>all</option>';
        }
        $HTML_Display_Size_Submit = $HTML_Display_Size_Submit.'<input type="submit" name="New_Entry_Change_Display_Amount" value="New_Entry_Change_Display_Amount"></input>';
        $HTML_Display_Text = $HTML_Display_Text.$HTML_submit_buttons.$HTML_Display_Size_Submit.'</form>';
        $HTML_current_table_info = sprintf("<div>Current Block : %d of %d. Displaying %d entires per page.</div>", $Session->Current_New_Entry_Block_Number+1, $Session->New_Entires_Number_Of_Blocks, $Session->Current_New_Entries_Display_Amount);
        $HTML_Display_Text = $HTML_Display_Text.$HTML_current_table_info;
        return $HTML_Display_Text;
    }



    function Get_Modify_Entry_Table_Block() {
        $Current_Modify_Entry_Block = array_slice($Session->Modify_Entry_List, $Session->Current_Modify_Entry_Block_Number*$Session->Current_Modify_Entries_Display_Amount, $Session->Current_Modify_Entries_Display_Amount);
        $HTML_Display_Text = sprintf('<form name="Modify_Entry_Submit_Form_Block__%d" action="%s" method="post"><input type="hidden" name="Modify_Entry_Form_Submitted" value="submitted">', $Session->Current_Modify_Entry_Block_Number, 'New_And_Modify_Entry_Table_Display.php');
        $HTML_Display_Text = $HTML_Display_Text.sprintf('<table id="Modify_User_Attribute_Select_Table_Block__%d">', $Session->Current_Modify_Entry_Block_Number);

        $HTML_table_row = sprintf('<tr><td>EMAIL<br><input type="button" id="Modify_Entry_Include_All_Emails" name="Modify_Entry_Include_All_Emails" value="Include All Emails" onClick="checkAll_ModifyEntry_Emails()"></input>');
        $HTML_table_row = $HTML_table_row.sprintf('<input type="button" id="Modify_Entry_Remove_All_Emails" name="Modify_Entry_Remove_All_Emails" value="Remove All Emails" onClick="removeAll_ModifyEntry_Emails()"></input></td>');

        foreach ($Session->attribute_list as $attribute_name => $attribute_info) {
            $HTML_table_row = $HTML_table_row.sprintf('<td>%s<input type="checkbox" name="Modify_Entry_Attribute_Column_Select[%s]" value="checked">',$attribute_name, $attribute_name);
            if($attribute_info['type'] === 'checkboxgroup') {
                $HTML_table_row = $HTML_table_row.sprintf('<br><input type="button" name="Modify_Entry_Include_All_Checkboxgroup_%s" value="Include All Checkboxgroup Values" onClick="checkAll_ModifyEntry_CheckboxGroup(\'%s\')"></input>', $attribute_name, $attribute_name);
                $HTML_table_row = $HTML_table_row.sprintf('<br><input type="button" name="Modify_Entry_Remove_All_Checkboxgroup_%s" value="Remove All Checkboxgroup Values" onClick="removeAll_ModifyEntry_CheckboxGroup(\'%s\')"></input></td>', $attribute_name, $attribute_name);
            }
            else{
                $HTML_table_row = $HTML_table_row.sprintf('<br><input type="button" name="Modify_Entry_Include_All_Safe_Values_%s" value="Include All Safe Values" onClick="checkAll_ModifyEntry_SafeValues(\'%s\')></input>', $attribute_name, $attribute_name);
                $HTML_table_row = $HTML_table_row.sprintf('<br><input type="button" name="Modify_Entry_Remove_All_Safe_Values_%s" value="Remove All Safe Values" onClick="removeAll_ModifyEntry_SafeValues(\'%s\')></input></td>', $attribute_name, $attribute_name);
            }
        }
        $HTML_Display_Text = $HTML_Display_Text.$HTML_table_row.'</tr>';

        foreach ($Current_Modify_Entry_Block as $email_key => $modify_user_attributes_and_values) {
            //THIS SHOULD NEVER HAPPEN!!!!!!!
            if(!isset($Session->Current_user_values[$email_key])) {
                Get_Current_User_Attribute_Values();
            }
             
            if(isset($Commited_Modify_Entries[$email_key])) {
                $HTML_table_row = sprintf('<tr><td>%s<br><input type="checkbox" class="Modify_Entry_Email" name="Modify_Entry_List[%s][\'include\']" value="include" checked>Include This Email</input><input type="hidden" name="Hidden$Session->_Modify_Entry_List[%s]" value="submitted"></td>',$email_key, $email_key, $email_key);
            }
            else{
                $HTML_table_row = sprintf('<tr><td>%s<br><input type="checkbox" class="Modify_Entry_Email" name="Modify_Entry_List[%s][\'include\']" value="include">Include This Email</input><input type="hidden" name="Hidden$Session->_Modify_Entry_List[%s]" value="submitted"></td>',$email_key, $email_key, $email_key);
            }

            //first foreach is for current set vals
            foreach ($Session->attribute_list as $attribute_name => $attribute_info) {
                $HTML_table_row = $HTML_table_row.'<td>';

                if(!isset($Session->Current_user_values[$email_key]['attributes'][$attribute_name]) {
                    $HTML_table_row = $HTML_table_row.'</td>';
                }
                else {

                    if($Session->attribute_list[$attribute_name]['type'] === 'checkboxgroup') {

                        foreach ($Session->Current_user_values[$email_key]['attributes'][$attribute_name] as $key => $current_single_value) {

                            if(isset($Commited_Modify_Entries[$email_key]) && isset($Commited_Modify_Entries[$email_key][$attribute_name])) {
                                if(in_array($current_single_value, $Commited_Modify_Entries[$email_key][$attribute_name])) {
                                    $HTML_attribute_value_input = sprintf('<input type="checkbox" class="Current_Modify_Checkbox_Value_%s" name="Modify_Entry_List[%s][%s][%s]" value="%s" checked>%s</input><br>', $attribute_name, $email_key, $attribute_name, $current_single_value, $current_single_value, $current_single_value);
                                }
                                else {
                                    $HTML_attribute_value_input = sprintf('<input type="checkbox" class="Current_Modify_Checkbox_Value_%s" name="Modify_Entry_List[%s][%s][%s]" value="%s">%s</input><br>', $attribute_name, $email_key, $attribute_name, $current_single_value, $current_single_value, $current_single_value);
                                }
                            }
                            else{
                                $HTML_attribute_value_input = sprintf('<input type="checkbox" class="Current_Modify_Checkbox_Value_%s" name="Modify_Entry_List[%s][%s][%s]" value="%s">%s</input><br>', $attribute_name, $email_key, $attribute_name, $current_single_value, $current_single_value, $current_single_value);
                            }
                            $HTML_table_row = $HTML_table_row.$HTML_attribute_value_input;
                        }
                    }
                    else {
                        if(isset($Commited_Modify_Entries[$email_key]) && isset($Commited_Modify_Entries[$email_key][$attribute_name])) {
                            if($Session->Current_user_values[$email_key]['attributes'][$attribute_name] === $Commited_Modify_Entries[$email_key][$attribute_name]) {
                                $HTML_attribute_value_input = sprintf('<input type="radio class="Current_Modify_Attribute_Value_%s" name="Modify_Entry_List[%s][%s]" value="%s" checked>%s</input><br>', $attribute_name, $email_key, $attribute_name, $Session->Current_user_values[$email_key]['attributes'][$attribute_name], $Session->Current_user_values[$email_key]['attributes'][$attribute_name]);                             }
                            else {
                                $HTML_attribute_value_input = sprintf('<input type="checkbox" class="Current_Modify_Checkbox_Value_%s" name="Modify_Entry_List[%s][%s][%s]" value="%s">%s</input><br>', $attribute_name, $email_key, $attribute_name, $$Session->Current_user_values[$email_key]['attributes'][$attribute_name], $$Session->Current_user_values[$email_key]['attributes'][$attribute_name], $$Session->Current_user_values[$email_key]['attributes'][$attribute_name]);
                            }
                        }
                        else{
                            $HTML_attribute_value_input = sprintf('<input type="checkbox" class="Current_Modify_Checkbox_Value_%s" name="Modify_Entry_List[%s][%s][%s]" value="%s">%s</input><br>', $attribute_name, $email_key, $attribute_name, $$Session->Current_user_values[$email_key]['attributes'][$attribute_name], $$Session->Current_user_values[$email_key]['attributes'][$attribute_name], $$Session->Current_user_values[$email_key]['attributes'][$attribute_name]);
                        }
                        $HTML_table_row = $HTML_table_row.$HTML_attribute_value_input;
                    }
                    $HTML_table_row = $HTML_table_row.'</td>';
                }
                 
            }
            $HTML_table_row = $HTML_table_row.'</tr>';
            $HTML_Display_Text = $HTML_Display_Text.$HTML_table_row;

            $HTML_table_row = '<tr><td></td>';

            foreach ($Session->attribute_list as $attribute_name => $attribute_info) {

                $HTML_table_row = $HTML_table_row.'<td>';
                 
                if(isset($modify_user_attributes_and_values[$attribute_name])) {


                    if($attribute_info['type'] === 'checkboxgroup') {

                        $HTML_table_row = $HTML_table_row.Get_Modify_Attribute_Value_Display_Checkboxgroup($Session->Commited_Modify_Entries, $email_key, $attribute_name, $modify_user_attributes_and_values[$attribute_name]);

                        $HTML_table_row = $HTML_table_row.'</td>';
                    }
                    else{

                        $HTML_table_row = $HTML_table_row.Get_Modify_Attribute_Value_Display_Other_Type($Session->Current_Users_Values, $Session->Commited_Modify_Entries, $email_key, $attribute_name, $modify_user_attributes_and_values[$attribute_name]);
                        
                        $HTML_table_row = $HTML_table_row.'</td>';
                    }

                } 
                else{
                    $HTML_table_row = $HTML_table_row.'</td>';
                }  
            }

            $HTML_table_row = $HTML_table_row.'</tr>';
            $HTML_Display_Text = $HTML_Display_Text.$HTML_table_row;

        }

        $HTML_Display_Text = $HTML_Display_Text.'</table>';
        $HTML_submit_buttons = '<input type="submit" name ="Modify_Entries_Table_Submit_all" value="Submit_all">Submit_all</input>';
        if($Session->Current_New_Entry_Block_Number > 0) {
            $HTML_submit_buttons = $HTML_submit_buttons.'<input type="submit" name ="Modify_Entries_Table_Previous_Page" value="Modify_Entries_Table_Previous_Page"></input>';
        }
        if($Session->Current_New_Entry_Block_Number < $Session->New_Entires_Number_Of_Blocks - 1) {
            $HTML_submit_buttons = $HTML_submit_buttons.'<input type="submit" name ="Modify_Entries_Table_Next_Page" value="Modify_Entries_Table_Next_Page"></input>';
        }
        switch($Session->Current_Modify_Entries_Display_Amount){
            case 10:
                $HTML_Display_Size_Submit = '<select name="Modify_Entries_New_Display_Amount"><option value="10" checked>10</option><option value="100">100</option><option value="1000">1000</option><option value="10000">10000</option><option value="all">all</option>';
            case 100:
                $HTML_Display_Size_Submit = '<select name="Modify_Entries_New_Display_Amount"><option value="10">10</option><option value="100" checked>100</option><option value="1000">1000</option><option value="10000">10000</option><option value="all">all</option>';
            case 1000:
                $HTML_Display_Size_Submit = '<select name="Modify_Entries_New_Display_Amount"><option value="10">10</option><option value="100">100</option><option value="1000" checked>1000</option><option value="10000">10000</option><option value="all">all</option>';
            case 10000:
                $HTML_Display_Size_Submit = '<select name="Modify_Entries_New_Display_Amount"><option value="10">10</option><option value="100">100</option><option value="1000">1000</option><option value="10000" checked>10000</option><option value="all">all</option>';
            case 'all':
                $HTML_Display_Size_Submit = '<select name="Modify_Entries_New_Display_Amount"><option value="10">10</option><option value="100">100</option><option value="1000">1000</option><option value="10000">10000</option><option value="all" checked>all</option>';
            default:

        }
        $HTML_Display_Size_Submit = $HTML_Display_Size_Submit.'<input type="submit" name="New_Entry_Change_Display_Amount" value="New_Entry_Change_Display_Amount"></input>';
        $HTML_Display_Text = $HTML_Display_Text.$HTML_submit_buttons.$HTML_Display_Size_Submit.'</form>';
        $HTML_current_table_info = sprintf("<div>Current Block : %d of %d. Displaying %d entires per page.</div>", $Session->Current_Modify_Entry_Block_Number+1, $Session->Modify_Entires_Number_Of_Blocks, $Session->Current_Modify_Entries_Display_Amount);
        $HTML_Display_Text = $HTML_Display_Text.$HTML_current_table_info;
        return $HTML_Display_Text;
    }

    function Get_Modify_Attribute_Value_Display_Checkboxgroup(&$All_Committed_Modify_Entries, $current_email, $current_attribute_name, $all_values) {
        $HTML_value_block = '';

        foreach ($all_values as $key => $checkbox_value) {

            if(isset($All_Committed_Modify_Entries[$current_email]) && isset($All_Committed_Modify_Entries[$current_email][$current_attribute_name]) {

                if(in_array($checkbox_value, $All_Committed_Modify_Entries[$current_email][$current_attribute_name]) ) {
                    $HTML_attribute_value_input = sprintf('<input type="checkbox" class="Modify_Entry_Checkbox_Value_Attribute_%s" name="Modify_Entry_List[%s][%s][%s]" value="%s" checked>%s</input><br>', $current_attribute_name, $current_email, $current_attribute_name, $checkbox_value, $checkbox_value, $checkbox_value);
                }
                else{
                    $HTML_attribute_value_input = sprintf('<input type="checkbox" class="Modify_Entry_Checkbox_Value_Attribute_%s" name="Modify_Entry_List[%s][%s][%s]" value="%s">%s</input><br>', $current_attribute_name, $current_email, $current_attribute_name, $checkbox_value, $checkbox_value, $checkbox_value);
                }

            }
            else{
                $HTML_attribute_value_input = sprintf('<input type="checkbox" class="Modify_Entry_Checkbox_Value_Attribute_%s" name="Modify_Entry_List[%s][%s][%s]" value="%s">%s</input><br>', $current_attribute_name, $current_email, $current_attribute_name, $checkbox_value, $checkbox_value, $checkbox_value);
            }
            $HTML_value_block = $HTML_value_block.$HTML_attribute_value_input;
        }
        return $HTML_value_block;
    }

    function Get_Modify_Attribute_Value_Display_Other_Type(&$All_Current_User_Values, &$All_Committed_Modify_Entries, $current_email, $current_attribute_name, $all_values) {
        $HTML_value_block = '';

        foreach ($all_values as $key => $checkbox_value) {

            if(isset($All_Committed_Modify_Entries[$current_email]) && isset($All_Committed_Modify_Entries[$current_email][$current_attribute_name]) {

                if($checkbox_value === $All_Committed_Modify_Entries[$current_email][$current_attribute_name]) ) {
                    if($key == 0) && !isset($All$Session->_Current_User_Values[$current_email][$current_attribute_name])) {
                        $HTML_attribute_value_input = sprintf('<input type="radio" class="Modify_Entry_Safe_Value_Attribute_%s" name="Modify_Entry_List[%s][%s][%s]" value="%s" checked>%s</input><br>', $current_attribute_name, $current_email, $current_attribute_name, $checkbox_value, $checkbox_value, $checkbox_value);
                    }
                    else{
                        $HTML_attribute_value_input = sprintf('<input type="radio" name="Modify_Entry_List[%s][%s][%s]" value="%s" checked>%s</input><br>', $current_email, $current_attribute_name, $checkbox_value, $checkbox_value, $checkbox_value);
                    }

                }
                else{
                    if($key == 0 && !isset($All_Current_User_Values[$current_email][$current_attribute_name])) {
                        $HTML_attribute_value_input = sprintf('<input type="radio" class="Modify_Entry_Safe_Value_Attribute_%s" name="Modify_Entry_List[%s][%s][%s]" value="%s">%s</input><br>', $current_attribute_name, $current_email, $current_attribute_name, $checkbox_value, $checkbox_value, $checkbox_value);
                    }
                    else{
                        $HTML_attribute_value_input = sprintf('<input type="radio" name="Modify_Entry_List[%s][%s][%s]" value="%s">%s</input><br>', $current_email, $current_attribute_name, $checkbox_value, $checkbox_value, $checkbox_value);
                    }                                   
                }

            }
            else{
                if($key == 0 && !isset($All_Current_User_Values[$current_email][$current_attribute_name])) {
                    $HTML_attribute_value_input = sprintf('<input type="radio" class="Modify_Entry_Safe_Value_Attribute_%s" name="Modify_Entry_List[%s][%s][%s]" value="%s">%s</input><br>', $current_attribute_name, $current_email, $current_attribute_name, $checkbox_value, $checkbox_value, $checkbox_value);
                }
                else{
                    $HTML_attribute_value_input = sprintf('<input type="radio" name="Modify_Entry_List[%s][%s][%s]" value="%s">%s</input><br>', $current_email, $current_attribute_name, $checkbox_value, $checkbox_value, $checkbox_value);
                }
            }
            $HTML_value_block = $HTML_table_row.$HTML_attribute_value_input;
        }
        return $HTML_value_block;
    }

?>