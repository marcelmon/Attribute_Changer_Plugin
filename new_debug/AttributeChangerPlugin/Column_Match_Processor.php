<?php


if(isset($_POST['File_Column_Match_Submit'])) {
	
    if(!isset($_POST['attribute_to_match'])) {
        //shouldnt happen .... else user needs to be WARNEDDDDD
        print("SHITITITIT");
    }

    $attribute_changer->Retreive_And_Unserialize();


    $Session = $attribute_changer->Current_Session;

    //print('<br><br>asdasdad<br>');
    //print_r($Session->attribute_list);

    $att_list = $Session->attribute_list;

    //print_r($att_list);

    if(!isset($_POST['attribute_to_match']['email'])) {

        $display_html = "<html><body>no email column selected</body></html>";
    }
    else{
        $FILE_LOCATION = $attribute_changer->Current_Session->Get_File_Location();

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

        //print_r($_POST['attribute_to_match']);

        while(!feof($fp)) {
            //read 10kb at a time

            $current_line = fgets($fp);
            $current_line = explode(',', $current_line);
            if(count($current_line) != $number_columns) {

                //SOME WEIRD ERROR, CHECK EOF
            }

            $new_attribute_value_array = array();

            foreach ($_POST['attribute_to_match'] as $attribute_id => $col_number) {

                $current_line[$col_number] = str_replace('"', '', $current_line[$col_number]);

                if(isset($current_line[$col_number]) && $current_line[$col_number] != '') {
                    //print('<br>'.$current_line[$col_number]);

                    //print('<br>attribute id '.$attribute_id.'<br>atrribute type '.$att_list[$attribute_id]['type'].'<br>');

                    if($attribute_id === 'email') {
                        $new_attribute_value_array[$attribute_id] = $current_line[$col_number];

                    }
                    

                    else if($att_list[$attribute_id]['type'] === "radio" || $att_list[$attribute_id]['type'] === "checkboxgroup" || $att_list[$attribute_id]['type'] === "select") {
                        

                        $new_attribute_value_array[$attribute_id] = explode(';', $current_line[$col_number]);

                        //print_r($new_attribute_value_array[$attribute_id]);
                    }
                    else {
                        $new_attribute_value_array[$attribute_id] = $current_line[$col_number];
                    }
                    
                }
            }
            //print("GARAGARARARA<br>GARARARARA<br>");
            //print_r($new_attribute_value_array);
            //print("<br>");
            if(isset($new_attribute_value_array['email'])) {
                $attribute_changer->Test_Entry($new_attribute_value_array);
                //print_r("<br><br>".$new_attribute_value_array);
            }
        }


        fclose($fp);
        $display_html ='<html><body>';
        $new_entry_table_html = '';

        include_once(PLUGIN_ROOTDIR.'/AttributeChangerPlugin/Display_Adjustment_Functions.php');

        include_once(PLUGIN_ROOTDIR.'/AttributeChangerPlugin/Display_Functions.php');

        print("<br><br>");
        print_r($Session->New_Entry_List);

        print("<br><br>");
        if(Initialize_New_Entries_Display()!=null) {
            $display_html = $display_html.Get_New_Entry_Table_Block().'</body></html>';
        }
        else{
            // if(Initialize_Modify_Entries_Display()!=null) {
            //     $display_html = $display_html.Get_Modify_Entry_Table_Block().'</body></html>';
            // }
            // else{
            //     $display_html = $display_html.'There is nothing new or to modify</body></html>'
            // }
        }
    }
    $attribute_changer->Serialize_And_Store();
    print($display_html);

}


?>