<?php


function Get_Current_Attribute_Block(&$dom, &$Session, $email_key, $attribute_id){
	$HTML_block = $dom->createElement('<div>');
	foreach ($Session->Current_User_Values[$email_key] as $attribute_id => $values) {
		
	}


    if(!isset($Session->Current_User_Values[$email_key][$attribute_id])) {
            
        return $HTML_block;
    }
    
    else {
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
        switch ($case_array[$Session->attribute_list[$attribute_id]['type']]) {
            case 'case_3':
                
                foreach ($Session->Current_User_Values[$email_key]['attributes'][$attribute_id] as $key => $current_single_value) {

                	$text = $Session->attribute_list[$attribute_id]['allowed_value_ids'][$current_single_value];
                	$name = $Modify_Entry_List[$email_key][$attribute_id][$current_single_value];
                	$value = $current_single_value;

                	$class = 'Current_Modify_Checkbox_Value_'.$attribute_id;
					$checkbox = new Input($dom, $Session->$text, 'checkbox', $value);
					$checkbox->setAttribute('class',$class);

                    if(isset($Session->Committed_Modify_Entries[$email_key]) && isset($Session->Committed_Modify_Entries[$email_key][$attribute_id])) {
                        if(in_array($current_single_value, $Session->Committed_Modify_Entries[$email_key][$attribute_id])) {
                        	$checkbox->setAttribute('checked', 'checked');
                        }
                    }
                    else{
                    	$checkbox->setAttribute('checked', 'checked');
                    }
                    $HTML_block->appendChild($checkbox);
                }
                break;
            
            case 'case_2' :
                $text = $Session->attribute_list[$attribute_id]['allowed_value_ids'][$Session->Current_User_Values[$email_key][$attribute_id]];
            	$name = $Modify_Entry_List[$email_key][$attribute_id];
            	$value = $Session->Current_User_Values[$email_key][$attribute_id];

            	$class = 'Current_Modify_Attribute_Value_'.$attribute_id;
				$radio = new Input($dom, $Session->$text, 'radio', $value);
				$radio->setAttribute('class', $class);

                if(isset($Session->Committed_Modify_Entries[$email_key]) && isset($Session->Committed_Modify_Entries[$email_key][$attribute_id])) {
                    if($Session->Committed_Modify_Entries[$email_key][$attribute_id] === $Session->Current_User_Values[$email_key][$attribute_id]) {
                          $radio->setAttribute('checked', 'checked');                  
                    }
                }
                else{
                	$radio->setAttribute('checked', 'checked'); 
                }
                $HTML_block->appendChild($radio);
                break;

            case 'case_1':

            	$text = $Session->Current_User_Values[$email_key][$attribute_id];
            	$name = $Modify_Entry_List[$email_key][$attribute_id];
            	$value = $Session->Current_User_Values[$email_key][$attribute_id];

            	$class = 'Current_Modify_Attribute_Value_'.$attribute_id;
				$radio = new Input($dom, $Session->$text, 'radio', $value);
				$radio->setAttribute('class', $class);

                if(isset($Session->Committed_Modify_Entries[$email_key]) && isset($Session->Committed_Modify_Entries[$email_key][$attribute_id])) {
                    if($Session->Committed_Modify_Entries[$email_key][$attribute_id] === $Session->Current_User_Values[$email_key][$attribute_id]) {
                          $radio->setAttribute('checked', 'checked');                  
                    }
                }
                else{
                	$radio->setAttribute('checked', 'checked'); 
                }
                $HTML_block->appendChild($radio);
                break;

            default:
                break;
        }
        return $HTML_block;
    }
}

