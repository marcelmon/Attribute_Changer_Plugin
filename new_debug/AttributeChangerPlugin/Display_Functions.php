<?php

	
//NEED VALUE IN OTHER THAN SET DEFAULT ? ACTUALLY NO 


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

        $column_match_return_string = '';
        $fp = fopen($Session->file_location, 'r');
        if(!$fp) {
            return "ERRORORORO FILE POINTER BAD";
        }

        $columns = array();
        $current_word = '';
        $current_char ='';

        $first_block = '';


        $first_line = rtrim(fgets($fp));
        
        if(feof($fp)) {
            fclose($fp);
            return 'error no values set';
        }

        $columns = explode(',', $first_line);
        $current_row = 0;
        $first_few_rows = array();
        while($current_row < 10) {
            $first_few_rows[$current_row] = rtrim(fgets($fp));
            
            if(substr($first_few_rows[$current_row], -1) === '\n') {
                substr_replace($first_few_rows[$current_row],"", -1);
            }
            $first_few_rows[$current_row] = rtrim($first_few_rows[$current_row]);
     
            if(feof($fp)) {
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
        $column_match_return_string = $column_match_return_string.sprintf('<input type="hidden" name="file_location" value="%s">', $Session->file_location);
        //create radios for each

        foreach ($columns as $column_key => $column_value) {
            $cell_string = sprintf('<td> Set : %s  to : <br>', $column_value);

            foreach ($Session->attribute_list as $attribute_id => $attribute_info) {
                $cell_string = $cell_string.sprintf('<input type="radio" name="attribute_to_match[%d]" value="%d" class="%s">%s<br>', $attribute_id, $column_key, $column_value, $Session->attribute_list[$attribute_id]['name']);
            }
            $cell_string = $cell_string.sprintf('<input type="radio" name="attribute_to_match[%s]" value="%d" class="%s">%s<br>', 'email', $column_key, $column_value, "email");

            $cell_string = $cell_string.sprintf('<input type="button" id="clear_%s" value="Clear Column" onClick="Clear_Column_Select(\'%s\')"', $column_value, $column_value);
            $column_match_return_string = $column_match_return_string.$cell_string.'</td>';
        }
        $column_match_return_string = $column_match_return_string.'</tr>';

        //print('<br>'.$column_match_return_string.'<br>');

        $value_row = '';

        for($i=1; $i < $number_of_rows; $i++) {
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

        $Session = $GLOBALS['plugins']['AttributeChangerPlugin']->Current_Session;
        
        $Current_New_Entry_Block = array_slice($Session->New_Entry_List, $Session->Current_New_Entry_Block_Number*$Session->Current_New_Entries_Display_Amount, $Session->Current_New_Entries_Display_Amount);

        $HTML_Display_Text = Get_New_Entry_Table_And_Top_Row();


        foreach ($Current_New_Entry_Block as $email_key => $new_user_attributes_and_values) {

            $HTML_table_row = '<tr><td>'.Get_New_Entry_Email_Block($email_key).'</td>';

            foreach ($Session->attribute_list as $attribute_id => $attribute_info) {

                $HTML_table_row = $HTML_table_row.'<td>'.Get_New_Entry_Attribute_Block($email_key, $attribute_id).'</td>';
            }
            $HTML_Display_Text = $HTML_Display_Text.$HTML_table_row.'</tr>';
             
        }
        //print($HTML_Display_Text."</table></form></html>");
       $HTML_Display_Text = $HTML_Display_Text.'</table>'.Get_New_Entry_Table_Navigation_Buttons();


        return $HTML_Display_Text;
    }


    function Get_New_Entry_Table_And_Top_Row() {

        $Session = $GLOBALS['plugins']['AttributeChangerPlugin']->Current_Session;

        $HTML_Display_Text = sprintf('<form name="New_Entry_Submit_Form_Block__%d" action="" method="post"><input type="hidden" name="New_Entry_Form_Submitted" value="submitted">', $Session->Current_New_Entry_Block_Number);
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
                $HTML_table_row = $HTML_table_row.sprintf('<br><input type="button" name="New_Entry_Remove_All_Safe_Values_Or_Checked_%s" value="Remove All Safe Values Or Checked" onClick="removeAll_NewEntry_SafeValues_OrChecked(\'%s\')"></input></td>', $attribute_id, $attribute_id);
            }
        }
        $HTML_Display_Text = $HTML_Display_Text.$HTML_table_row.'</tr>';

        return $HTML_Display_Text;
    }



    function Get_New_Entry_Email_Block($email_key) {

        $Session = $GLOBALS['plugins']['AttributeChangerPlugin']->Current_Session;

        if(isset($Session->Committed_New_Entries[$email_key])) {
            $HTML_table_row = sprintf('%s<br><input type="checkbox" class="New_Entry_Email" name="New_Entry_List[%s][%s]" value="include" checked>Include This Email</input><input type="hidden" name="Hidden_New_Entry_List[%s]" value="submitted">',$email_key, $email_key, 'include', $email_key);
        }
        else{
            $HTML_table_row = sprintf('%s<br><input type="checkbox" class="New_Entry_Email" name="New_Entry_List[%s][%s]" value="include">Include This Email</input><input type="hidden" name="Hidden_New_Entry_List[%s]" value="submitted">',$email_key, $email_key, 'include', $email_key);
        }
        return $HTML_table_row;        
    }



    function Get_New_Entry_Attribute_Block($email_key, $attribute_id) {

        $Session = $GLOBALS['plugins']['AttributeChangerPlugin']->Current_Session;

        $text= '';
        if(!isset($Session->New_Entry_List[$email_key][$attribute_id]) || count($Session->New_Entry_List[$email_key][$attribute_id]) == 0) {
            return $text;
        }

        $case_array = array(
            'textarea' => 'case_1',
            'textline' => 'case_1',
            'hidden' => 'case_1',
            'date' => 'case_1',

            'checkbox' => 'case_2',
            'radio' => 'case_2',
            'select' => 'case_2',

            'checkboxgroup' => 'case_3',
            );

        foreach ($Session->New_Entry_List[$email_key][$attribute_id] as $numkey => $attribute_value) {

            if(!isset($case_array[$Session->attribute_list[$attribute_id]['type']])) {
                continue;
            }

            $this_string ='';

            switch($case_array[$Session->attribute_list[$attribute_id]['type']]) {

                case "case_1": 
                    if(isset($Session->Committed_New_Entries[$email_key]) && isset($Session->Committed_New_Entries[$email_key][$attribute_id]) && $Session->Committed_New_Entries[$email_key][$attribute_id] === $attribute_value) {
                        //if the attribute value is the already selected, mark as checked
                        if($numkey == 0) {
                            $this_string = sprintf('<input type="radio" class="New_Entry_Safe_Value_Attribute_%s" name="New_Entry_List[%s][%s]" value="%s" checked>%s</input>', $attribute_id, $email_key, $attribute_id, $attribute_value, $attribute_value);
                        }
                        else{
                            $this_string = sprintf('<input type="radio" class="New_Entry_Attribute_%s" name="New_Entry_List[%s][%s]" value="%s" checked>%s</input>', $email_key, $attribute_id, $attribute_value, $attribute_value);
                        }
                         
                    }
                    else{
                        //else not yet selected so just create the input
                        if($numkey == 0) {
                            $this_string = sprintf('<input type="radio" class="New_Entry_Safe_Value_Attribute_%s" name="New_Entry_List[%s][%s]" value="%s">%s</input>', $attribute_id, $email_key, $attribute_id, $attribute_value, $attribute_value);
                        }
                        else{
                            $this_string = sprintf('<input type="radio" name="New_Entry_List[%s][%s]" value="%s">%s</input>', $email_key, $attribute_id, $attribute_value, $attribute_value);
                        }
                    }
                    //$HTML_table_row= $HTML_table_row.$HTML_attribute_value_input.'<br>';
                    break;
                
                case "case_2" :

                    if(isset($Session->Committed_New_Entries[$email_key]) && isset($Session->Committed_New_Entries[$email_key][$attribute_id]) && $Session->Committed_New_Entries[$email_key][$attribute_id] === $attribute_value) {
                        //if the attribute value is the already selected, mark as checked
                        if($numkey == 0) {
                            $this_string = sprintf('<input type="radio" class="New_Entry_Safe_Value_Attribute_%s" name="New_Entry_List[%s][%s]" value="%s" checked>%s</input>', $attribute_id, $email_key, $attribute_id, $attribute_value, $Session->attribute_list[$attribute_id]['allowed_value_ids'][$attribute_value]);
                        }
                        else{
                            $this_string = sprintf('<input type="radio" class="New_Entry_Attribute_%s" name="New_Entry_List[%s][%s]" value="%s" checked>%s</input>', $attribute_id, $email_key, $attribute_value, $attribute_value, $Session->attribute_list[$attribute_id]['allowed_value_ids'][$attribute_value]);
                        }
                         
                    }
                    else{
                        //else not yet selected so just create the input
                        if($numkey == 0) {
                            $this_string = sprintf('<input type="radio" class="New_Entry_Safe_Value_Attribute_%s" name=$Session->"New_Entry_List[%s][%s]" value="%s">%s</input>', $attribute_id, $email_key, $attribute_id, $attribute_value, $Session->attribute_list[$attribute_id]['allowed_value_ids'][$attribute_value]);
                        }
                        else{
                            $this_string = sprintf('<input type="radio" class="New_Entry_Attribute_%s" name="New_Entry_List[%s][%s]" value="%s">%s</input>', $attribute_id, $email_key, $attribute_id, $attribute_value, $Session->attribute_list[$attribute_id]['allowed_value_ids'][$attribute_value]);
                        }
                    }
                    //$HTML_table_row= $HTML_table_row.$HTML_attribute_value_input.'<br>';
                    break;

                case "case_3": 


                    if(isset($Session->Committed_New_Entries[$email_key]) && isset($Session->Committed_New_Entries[$email_key][$attribute_id]) && in_array($attribute_value, $Session->Committed_New_Entries[$email_key][$attribute_id])) {
                        //the current attribute value should already be checked

                        $this_string = sprintf('<input type="checkbox" class="New_Entry_Checkbox_Value_Attribute_%s" name="New_Entry_List[%s][%s][%s]" value="%s" checked>%s</input><br>', $attribute_id, $email_key, $attribute_id, $attribute_value, $attribute_value, $Session->attribute_list[$attribute_id]['allowed_value_ids'][$attribute_value]);
                    }
                    else{
                        //not already checked
                        $this_string = sprintf('<input type="checkbox" class="New_Entry_Checkbox_Value_Attribute_%s" name="New_Entry_List[%s][%s][%s]" value="%s">%s</input><br>', $attribute_id, $email_key, $attribute_id, $attribute_value, $attribute_value, $Session->attribute_list[$attribute_id]['allowed_value_ids'][$attribute_value]);

                    }
                    //$HTML_table_row = $HTML_table_row.$HTML_attribute_value_input.'<br>';
                   break;
                default:
                    
            }
            $text = $text.$this_string.'<br>';

        }
        return $text;
    }
                


    function Get_New_Entry_Table_Navigation_Buttons () {

        $Session = $GLOBALS['plugins']['AttributeChangerPlugin']->Current_Session;

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
            default:
                $HTML_Display_Size_Submit = '<select name="New_Entries_New_Display_Amount"><option value="10">10</option><option value="100">100</option><option value="1000">1000</option><option value="10000">10000</option><option value="all" checked>all</option>';
        }
        $HTML_Display_Size_Submit = $HTML_Display_Size_Submit.'<input type="submit" name="New_Entry_Change_Display_Amount" value="New_Entry_Change_Display_Amount"></input>';
        $HTML_Table_Navigation = $HTML_submit_buttons.$HTML_Display_Size_Submit.'</form>';
        $HTML_current_table_info = sprintf("<div>Current Block : %d of %d. Displaying %d entires per page.</div>", $Session->Current_New_Entry_Block_Number+1, $Session->New_Entires_Number_Of_Blocks, $Session->Current_New_Entries_Display_Amount);
        $HTML_Table_Navigation = $HTML_Table_Navigation.$HTML_current_table_info;

        return $HTML_Table_Navigation;
    }





















    // function Get_Modify_Email_Block($email_key) {
    //     $HTML_table_row = '<td>';
    //     if(!isset($Session->Current_user_values[$email_key])) {
    //         $Session->Get_Current_User_Values();
    //     }
         
    //     if(isset($Committed_Modify_Entries[$email_key])) {
    //         $HTML_table_row = $HTML_table_row.sprintf('<tr><td>%s<br><input type="checkbox" class="Modify_Entry_Email" name="Modify_Entry_List[%s][\'include\']" value="include" checked>Include This Email</input><input type="hidden" name="Hidden_Modify_Entry_List[%s]" value="submitted"></td>',$email_key, $email_key, $email_key);
    //     }
    //     else{
    //         $HTML_table_row = $HTML_table_row.sprintf('<tr><td>%s<br><input type="checkbox" class="Modify_Entry_Email" name="Modify_Entry_List[%s][\'include\']" value="include">Include This Email</input><input type="hidden" name="Hidden$Session->_Modify_Entry_List[%s]" value="submitted"></td>',$email_key, $email_key, $email_key);
    //     }
    //     return $HTML_table_row;
    // }

    // function Get_Modify_Attribute_Block_Current_Values($email_key, $attribute_id) {
    //     $HTML_block = '';

    //     if(!isset($Session->Current_user_values[$email_key][$attribute_id]) {
    //         return $HTML_block;
    //     }
    //     else {
    //         switch ($Session->attribute_list[$attribute_id]['type']) {
    //             case 'checkboxgroup':
    //                 foreach ($Session->Current_user_values[$email_key]['attributes'][$attribute_id] as $key => $current_single_value) {

    //                     if(isset($Session->$Committed_Modify_Entries[$email_key]) && isset($Session->$Committed_Modify_Entries[$email_key][$attribute_id])) {
    //                         if(in_array($current_single_value, $Committed_Modify_Entries[$email_key][$attribute_id])) {
    //                             $HTML_attribute_value_input = sprintf('<input type="checkbox" class="Current_Modify_Checkbox_Value_%s" name="Modify_Entry_List[%s][%s][%s]" value="%s" checked>%s</input><br>', $attribute_id, $email_key, $attribute_id, $current_single_value, $current_single_value, $Session->attribute_list[$attribute_id]['allowed_value_ids'][$current_single_value]);
    //                         }
    //                         else {
    //                             $HTML_attribute_value_input = sprintf('<input type="checkbox" class="Current_Modify_Checkbox_Value_%s" name="Modify_Entry_List[%s][%s][%s]" value="%s">%s</input><br>', $attribute_id, $email_key, $attribute_id, $current_single_value, $current_single_value, $Session->attribute_list[$attribute_id]['allowed_value_ids'][$current_single_value]);
    //                         }
    //                     }
    //                     else{
    //                         $HTML_attribute_value_input = sprintf('<input type="checkbox" class="Current_Modify_Checkbox_Value_%s" name="Modify_Entry_List[%s][%s][%s]" value="%s">%s</input><br>', $attribute_id, $email_key, $attribute_id, $current_single_value, $current_single_value, $Session->attribute_list[$attribute_id]['allowed_value_ids'][$current_single_value]);
    //                     }
    //                     $HTML_block = $HTML_block.$HTML_attribute_value_input;
                        
    //                 }

    //                 break;
                
    //             case 'checkbox'|'select'|'radio' :

    //                 if(isset($Session->$Committed_Modify_Entries[$email_key]) && isset($Session->$Committed_Modify_Entries[$email_key][$attribute_id])) {
    //                     if($Session->$Committed_Modify_Entries[$email_key][$attribute_id] === $Session->Current_user_values[$email_key][$attribute_id]) {
    //                         $HTML_attribute_value_input = sprintf('<input type="radio class="Current_Modify_Attribute_Value_%s" name="Modify_Entry_List[%s][%s]" value="%s" checked>%s</input><br>', $attribute_id, $email_key, $attribute_id, $Session->Current_user_values[$email_key][$attribute_id], $Session->attribute_list[$attribute_id]['allowed_value_ids'][$Session->Current_user_values[$email_key][$attribute_id]]);                             
    //                     }
    //                     else {
    //                         $HTML_attribute_value_input = sprintf('<input type="radio" class="Current_Modify_Checkbox_Value_%s" name="Modify_Entry_List[%s][%s]" value="%s">%s</input><br>', $attribute_name, $email_key, $attribute_id, $$Session->Current_user_values[$email_key]['attributes'][$attribute_id], $Session->Current_user_values[$email_key][$attribute_id], $Session->attribute_list[$attribute_id]['allowed_value_ids'][$Session->Current_user_values[$email_key][$attribute_id]]);
    //                     }
    //                 }
    //                 else{
    //                     //no current commited entries, so this current value should be checked
    //                     $HTML_attribute_value_input = sprintf('<input type="radio" class="Current_Modify_Checkbox_Value_%s" name="Modify_Entry_List[%s][%s]" value="%s" checked>%s</input><br>', $attribute_id, $email_key, $attribute_id, $$Session->Current_user_values[$email_key][$attribute_id], $Session->Current_user_values[$email_key][$attribute_id], $Session->Current_user_values[$email_key][$attribute_id]);
    //                 }
    //                 $HTML_block = $HTML_block.$HTML_attribute_value_input;
    //                 break;

    //             default:
    //                 if(isset($Session->$Committed_Modify_Entries[$email_key]) && isset($Session->$Committed_Modify_Entries[$email_key][$attribute_id])) {
    //                     if($Session->$Committed_Modify_Entries[$email_key][$attribute_id] === $Session->Current_user_values[$email_key][$attribute_id]) {
    //                         $HTML_attribute_value_input = sprintf('<input type="radio class="Current_Modify_Attribute_Value_%s" name="Modify_Entry_List[%s][%s]" value="%s" checked>%s</input><br>', $attribute_id, $email_key, $attribute_id, $Session->Current_user_values[$email_key][$attribute_id], $Session->Current_user_values[$email_key][$attribute_id]);                             
    //                     }
    //                     else {
    //                         $HTML_attribute_value_input = sprintf('<input type="radio" class="Current_Modify_Checkbox_Value_%s" name="Modify_Entry_List[%s][%s]" value="%s">%s</input><br>', $attribute_id, $email_key, $attribute_id, $Session->Current_user_values[$email_key][$attribute_id], $Session->Current_user_values[$email_key][$attribute_id], $Session->Current_user_values[$email_key][$attribute_id]);
    //                     }
    //                 }
    //                 else{
    //                     //no current commited entries, so this current value should be checked
    //                     $HTML_attribute_value_input = sprintf('<input type="radio" class="Current_Modify_Checkbox_Value_%s" name="Modify_Entry_List[%s][%s]" value="%s" checked>%s</input><br>', $attribute_id, $email_key, $attribute_id, $$Session->Current_user_values[$email_key][$attribute_id], $Session->Current_user_values[$email_key][$attribute_id], $Session->Current_user_values[$email_key][$attribute_id]);
    //                 }
    //                 $HTML_block = $HTML_block.$HTML_attribute_value_input;
    //                 break;
    //         }
    //         return $HTML_block;
    //     }
    // }

    function Get_Modify_Table_Header() {

        $Session = $GLOBALS['plugins']['AttributeChangerPlugin']->Current_Session;

        $HTML_Modify_Table_Header = sprintf('<form name="Modify_Entry_Submit_Form_Block__%d" action="%s" method="post"><input type="hidden" name="Modify_Entry_Form_Submitted" value="submitted">', $Session->Current_Modify_Entry_Block_Number, 'New_And_Modify_Entry_Table_Display.php');
        $HTML_Modify_Table_Header = $HTML_Modify_Table_Header.sprintf('<table id="Modify_User_Attribute_Select_Table_Block__%d">', $Session->Current_Modify_Entry_Block_Number);

        $HTML_table_row = sprintf('<tr><td>EMAIL<br><input type="button" id="Modify_Entry_Include_All_Emails" name="Modify_Entry_Include_All_Emails" value="Include All Emails" onClick="checkAll_ModifyEntry_Emails()"></input>');
        $HTML_table_row = $HTML_table_row.sprintf('<input type="button" id="Modify_Entry_Remove_All_Emails" name="Modify_Entry_Remove_All_Emails" value="Remove All Emails" onClick="removeAll_ModifyEntry_Emails()"></input></td>');

        $HTML_Modify_Table_Header = $HTML_Display_Text.$HTML_table_row;

        foreach ($Session->attribute_list as $attribute_id => $attribute_info) {
            $HTML_table_row = sprintf('<td>%s<input type="checkbox" name="Modify_Entry_Attribute_Column_Select[%s]" value="checked">',$attribute_id, $attribute_id);
            if($attribute_info['type'] === 'checkboxgroup') {
                $HTML_table_row = $HTML_table_row.sprintf('<br><input type="button" name="Modify_Entry_Include_All_Checkboxgroup_%s" value="Include All Checkboxgroup Values" onClick="checkAll_ModifyEntry_CheckboxGroup(\'%s\')"></input>', $attribute_id, $attribute_name);
                $HTML_table_row = $HTML_table_row.sprintf('<br><input type="button" name="Modify_Entry_Remove_All_Checkboxgroup_%s" value="Remove All Checkboxgroup Values" onClick="removeAll_ModifyEntry_CheckboxGroup(\'%s\')"></input></td>', $attribute_id, $attribute_id);
            }
            else{
                $HTML_table_row = $HTML_table_row.sprintf('<br><input type="button" name="Modify_Entry_Include_All_Safe_Values_%s" value="Include All Safe Values" onClick="checkAll_ModifyEntry_SafeValues(\'%s\')></input>', $attribute_id, $attribute_id);
                $HTML_table_row = $HTML_table_row.sprintf('<br><input type="button" name="Modify_Entry_Remove_All_Safe_Values_%s" value="Remove All Safe Values" onClick="removeAll_ModifyEntry_SafeValues(\'%s\')></input></td>', $attribute_id, $attribute_id);
            }
            $HTML_Modify_Table_Header = $HTML_Modify_Table_Header.$HTML_table_row;
        }
        return $HTML_Modify_Table_Header;

        
    }

    function Get_Modify_Table_Navigation_Buttons() {
        $Session = $GLOBALS['plugins']['AttributeChangerPlugin']->Current_Session;

        $HTML_Navigation = '<input type="submit" name ="Modify_Entries_Table_Submit_all" value="Submit_all">Submit_all</input>';
        if($Session->Current_New_Entry_Block_Number > 0) {
            $HTML_Navigation = $HTML_Navigation.'<input type="submit" name ="Modify_Entries_Table_Previous_Page" value="Modify_Entries_Table_Previous_Page"></input>';
        }
        if($Session->Current_New_Entry_Block_Number < $Session->New_Entires_Number_Of_Blocks - 1) {
            $HTML_Navigation = $HTML_Navigation.'<input type="submit" name ="Modify_Entries_Table_Next_Page" value="Modify_Entries_Table_Next_Page"></input>';
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
            default:
                $HTML_Display_Size_Submit = '<select name="Modify_Entries_New_Display_Amount"><option value="10">10</option><option value="100">100</option><option value="1000">1000</option><option value="10000">10000</option><option value="all" checked>all</option>';

        }
        $HTML_Display_Size_Submit = $HTML_Display_Size_Submit.'<input type="submit" name="New_Entry_Change_Display_Amount" value="New_Entry_Change_Display_Amount"></input>';
        $HTML_Navigation = $HTML_Navigation.$HTML_Display_Size_Submit.'</form>';
        $HTML_current_table_info = sprintf("<div>Current Block : %d of %d. Displaying %d entires per page.</div>", $Session->Current_Modify_Entry_Block_Number+1, $Session->Modify_Entires_Number_Of_Blocks, $Session->Current_Modify_Entries_Display_Amount);
        $HTML_Navigation = $HTML_Navigation.$HTML_current_table_info;
    }

    function Get_Modify_Entry_Table_Block() {
        $Session = $GLOBALS['plugins']['AttributeChangerPlugin']->Current_Session;
 
        $Current_Modify_Entry_Block = array_slice($Session->Modify_Entry_List, $Session->Current_Modify_Entry_Block_Number*$Session->Current_Modify_Entries_Display_Amount, $Session->Current_Modify_Entries_Display_Amount);

        //$HTML_Display_Text = Get_Modify_Table_Header().'</tr>';

        // foreach ($Current_Modify_Entry_Block as $email_key => $modify_user_attributes_and_values) {
        
        //     $HTML_table_row = '<tr><td>'.Get_Modify_Email_Block($email_key).'</td>';

        //     foreach ($Session->attribute_list as $attribute_id => $attribute_info) {
        //         $HTML_table_row = $HTML_table_row.'<td>';

        //         $HTML_table_row = $HTML_table_row.Get_Modify_Attribute_Block_Current_Values($email_key, $attribute_id);
        //         if($attribute_info['type'] === 'checkboxgroup') {
        //             $HTML_table_row = $HTML_table_row.Get_Modify_Attribute_Value_Display_Checkboxgroup_New_Vals($email_key, $attribute_id);
        //         }
        //         else{
        //             $HTML_table_row = $HTML_table_row.Get_Modify_Attribute_Value_Display_Other_Type_New_Vals($email_key, $attribute_id);
        //         }
        //         $HTML_table_row = $HTML_table_row.'</td>';
        //     }
        //     $HTML_table_row = $HTML_table_row .'</tr>';
        // }
        $HTML_Display_Text = $HTML_Display_Text.'</table>'.Get_Modify_Table_Navigation_Buttons();
        //$HTML_Display_Text = '';
        return $HTML_Display_Text;
    }

    // function Get_Modify_Attribute_Value_Display_Checkboxgroup_New_Vals($email_key, $attribute_id) {
    //     $HTML_value_block = '';

    //     if(!isset($Session->Modify_Entry_List[$email_key][$attribute_id]) || count($Session->Modify_Entry_List[$email_key][$attribute_id]) == 0) {
    //         return $HTML_value_block;
    //     }

    //     else foreach ($Session->Modify_Entry_List[$email_key][$attribute_id] as $numkey => $checkbox_value_id) {

    //         if(isset($Session->Committed_Modify_Entries[$email_key]) && isset($Session->Committed_Modify_Entries[$email_key][$attribute_id]) {

    //             if(in_array($checkbox_value_id, $Session->Committed_Modify_Entries[$email_key][$attribute_id]) ) {
    //                 $HTML_attribute_value_input = sprintf('<input type="checkbox" class="Modify_Entry_Checkbox_Value_Attribute_%s" name="Modify_Entry_List[%s][%s][%s]" value="%s" checked>%s</input><br>', $attribute_id, $email_key, $attribute_id, $checkbox_value_id, $checkbox_value_id, $Session->attribute_list[$attribute_id]['allowed_value_ids'][$checkbox_value_id]);
    //             }
    //             else{
    //                 $HTML_attribute_value_input = sprintf('<input type="checkbox" class="Modify_Entry_Checkbox_Value_Attribute_%s" name="Modify_Entry_List[%s][%s][%s]" value="%s">%s</input><br>', $attribute_id, $email_key, $attribute_id, $checkbox_value_id, $checkbox_value_id, $Session->attribute_list[$attribute_id]['allowed_value_ids'][$checkbox_value_id]);
    //             }
    //         }
    //         else{
    //             $HTML_attribute_value_input = sprintf('<input type="checkbox" class="Modify_Entry_Checkbox_Value_Attribute_%s" name="Modify_Entry_List[%s][%s][%s]" value="%s">%s</input><br>', $attribute_id, $email_key, $attribute_id, $checkbox_value_id, $checkbox_value_id, $Session->attribute_list[$attribute_id]['allowed_value_ids'][$checkbox_value_id]);
    //         }
    //         $HTML_value_block = $HTML_value_block.$HTML_attribute_value_input;
    //     }
    //     return $HTML_value_block;
    // }

    // function Get_Modify_Attribute_Value_Display_Other_Type_New_Vals($email_key, $attribute_id) {
    //     $HTML_value_block = '';
    //     if($Session->attribute_list[$attribute_id]['type'] === 'checkboxgroup') {
    //         return $HTML_value_block;
    //     }

    //     foreach ($Session->Modify_Entry_List[$email_key][$attribute_id] as $numkey => $checkbox_value_id) {

    //         if($Session->attribute_list[$attribute_id]['type'] === 'checkbox'|'select'|'radio') {
    //             $this_value = $Session->$attribute_list[$attribute_id]['allowed_value_ids'][$checkbox_value_id];
    //         }
    //         else{
    //             $this_value = $checkbox_value_id;
    //         }

    //         if(isset($Session->Committed_Modify_Entries[$email_key]) && isset($Session->Committed_Modify_Entries[$email_key][$attribute_id]) {

    //             if($checkbox_value_id === $Session->Committed_Modify_Entries[$email_key][$attribute_id]) ) {
    //                 if(($numkey == 0) && (!isset($Session->Current_User_Values[$email_key][$attribute_id]) || count($Session->Current_User_Values[$email_key][$attribute_id]) == 0)) {
    //                     $HTML_attribute_value_input = sprintf('<input type="radio" class="Modify_Entry_Safe_Value_Attribute_%s" name="Modify_Entry_List[%s][%s]" value="%s" checked>%s</input><br>', $attribute_id, $email_key, $attribute_id, $checkbox_value_id, $this_value);
    //                 }
    //                 else{
    //                     $HTML_attribute_value_input = sprintf('<input type="radio" class="Modify_Entry_%s" name="Modify_Entry_List[%s][%s]" value="%s" checked>%s</input><br>', $attribute_id, $email_key, $attribute_id, $checkbox_value_id, $this_value);
    //                 }

    //             }
    //             else{
    //                 if($numkey == 0 && !isset($Session->Current_User_Values[$email_key][$attribute_id])) {
    //                     $HTML_attribute_value_input = sprintf('<input type="radio" class="Modify_Entry_Safe_Value_Attribute_%s" name="Modify_Entry_List[%s][%s]" value="%s">%s</input><br>', $attribute_id, $email_key, $attribute_id, $checkbox_value_id, $this_value);
    //                 }
    //                 else{
    //                     $HTML_attribute_value_input = sprintf('<input type="radio" class="Modify_Entry_%s" name="Modify_Entry_List[%s][%s]" value="%s">%s</input><br>', $attribute_id, $email_key, $attribute_id, $checkbox_value_id, $this_value);
    //                 }                                   
    //             }

    //         }
    //         else{
    //             if($numkey == 0 && !isset($Session->Current_User_Values[$email_key][$attribute_id])) {
    //                 $HTML_attribute_value_input = sprintf('<input type="radio" class="Modify_Entry_Safe_Value_Attribute_%s" name="Modify_Entry_List[%s][%s]" value="%s">%s</input><br>', $attribute_id, $email_key, $attribute_id, $checkbox_value_id, $this_value);
    //             }
    //             else{
    //                 $HTML_attribute_value_input = sprintf('<input type="radio" class="Modify_Entry_%s" name="Modify_Entry_List[%s][%s]" value="%s">%s</input><br>', $attribute_id, $email_key, $attribute_id, $checkbox_value_id, $this_value);
    //             }
    //         }
    //         $HTML_value_block = $HTML_table_row.$HTML_attribute_value_input;
        
            
    //     }
    //     return $HTML_value_block;
    // }




?>