function Get_New_Entry_Attribute_Block(&$dom, &$Session, $email_key, $attribute_id) {

        $Session = $GLOBALS['plugins']['AttributeChangerPlugin']->Current_Session;

        $domElement = $dom->createElement('td');

        if(!isset($Session->New_Entry_List[$email_key][$attribute_id]) || count($Session->New_Entry_List[$email_key][$attribute_id]) == 0) {
            return $domElement;
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
            $el;

            switch($case_array[$Session->attribute_list[$attribute_id]['type']]) {

                case "case_1": 
                	$radio = new Input($dom, $attributeValue, 'radio', 'New_Entry_List['.$email_key.']['.$attribute_id.']', $attributeValue);
                    if(isset($Session->Committed_New_Entries[$email_key]) && isset($Session->Committed_New_Entries[$email_key][$attribute_id]) && $Session->Committed_New_Entries[$email_key][$attribute_id] === $attribute_value) {
                        //if the attribute value is the already selected, mark as checked
                        
                        $radio->setAttribute('checked', 'checked');
                        if($numkey == 0) {
                        	$radio->setAttribute('class', 'New_Entry_Safe_Value_Attribute_'.$attribute_id);
                        }
                        else{
                        	$radio->setAttribute('class', 'New_Entry_Attribute__'.$attribute_id);
                        }
                         
                    }
                    else{
                        //else not yet selected so just create the input
                        if($numkey == 0) {
                            $radio->setAttribute('class', 'New_Entry_Safe_Value_Attribute_'.$attribute_id);
                        }
                        else{
                            $radio->setAttribute('class', 'New_Entry_Attribute__'.$attribute_id);
                        }
                    }
                    //$HTML_table_row= $HTML_table_row.$HTML_attribute_value_input.'<br>';
                    $el = $radio;
                    break;
                
                case "case_2" :
                	$radio = new Input($dom, $Session->attribute_list[$attribute_id]['allowed_value_ids'][$attribute_value], 'radio', 'New_Entry_List['.$email_key.']['.$attribute_id.']', $attributeValue);
                    if(isset($Session->Committed_New_Entries[$email_key]) && isset($Session->Committed_New_Entries[$email_key][$attribute_id]) && $Session->Committed_New_Entries[$email_key][$attribute_id] === $attribute_value) {
                        $radio->setAttribute('checked', 'checked');
                        if($numkey == 0) {
                            $radio->setAttribute('class', 'New_Entry_Safe_Value_Attribute_'.$attribute_id);
                        }
                        else{
                            $radio->setAttribute('class', 'New_Entry_Attribute__'.$attribute_id);
                        }
                         
                    }
                    else{
                        //else not yet selected so just create the input
                        if($numkey == 0) {
                            $radio->setAttribute('class', 'New_Entry_Safe_Value_Attribute_'.$attribute_id);
                        }
                        else{
                            $radio->setAttribute('class', 'New_Entry_Attribute__'.$attribute_id);
                        }
                    }
                    //$HTML_table_row= $HTML_table_row.$HTML_attribute_value_input.'<br>';
                    $el = $radio;
                    break;

                case "case_3": 
                	$checkbox = new Input($dom, $Session->attribute_list[$attribute_id]['allowed_value_ids'][$attribute_value], 'checkbox', 'New_Entry_List['.$email_key.']['.$attribute_id.']['.$attributeValue.']', $attributeValue);

                	$checkbox->setAttribute('class', 'New_Entry_Checkbox_Value_Attribute_'.$attribute_id);
                    if(isset($Session->Committed_New_Entries[$email_key]) && isset($Session->Committed_New_Entries[$email_key][$attribute_id]) && in_array($attribute_value, $Session->Committed_New_Entries[$email_key][$attribute_id])) {
                        $checkbox->setAttribute('checked', 'checked');
                    }
                    $el = $checkbox;
                    //$HTML_table_row = $HTML_table_row.$HTML_attribute_value_input.'<br>';
                   break;
                default:
                    
            }
            $domElement->appendChild($el);
            
        }
        return $domElement;
    }



function Get_Modify_Attribute_Value_Display_Checkboxgroup_New_Vals(&$dom, $email_key, $attribute_id) {
    $Session = $GLOBALS['plugins']['AttributeChangerPlugin']->Current_Session;
    $HTML_value_block = $dom->createElement('div');

    if(!isset($Session->Modify_Entry_List[$email_key][$attribute_id]) || count($Session->Modify_Entry_List[$email_key][$attribute_id]) == 0) {
        return $HTML_value_block;
    }

	$name = "Modify_Entry_List[".$email_key."][".$attribute_id."][".$checkbox_value_id."]";
	$class = 'Modify_Entry_Checkbox_Value_Attribute_'.$attribute_id;
	$value = $checkbox_value_id;
    $text = $Session->attribute_list[$attribute_id]['allowed_value_ids'][$checkbox_value_id];

    foreach ($Session->Modify_Entry_List[$email_key][$attribute_id] as $numkey => $checkbox_value_id) {

    	$checkbox = new Input($dom, $text, 'checkbox', $name, $value);
    	$checkbox->setAttribute('class', $class);

        if(isset($Session->Committed_Modify_Entries[$email_key]) && isset($Session->Committed_Modify_Entries[$email_key][$attribute_id])) {

            if(in_array($checkbox_value_id, $Session->Committed_Modify_Entries[$email_key][$attribute_id]) ) {
                $checkbox->setAttribute('checked', 'checked');
            }
        }
        else{
           	$checkbox->setAttribute('checked', 'checked');
        }
        $HTML_value_block->appendChild($checkbox);
    }
    return $HTML_value_block;
}


function Get_Modify_Attribute_Value_Display_Other_Type_New_Vals(&$dom, $email_key, $attribute_id) {
    $HTML_value_block = $dom->createElement('div');
    $Session = $GLOBALS['plugins']['AttributeChangerPlugin']->Current_Session;

    if($Session->attribute_list[$attribute_id]['type'] === 'checkboxgroup') {
        return $HTML_value_block;
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

    foreach ($Session->Modify_Entry_List[$email_key][$attribute_id] as $numkey => $checkbox_value_id) {

        if(!isset($case_array[$Session->attribute_list[$attribute_id]['type']])) {
            return $HTML_value_block;
        }
        $this_value ='';

        if($case_array[$Session->attribute_list[$attribute_id]['type']] === 'case_2') {
            $this_value = $Session->$attribute_list[$attribute_id]['allowed_value_ids'][$checkbox_value_id];
        }
        else{
            $this_value = $checkbox_value_id;
        }

        if(isset($Session->Committed_Modify_Entries[$email_key]) && isset($Session->Committed_Modify_Entries[$email_key][$attribute_id])) {

        	$name = 'Modify_Entry_List['.$email_key.']['.,$attribute_id.']';
        	$value = $checkbox_value_id

        	$radio = new Input($dom, $this_value, 'radio', $name, $value);
        	

            if($checkbox_value_id === $Session->Committed_Modify_Entries[$email_key][$attribute_id]) {
            	$radio->setAttribute('checked', 'checked');
                if( ($numkey == 0) && (!isset($Session->Current_User_Values[$email_key][$attribute_id]) || count($Session->Current_User_Values[$email_key][$attribute_id]) == 0) ) {
                    $radio->setAttribute('class', "Modify_Entry_Safe_Value_Attribute_".$attribute_id);
                    
                }
                else{
                	$radio->setAttribute('class', "Modify_Entry_".$attribute_id);              
                }

            }
            else{
                if($numkey == 0 && !isset($Session->Current_User_Values[$email_key][$attribute_id])) {
                    $radio->setAttribute('class', "Modify_Entry_Safe_Value_Attribute_".$attribute_id);
                }
                else{
                    $radio->setAttribute('class', "Modify_Entry_".$attribute_id);
                }                                   
            }

        }
        else{
            if($numkey == 0 && !isset($Session->Current_User_Values[$email_key][$attribute_id])) {
                $radio->setAttribute('class', "Modify_Entry_Safe_Value_Attribute_".$attribute_id);
            }
            else{
                $radio->setAttribute('class', "Modify_Entry_".$attribute_id);
            }    
        }

        $HTML_value_block->appendChild($radio);

    }
    return $HTML_value_block;
}

function GetModifyTableRow(&$dom, &$Session, $email_key) {
	$row = $dom->createElement('tr');
	$row.appendChild(GetEmailBlock());
	foreach ($Session->attribute_list as $attribute_id => $attribute_info) {
		$cell = $dom->createElement('td');
		$cell->appendChild( Get_Current_Attribute_Block($dom, $Session, $email_key, $attribute_id));
		$cell->appendChild
	}
	return $row;
}

function GetEmailBlock(&$dom, &$Session, $email_key) {

	$emailBlock = $dom->createElement('td', $email_key.'<br>');
	$include = 'include';

	$mod_new = '';
	if(!isset($Session->Current_User_Values[$email_key])) {

		$emailCheckbox = new Input($dom, '', 'checkbox', 'New_Entry_List['.$email_key.']['.$include.']', 'include');
		$emailCheckbox->setAttribute('class', 'New_Entry_Email');

		$emailHidden = new Input($dom, '', 'hidden', 'Hidden_New_Entry_List['.$email_key.']', 'submitted');

		if(isset($Session->Committed_New_Entries[$email_key])) {
			$emailCheckbox->setAttribute('checked', '1');
		}
	}
	else{
		
		$emailCheckbox = new Input($dom, '', 'checkbox', 'Modify_Entry_List['.$email_key.']['.$include.']', 'include');
		$emailCheckbox->setAttribute('class', 'Modify_Entry_Email');
		$emailHidden = new Input($dom, '', 'hidden', 'Hidden_Modify_Entry_List['.$email_key.']', 'submitted');

		if(isset($Session->Committed_Modify_Entries[$email_key])) {
			$emailCheckbox->setAttribute('checked', '1');
		}
	}

	$emailBlock->appendChild($emailCheckbox);
	$emailBlock->appendChild($emailHidden);
    return $emailBlock;
}

function BuilNewEntryDom(&$EntryList) {


	$dom = new DOMDocument('1.0', 'utf-8');

	$htmlHeader = $dom->createElement('head');

	$dom->appendChild($htmlHeader);

	$form = $dom->createElement('form');
	$form->setAttribute("name", 'New_Entry_Submit_Form_Block__'.$Session->Current_New_Entry_Block_Number);
	$form->setAttribute("method", 'post');
	$form->setAttribute("action", "");

	$htmlHeader->appendChild($form);

	$table = new Table($dom);

	$form->appendChild($table);

	GetNewEntryTableHeader(&$table);

	foreach ($New_Entry_List as $email_key => $newValues) {
		$tableRow = GetNewEntryTableRow($emailKey);
		$table->appendChild($tableRow);
	}

}

function GetNewEntryTableRow(&$dom, $email_key) {
	$row = $dom->createElement('tr');
	$row.appendChild(GetNewEntryEmailBlock());
	foreach ($Session->attribute_list as $attribute_id => $attribute_info) {
		$row.appendChild(Get_New_Entry_Attribute_Block($dom, $email_key, $attribute_id));
	}
	return $row;
}

    function Get_New_Entry_Attribute_Block(&$dom, $email_key, $attribute_id) {

        $Session = $GLOBALS['plugins']['AttributeChangerPlugin']->Current_Session;

        $domElement = $dom->createElement('td');

        if(!isset($Session->New_Entry_List[$email_key][$attribute_id]) || count($Session->New_Entry_List[$email_key][$attribute_id]) == 0) {
            return $domElement;
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

        //print_r($Session->New_Entry_List);

        //att value is actually the value id where applicable

        foreach ($Session->New_Entry_List[$email_key][$attribute_id] as $numkey => $attribute_value) {

            if(!isset($case_array[$Session->attribute_list[$attribute_id]['type']])) {
                continue;
            }

            $this_string ='';
            $el;

            switch($case_array[$Session->attribute_list[$attribute_id]['type']]) {

                case "case_1": 
                	$radio = new Input($dom, $attributeValue, 'radio', 'New_Entry_List['.$email_key.']['.$attribute_id.']', $attributeValue);
                    if(isset($Session->Committed_New_Entries[$email_key]) && isset($Session->Committed_New_Entries[$email_key][$attribute_id]) && $Session->Committed_New_Entries[$email_key][$attribute_id] === $attribute_value) {
                        //if the attribute value is the already selected, mark as checked
                        
                        $radio->setAttribute('checked', 'checked');
                        if($numkey == 0) {
                        	$radio->setAttribute('class', 'New_Entry_Safe_Value_Attribute_'.$attribute_id);
                        }
                        else{
                        	$radio->setAttribute('class', 'New_Entry_Attribute__'.$attribute_id);
                        }
                         
                    }
                    else{
                        //else not yet selected so just create the input
                        if($numkey == 0) {
                            $radio->setAttribute('class', 'New_Entry_Safe_Value_Attribute_'.$attribute_id);
                        }
                        else{
                            $radio->setAttribute('class', 'New_Entry_Attribute__'.$attribute_id);
                        }
                    }
                    //$HTML_table_row= $HTML_table_row.$HTML_attribute_value_input.'<br>';
                    $el = $radio;
                    break;
                
                case "case_2" :
                	$radio = new Input($dom, $Session->attribute_list[$attribute_id]['allowed_value_ids'][$attribute_value], 'radio', 'New_Entry_List['.$email_key.']['.$attribute_id.']', $attributeValue);
                    if(isset($Session->Committed_New_Entries[$email_key]) && isset($Session->Committed_New_Entries[$email_key][$attribute_id]) && $Session->Committed_New_Entries[$email_key][$attribute_id] === $attribute_value) {
                        $radio->setAttribute('checked', 'checked');
                        if($numkey == 0) {
                            $radio->setAttribute('class', 'New_Entry_Safe_Value_Attribute_'.$attribute_id);
                        }
                        else{
                            $radio->setAttribute('class', 'New_Entry_Attribute__'.$attribute_id);
                        }
                         
                    }
                    else{
                        //else not yet selected so just create the input
                        if($numkey == 0) {
                            $radio->setAttribute('class', 'New_Entry_Safe_Value_Attribute_'.$attribute_id);
                        }
                        else{
                            $radio->setAttribute('class', 'New_Entry_Attribute__'.$attribute_id);
                        }
                    }
                    //$HTML_table_row= $HTML_table_row.$HTML_attribute_value_input.'<br>';
                    $el = $radio;
                    break;

                case "case_3": 
                	$checkbox = new Input($dom, $Session->attribute_list[$attribute_id]['allowed_value_ids'][$attribute_value], 'checkbox', 'New_Entry_List['.$email_key.']['.$attribute_id.']['.$attributeValue.']', $attributeValue);

                	$checkbox->setAttribute('class', 'New_Entry_Checkbox_Value_Attribute_'.$attribute_id);
                    if(isset($Session->Committed_New_Entries[$email_key]) && isset($Session->Committed_New_Entries[$email_key][$attribute_id]) && in_array($attribute_value, $Session->Committed_New_Entries[$email_key][$attribute_id])) {
                        $checkbox->setAttribute('checked', 'checked');
                    }
                    $el = $checkbox;
                    //$HTML_table_row = $HTML_table_row.$HTML_attribute_value_input.'<br>';
                   break;
                default:
                    
            }
            $domElement->appendChild($el);
            
        }
        return $domElement;
    }



function GetEmailBlock(&$dom, $email_key) {
	$include = 'include';

	$emailBlock = $dom->createElement('td', $email_key.'<br>');

	if(!isset($Session->Current_User_Values[$email_key]) ? 'New':'Modify') {


		$emailCheckbox = new Input($dom, '', 'checkbox', 'New_Entry_List['.$email_key.']['.$include.']', 'include');
		$emailCheckbox->setAttribute('class', 'New_Entry_Email');
		$emailHidden = new Input($dom, '', 'hidden', 'Hidden_New_Entry_List['.$email_key.']', 'submitted');

		if(isset($Session->Committed_New_Entries[$email_key])) {

			$emailCheckbox->setAttribute('checked', '1');	
		}

		$emailBlock->appendChild($emailCheckbox);

	}
	else {

		$emailCheckbox = new Input($dom, '', 'checkbox', 'Modify_Entry_List['.$email_key.']['.$include.']', 'include');
		$emailCheckbox->setAttribute('class', 'Modify_Entry_Email');
		$emailHidden = new Input($dom, '', 'hidden', 'Hidden_Modify_Entry_List['.$email_key.']', 'submitted');

		if(isset($Session->Committed_Modify_Entries[$email_key])) {

			$emailCheckbox->setAttribute('checked', '1');	
		}

		$emailBlock->appendChild($emailCheckbox);
	}

    return $emailBlock;
}

function GetModifyEntryTableHeader_And_Append_To_Table(&$dom,  &$table) {
	$emailColumnHead = $dom->createElement('div', 'Email');

	$emailColumnCheckAll = new Button($dom, 'Include ALl Emails', 'Modify_Entry_Include_All_Emails', 'checkAll_ModifyEntry_Emails()');
	$emailColumnHead->appendChild($emailColumnCheckAll);

	$emailColumnUncheckAll = new Button($dom, 'Uncheck all emails', 'Modify_Entry_Remove_All_Emails', "removeAll_ModifyEntry_Emails()");
	$emailColumnHead->appendChild($emailColumnUncheckAll);

	$table->AddColumn($emailColumnHead);

	foreach ($Session->attribute_list as $attribute_id => $attribute_info) {

		$attributeColumnHead = new DOMElement('div',"Attribute: ".$attribute_info['name'].'<br>');

		$includeCheckbox = new DOMElement('div', 'Include this attribute');

		$includeCheckbox->setAttribute('name', 'Modify_Entry_Attribute_Column_Select['.$attribute_id.']');
		$includeCheckbox->setAttribute('value', 'checked');

		if($Session->New_Entries_Columns_To_Select[$attribute_id]== true) {
			$includeCheckbox->setAttribute('chekced', 'checked');
		}

	    if($attribute_info['type'] === 'checkboxgroup') {
	        
	        $nameIn = 'New_Entry_Include_All_Checkboxgroup_'.$attribute_id;
	    	$onClickIn = 'checkAll_ModifyEntry_CheckboxGroup('.$attribute_id.')';
	    	$textIn = "Include All Checkboxgroup";

	        $nameRem = 'New_Entry_Remove_All_Checkboxgroup_'.$attribute_id;
	    	$onClickRem = 'removeAll_ModifyEntry_CheckboxGroup('.$attribute_id.')';
	    	$textRem = "Remove All Checkboxgroup";

	    	$includeAllCheckboxgroup = new Button($domIn, $textIn, $nameIn, $onClickIn);
			$removeAllCheckboxgroup = new Button($dom, $textRem, $nameRem, $onClickRem)
			
			$includeEmailCheckbox->appendChild($includeAllCheckboxgroup);
			$includeEmailCheckbox->appendChild($removeAllCheckboxgroup);
			$table->AddColumn($includeEmailCheckbox);

	    }
	    else{

	   		$nameSafeIn = 'Modify_Entry_Include_All_Safe_Values_'.$attribute_id;
	    	$onClickSafeIn = 'checkAll_ModifyEntry_CheckboxGroup('.$attribute_id.')';
	    	$textSafeIn = "Include all safe values";

	   		$nameSafeCheckedIn = 'Modify_Entry_Include_All_Safe_Or_Checked'.$attribute_id;
	    	$onClickSafeCheckIn = 'checkAll_ModifyEntry_SafeValues_OrChecked("'.$attribute_id'")';
	    	$textSafeIn = "Include all safe or checked values";



	        $nameSafeRem = 'New_Entry_Remove_All_Checkboxgroup_'.$attribute_id;
	    	$onClickSafeRem = 'removeAll_ModifyEntry_CheckboxGroup('.$attribute_id.')';
	    	$textSafeRem = "Remove All Checkboxgroup";

	        $nameSafeCheckRem = 'Modify_Entry_Remove_All_Safe_Or_Checked'.$attribute_id;
	    	$onClickSafeRem = 'removeAll_ModifyEntry_SafeValues_OrChecked("'.$attribute_id'")';
	    	$textSafeRem = "Remove All safe or checked";


	    	$includeAllSafe = new Button($dom, $textSafeIn, $nameSafeIn , $onClickSafeIn);
	        $includeAllSafeOrChecked = new Button($dom, $textSafeCheckedIn, $nameSafeCheckedIn , $onClickSafeCheckedIn);
	        $removeAllSafe = new Button($dom, $textSafeRem, $nameSafeRem , $onClickSafeRem);
	        $removeAllSafeOrChecked = new Button($dom, $textSafeCheckedRem, $nameSafeCheckedRem , $onClickSafeCheckedRem);

	    	$includeEmailCheckbox->appendChild($includeAllSafe);
			$includeEmailCheckbox->appendChild($includeAllSafeOrChecked);

			$includeEmailCheckbox->appendChild($removeAllSafe);
			$includeEmailCheckbox->appendChild($removeAllSafeOrChecked);
			$table->AddColumn($includeEmailCheckbox);
	    }
	}
}

function GetNewEntryTableHeader_And_Append_To_Table(&$dom,  &$table) {
	$emailColumnHead = $dom->createElement('div', 'Email');

	$emailColumnCheckAll = new Button($dom, 'Include ALl Emails', 'New_Entry_Include_All_Emails', 'checkAll_NewEntry_Emails()');
	$emailColumnHead->appendChild($emailColumnCheckAll);

	$emailColumnUncheckAll = new Button($dom, 'Uncheck all emails', 'New_Entry_Remove_All_Emails', "removeAll_NewEntry_Emails()");
	$emailColumnHead->appendChild($emailColumnUncheckAll);

	$table->AddColumn($emailColumnHead);

	foreach ($Session->attribute_list as $attribute_id => $attribute_info) {

		$attributeColumnHead = new DOMElement('div',"Attribute: ".$attribute_info['name'].'<br>');

		$includeCheckbox = new DOMElement('div', 'Include this attribute');

		$includeCheckbox->setAttribute('name', 'New_Entry_Attribute_Column_Select['.$attribute_id.']');
		$includeCheckbox->setAttribute('value', 'checked');

		$includeEmailCheckbox->checked = $Session->New_Entries_Columns_To_Select ? true : false;

	    if($attribute_info['type'] === 'checkboxgroup') {
	        
	        $new_entry_include_all = 'New_Entry_Include_All_Checkboxgroup_'.$attribute_id;
	    	$includeAllCheckboxgroup = new Button($dom, "Include All Checkboxgroup", $new_entry_include_all, 'checkAll_NewEntry_CheckboxGroup("'.$attribute_id.'")');
			$removeAllCheckboxgroup = new Button($dom, "Remove All Checkboxgroup", $new_entry_include_all, 'removeAll_NewEntry_CheckboxGroup("'.$attribute_id.'")')
			
			$includeEmailCheckbox->appendChild($includeAllCheckboxgroup);
			$includeEmailCheckbox->appendChild($removeAllCheckboxgroup);
			$table->AddColumn($includeEmailCheckbox);

	    }
	    else{
	    	$includeAllSafe = new Button($dom, "Include all safe values", "New_Entry_Include_All_Safe_Values_".$attribute_id, 'checkAll_NewEntry_SafeValues("'.$attribute_id'")');
	        $includeAllSafeOrChecked = new Button($dom, "Include all safe values or checked", 'New_Entry_Include_All_Safe_Values_Or_Checked'.$attribute_id, 'checkAll_NewEntry_SafeValues_OrChecked("'.$attribute_id'")');
			$removeAllSafe = new Button($dom, "Remove all safe values", 'name="New_Entry_Remove_All_Safe_Values_"'.$attribute_id, 'removeAll_NewEntry_SafeValues("'.$attribute_id'")');
	        $removeAllSafeOrChecked = new Button($dom, "Remove all safe values or checked", 'New_Entry_Remove_All_Safe_Values_Or_Checked'.$attribute_id, 'removeAll_NewEntry_SafeValues_OrChecked("'.$attribute_id'")');
	    	
	    	$includeEmailCheckbox->appendChild($includeAllSafe);
			$includeEmailCheckbox->appendChild($includeAllSafeOrChecked);

			$includeEmailCheckbox->appendChild($removeAllSafe);
			$includeEmailCheckbox->appendChild($removeAllSafeOrChecked);
			$table->AddColumn($includeEmailCheckbox);
	    }
	}
}

class Button extends DOMElement {


	function __construct($dom, $text, $name, $onClick)
	{
		$this = $dom->createElement('button', $text);
		$this->setAttribute('name', $name);
		$this->setAttribute('onClick', $onClick);
	}
}

class Input extends DOMElement {


	function __construct($dom, $text, $type, $name, $value)
	{
		$this = $dom->createElement('input', $text);
		$this->setAttribute('type', $type);
		$this->setAttribute('name', $name);
		$this->setAttribute('onClick', $onClick);
	}
}

class Table extends DOMElement{


	$firstRow;

	function __construct($dom)
	{
		$this= $dom->createElement('table');
		$firstRow = $dom->createElement('tr');
		$this->appendChild($firstRow);
	}

	function AddColumn($domElement) {
		if($domElement instanceof DOMElement){
			$columnHead = $dom->createElement('td');
			$firstRow->appendChild($columnHead);
			$columnHead->appendChild($domElement);
		}
	}

}


class HTMLElement{ 



	function GetTag() {
		return $this->tag;
	}

	// function ChangeTag() {

	// }

	function AddChild($child) {
		if($child instanceof HTMLElement){
			$this->children[] = $child;
			$child->parent = $this;
			return true;
		}
		return false;
	}

	//must pass the object pointer
	function RemoveChild($child) {
		if($child instanceof HTMLElement){
			if(($key = array_search($child, $this->children))!=false) {

				$this->children[$key]->RemoveNode();
				unset($this->children[$key]);
				$HTML = null;
				return true;
			}
		}
		return false;
	}


	function GetChildren() {
		$returnArray = array();
		foreach ($this->children as $child) {
			$returnArray[] = $child;
		}
		return $returnArray;
	}

	function RemoveNode() {
		foreach ($this->children as $numKey => $child) {
			$child->RemoveNode;
			unset($this->children[$numKey]);
		}
		$thisIndex = array_search($this, $this->parent->children);
		unset($this->parent->children[$thisIndex]);
		$this->free();
		return true;
	}

	function HardCopy() {
		$newNode = new HTMLElement($this);

		foreach ($this->children as $numKey => $child) {
			$newNode->children[] = $child->HardCopy();
		}
		return $newNode;
	}



	function ChangeName($newName) {
		$newName= htmlspecialchars($newName);
		if($this->name === $newName) {
			return false;
		}
		$this->name = $newName;
		return true;
	}

	function GetName() {
		return $this->name;
	}



	function AddClass($newClass) {
		$newClass = htmlspecialchars($newClass);

		if(in_array($newClass, $this->class)) {
			return false;
		}
		$this->class[] = $newClass;
		return true;
	}

	function RemoveClass($class) {
		$class = htmlspecialchars($class);
		if(($key = array_search($class, $this->class)) != false) {
			unset($this->class[$key]);
			return true;
		}
		return false;
	}

	function GetClass() {
		$returnArray = array();
		foreach ($this->class as $key => $class) {
			$returnArray[] = $class;
		}
		return $returnArray;
	}



	function AddAttribute($newAttribute, $newAttributeValue) {
		$newAttribute = htmlspecialchars($newAttribute);

		$newAttributeValue = htmlspecialchars($newAttributeValue);

		$this->attributes[$newAttribute] = $newAttributeValue;
		
	}

	function RemoveAttribute($attribute) {
		$attribute = htmlspecialchars($attribute);

		if(isset($this->attributes[$attribute])) {
			unset($this->attributes[$attribute]);
			return true;
		}
		return false;
	}

	function AddAttributeValues($attribute, $value) {
		$attribute = htmlspecialchars($attribute);

		if(isset($this->attributes[$attribute])) {

			$value = htmlspecialchars($value);

			if(!in_array($value, $this->attributes[$attribute])) {
				$this->attributes[$attribute] = $value;
			}
			
			return true;
		}
		return false;
	}


	function GetAttributeValues($attribute) {
		$attribute = htmlspecialchars($attribute);
		if(isset($this->attributes[$attribute])){
		
			return $this->attributes[$attribute];
		}
		return false;
	}

	function AddStyleProperty($property, $newValue) {
		$property = htmlspecialchars($property);
		if(!isset($this->stylep[$property])) {
			$this->style[$property] = htmlspecialchars($newValue);
			return true;
		}
		return false;
	}

	function ChangeStyleProperty($property, $newValue) {
		$property = htmlspecialchars($property);
		if(isset($this->style[$property])) {
			$this->style[$property] = htmlspecialchars($newValue);
			return true;
		}
		return false;
	}

	function RemoveStyleProperty() {
		$property = htmlspecialchars($property);
		if(isset($this->style[$property])) {
			unset($this->style[$property]);
			return true;
		}
		return false;
	}

	$children[];
	$parent;

	$tag;

	$class[];
	$attributes[];
	$style[];

	$innerText;

	$HTML;

	function GetChildHTML() {
		$returnString = '';
		foreach ($this->children as $child) {
			$returnString = $returnString.$child->GetHTML();
		}
		$returnString = $returnString.'</'.$this->tag.'>';
		return $returnString;
	}

	function GetThisHTML() {

		$this->HTML = '<'.$this->tag.' ';
		foreach ($this->attributeArray as $name => $variableName) {

			if($name == 'attributes') {
				if(isset($this->attributes) && is_array($this->attributes)) {
					foreach ($this->attributes as $attributeName => $attributeValue) {
						$this->HTML = $this->HTML.$attributeName.'="'.$attributeValue.'" ';
					}
				}
			}
			if($name = 'class') {
				if(isset($this->class) && is_array($this->class)) {
					$this->HTML = $this->HTML.'class="';
					foreach ($this->class as $className) {
						$this->HTML = $this->HTML.$className.' ';
					}
					$this->HTML = $this->HTML.'" ';
				}
			}

			if($name = 'style') {
				if(isset($this->style) && is_array($this->style)) {
					$this->HTML = $this->HTML.'style="';
					foreach ($this->style as $styleName => $styleValue) {
						$this->HTML = $this->HTML.$styleName.':'.$styleValue.';';
					}
					$this->HTML = $this->HTML.'" ';
				}
			}
		}
		$this->HTML = $this->HTML.'>'.$this->innerText;
	}

	function GetHTML() {

		if($this->HTML == null) {
			$this->GetThisHTML();
		}
		$returnString = $this->HTML.$this->GetChildHTML();
		$returnString = $returnString.'</'.$this->tag.'>';
		return $returnString;

	}



	$attributeArray = array(
		'class'=>$this->class, 
		'attributes'=>$this->attributes, 
		'style'=>$this->style,
	);

	function __construct($tag, $attributes, $parent, $class, $styles, $innerText)
	{
		if(!$tag) {
			$this->tag = 'html';
		}
		$this->tag = $tag;
		if($attributes != null && is_array($attributes)) {
			foreach ($attributes as $name => $value) {
				$this->AddAttribute($name, $value);
			}
		}

		if($class != null && is_array($class)) {
			foreach ($class as $name) {
				$this->AddClass($name);
			}
		}

		if($styles != null && is_array($styles)) {
			foreach ($styles as $property => $value) {
				$this->AddStyleProperty($property, $value);
			}
		}

		if($innerText) {
			if($innerText instanceof string) {
				$this->innerText = $innerText;
			}
			else{
				$this->innerText = '';
			}
		}
		else{
			$this->innerText = '';
		}

		$this->children = array();

		if($parent) {
			if($parent instanceof HTMLElement) {
				$this->parent = $parent;
			}
			else{
				$this->parent = null;
			}
		}
		else{
			$this->parent = null;
		}
		return $this;
	}



	// function ChangeValue($variableName, $newValues) {
	// 	if(!$variableName || !is_string($variableName) !$newValues || !is_array($newValues) ) {
	// 		return false;
	// 	}
	// 	if(isset($this->attributeAttay[$variableName])) {
	// 		$unsucessfulNewValues = array();
	// 		$string = '';
	// 		foreach ($newValues as $value) {
	// 			# code...
	// 		}
	// 		eval('\$this->'$variableName.'= ')
	// 	}
	// 	$HTML = null;
	// }
}

/**
* 
*/
class Head extends HTMLElement
{

	function __construct()
	{
		parent::__construct('head');
	}
}

class Body extends HTMLElement
{
	
	function __construct()
	{
		parent::__construct('body');
	}
}


class Form extends HTMLElement
{
	
	function __construct($action, $method, $name)
	{
		$attributeArray = array();
		if($method == 'post' || $method == 'get') {
			$attributeArray['method'] = $method;
		}
		$attributeArray['name'] = $name;
		parent::__construct('form', $attributeArray);
	}
}



class Table extends HTMLElement
{
	
	$columnFormat = array();

	function GetHTML() {

		if($this->HTML == null) {
			$this->GetThisHTML();
			$this->HTML = $this->HTML.'<tr>';
			foreach ($columnFormat as $columnHeader) {
				$this->HTML = $this->HTML.'<td>'.$columnHeader->GetHTML().'</td>';
			}
			$this->HTML = $this->HTML.'</tr>';
		}

		$returnString = $this->HTML.$this->GetChildHTML();

		$returnString = $returnString.'</'.$this->tag.'>';
		return $returnString;
	}

	function AddChild($child) {
		if($child instanceof Row) {
			$this->children[] = $child;
			$child->parent = $this;
			return true;
		}
		return false;
	}

	function AddColumn($columnHeader) {
		if($columnHeader instanceof HTMLElement){
			$this->columnFormat[] = $columnHeader;
			return true;
		}
		return false;
	}

	function ModifyColumn($currentHeader, $newHeader) {
		if($currentHeader instanceof HTMLElement && $newHeader instanceof HTMLElement){
			$index = array_search($currentHeader, $this->columnFormat);
			if($index != false) {
				 $this->columnFormat = $newHeader;
				 return true;
			}
		}
		return false;
	}


	function RemoveColumn($columnHeader) {

		$index = array_search($columnHeader, $this->columnFormat);
		if($index == false) {
			return false;
		}

		$columnHeader->RemoveNode();

		foreach ($children as $child) {
			if($child->GetTag() == 'tr') {
				$toRemove = $child->children[$index];
				$child->RemoveChild($toRemove);
			}
		}
	}

	function __construct()
	{
		parent::__construct('table');
	}
}

class Row extends HTMLElement
{
	
	function AddChild($child) {
		if($child instanceof Cell) {
			$this->children[] = $child;
			$child->parent = $this;
			return true;
		}
		return false;
	}

	function __construct()
	{
		parent::__construct('tr');
	}
}

class Cell extends HTMLElement
{
	
	function __construct()
	{
		parent::__construct('td');
	}
}




class Div extends HTMLElement {

	function __construct()
	{
		parent::__construct('div');
	}
}

class List extends HTMLElement {


	function AddChild($child){
		if($child instanceof li){
			$this->children[] = $child;
			$child->parent = $this;
			return true;
		}
		return false;
	}

	function __construct()
	{
		parent::__construct('ul');
	}
}


class li extends HTMLElement {

	function __construct()
	{
		parent::__construct('li');
	}
}

class Button extends HTMLElement {


	function __construct()
	{
		parent::__construct('button', array('type'=>'button'));
	}
}





class InputTextline extends HTMLElement {


	function GetHTML() {

	}

	function __construct()
	{
		parent::__construct('input', array('type'=>'text'));
	}
}

class InputSubmit extends HTMLElement {

	function __construct()
	{
		parent::__construct('input', array('type'=>'submit'));
	}
}

class InputRadio extends HTMLElement {

	$checked = false;
	function GetThisHTML() {

		$this->HTML = '<'.$this->tag.' ';
		foreach ($this->attributeArray as $name => $variableName) {

			if($name == 'attributes') {
				if(isset($this->attributes) && is_array($this->attributes)) {
					foreach ($this->attributes as $attributeName => $attributeValue) {
						$this->HTML = $this->HTML.$attributeName.'="'.$attributeValue.'" ';
					}
				}
			}
			if($name = 'class') {
				if(isset($this->class) && is_array($this->class)) {
					$this->HTML = $this->HTML.'class="';
					foreach ($this->class as $className) {
						$this->HTML = $this->HTML.$className.' ';
					}
					$this->HTML = $this->HTML.'" ';
				}
			}

			if($name = 'style') {
				if(isset($this->style) && is_array($this->style)) {
					$this->HTML = $this->HTML.'style="';
					foreach ($this->style as $styleName => $styleValue) {
						$this->HTML = $this->HTML.$styleName.':'.$styleValue.';';
					}
					$this->HTML = $this->HTML.'" ';
				}
			}
		}
		$this->HTML = $this->HTML.($this->checked ? 'cheked':'').'>'.$this->innerText;
	}

	function __construct()
	{
		parent::__construct('input', array('type'=>'radio'));
	}
}

class InputCheckbox extends HTMLElement {

	$checked = false;

	function GetThisHTML() {

		$this->HTML = '<'.$this->tag.' ';
		foreach ($this->attributeArray as $name => $variableName) {

			if($name == 'attributes') {
				if(isset($this->attributes) && is_array($this->attributes)) {
					foreach ($this->attributes as $attributeName => $attributeValue) {
						$this->HTML = $this->HTML.$attributeName.'="'.$attributeValue.'" ';
					}
				}
			}
			if($name = 'class') {
				if(isset($this->class) && is_array($this->class)) {
					$this->HTML = $this->HTML.'class="';
					foreach ($this->class as $className) {
						$this->HTML = $this->HTML.$className.' ';
					}
					$this->HTML = $this->HTML.'" ';
				}
			}

			if($name = 'style') {
				if(isset($this->style) && is_array($this->style)) {
					$this->HTML = $this->HTML.'style="';
					foreach ($this->style as $styleName => $styleValue) {
						$this->HTML = $this->HTML.$styleName.':'.$styleValue.';';
					}
					$this->HTML = $this->HTML.'" ';
				}
			}
		}
		$this->HTML = $this->HTML.($this->checked ? 'cheked':'').'>'.$this->innerText;
	}
	function __construct()
	{
		parent::__construct('input', array('type'=>'checkbox'));
	}
}

class InputHidden extends HTMLElement {

	function __construct()
	{
		parent::__construct('input', array('type'=>'hidden'));
	}
}



class Select extends HTMLElement {

	function AddChild($child) {
		if($child instanceof Option) {
			$this->children[] = $child;
			$child->parent = $this;
		}
	}

	function __construct()
	{
		parent::__construct('select');
	}
}

class Option extends HTMLElement {

	function __construct()
	{
		parent::__construct('option');
	}
}



?>