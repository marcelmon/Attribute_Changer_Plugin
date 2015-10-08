<?php



// class Button extends DOMElement {


//     public function __construct(&$dom, $text, $name, $onClick)
//     {
//         $this = $dom->createElement('button', $text);
//         $this->setAttribute('name', $name);
//         $this->setAttribute('onClick', $onClick);
//     }
// }


class Button_1 {

    
    public function __construct(&$dom, $text, $name, $onClick)
    {
        $this->DOMElement = $dom->createElement('button', $text);
        $this->DOMElement->setAttribute('name', $name);
        $this->DOMElement->setAttribute('onClick', $onClick);
        
    }
    public $DOMElement;

    public function GetDOM() {
        return $this->DOMElement;
    }
}

class Input {


    public function __construct(&$dom, $text, $type, $name, $value)
    {
        $this->DOMElement = $dom->createElement('input', $text);
        $this->DOMElement->setAttribute('type', $type);
        $this->DOMElement->setAttribute('name', $name);
        $this->DOMElement->setAttribute('value', $value);
    }

    public $DOMElement;

    public function GetDOM() {
        return $this->DOMElement;
    }
}

class Table_1 {


    public $firstRow;

    public $DOMElement;

    public function GetDOM() {
        return $this->DOMElement;
    }

    public function __construct(&$dom)
    {
        $this->DOMElement = $dom->createElement('table');
        $this->firstRow = $dom->createElement('tr');
        $this->DOMElement->appendChild($this->firstRow);

    }

    public function AddColumn(&$dom, $domElement) {

        if($domElement instanceof DOMElement){

            $columnHead = $dom->createElement('td');
            $this->firstRow->appendChild($columnHead);
            $columnHead->appendChild($domElement);
        }
    }

}


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
//------------------1
function BuilNewEntryDom() {

    $Session = $GLOBALS['plugins']['AttributeChangerPlugin']->Current_Session;

    $dom = new DOMDocument('1.0', 'utf-8');

    $htmlHeader = $dom->createElement('head');

    $dom->appendChild($htmlHeader);

    $form = $dom->createElement('form');
    $form->setAttribute("name", 'New_Entry_Submit_Form_Block__'.$Session->Current_New_Entry_Block_Number);
    $form->setAttribute("method", 'post');
    $form->setAttribute("action", "");

    $hiddenFormData = new Input($dom, '', 'hidden', "New_Entry_Form_Submitted", 'submitted');
    $form->appendChild($hiddenFormData->GetDOM());

    $htmlHeader->appendChild($form);

    $_table = new Table_1($dom);

    $table = $_table->GetDOM();

    $form->appendChild($table);

    GetNewEntryTableHeader_And_Append_To_Table($dom,  $_table);

    $Current_New_Entry_Block = array_slice($Session->New_Entry_List, $Session->Current_New_Entry_Block_Number*$Session->Current_New_Entries_Display_Amount, $Session->Current_New_Entries_Display_Amount);

    foreach ($Current_New_Entry_Block as $email_key => $new_user_attributes_and_values) {

        $tableRow = GetNewEntryTableRow($dom, $email_key);

        $table->appendChild($tableRow);
    }

    $buttons = Get_New_Entry_Table_Navigation_Buttons($dom);

    $form->appendChild($buttons);
    return $dom;
}


//------------------2
function GetNewEntryTableHeader_And_Append_To_Table(&$dom,  &$table) {

    $Session = $GLOBALS['plugins']['AttributeChangerPlugin']->Current_Session;
    $emailColumnHead = $dom->createElement('div', 'Email');

    $emailColumnCheckAll = new Button_1($dom, 'Include ALl Emails', 'New_Entry_Include_All_Emails', 'checkAll_NewEntry_Emails()');
    $emailColumnCheckAll->GetDOM()->setAttribute('type', 'button');
    $checkAll = $emailColumnCheckAll->GetDOM();

    $emailColumnHead->appendChild($checkAll);

    $emailColumnUncheckAll = new Button_1($dom, 'Uncheck all emails', 'New_Entry_Remove_All_Emails', "removeAll_NewEntry_Emails()");
    $emailColumnUncheckAll->GetDOM()->setAttribute('type', 'button');
    $uncheckAll = $emailColumnUncheckAll->GetDOM();



    $emailColumnHead->appendChild($uncheckAll);

        
    $table->AddColumn($dom, $emailColumnHead);


    foreach ($Session->attribute_list as $attribute_id => $attribute_info) {

        $attributeColumnHead = $dom->createElement('div',"Attribute: ".$attribute_info['name']);

        $includeCheckbox = new Input($dom, 'Include this Attribute', 'checkbox', 'New_Entry_Attribute_Column_Select['.$attribute_id.']', 'checked');

        if(in_array($attribute_id, $Session->New_Entries_Columns_To_Select)) {
            $includeCheckbox->GetDOM()->setAttribute('checked', 'checked');
            $checkbox->GetDOM()->setAttribute('class', $current_class.' Checked_Value'); 
        }

        $attributeColumnHead->appendChild($includeCheckbox->GetDOM());

        if($attribute_info['type'] == 'checkboxgroup') {
            
            $new_entry_include_all = 'New_Entry_Include_All_Checkboxgroup_'.$attribute_id;
            $includeAllCheckboxgroup = new Button_1($dom, "Include All Checkboxgroup", $new_entry_include_all, 'checkAll_NewEntry_CheckboxGroup("'.$attribute_id.'")');
            $removeAllCheckboxgroup = new Button_1($dom, "Remove All Checkboxgroup", $new_entry_include_all, 'removeAll_NewEntry_CheckboxGroup("'.$attribute_id.'")');
            
            $includeAllCheckboxgroup->GetDOM()->setAttribute('type', 'button');
            $removeAllCheckboxgroup->GetDOM()->setAttribute('type', 'button');


            $attributeColumnHead->appendChild($includeAllCheckboxgroup->GetDOM());
            $attributeColumnHead->appendChild($removeAllCheckboxgroup->GetDOM());
            $table->AddColumn($dom, $attributeColumnHead);

        }
        else{
            $includeAllSafe = new Button_1($dom, "Include all safe values", "New_Entry_Include_All_Safe_Values_".$attribute_id, 'checkAll_NewEntry_SafeValues("'.$attribute_id.'")');
            $includeAllSafeOrChecked = new Button_1($dom, "Include all safe values or checked", 'New_Entry_Include_All_Safe_Values_Or_Checked'.$attribute_id, 'checkAll_NewEntry_SafeValues_OrChecked("'.$attribute_id.'")');
            $removeAllSafe = new Button_1($dom, "Remove all safe values", 'New_Entry_Remove_All_Safe_Values_'.$attribute_id, 'removeAll_NewEntry_SafeValues("'.$attribute_id.'")');
            $removeAllSafeOrChecked = new Button_1($dom, "Remove all safe values or checked", 'New_Entry_Remove_All_Safe_Values_Or_Checked'.$attribute_id, 'removeAll_NewEntry_SafeValues_OrChecked("'.$attribute_id.'")');
            
            $includeAllSafe->GetDOM()->setAttribute('type', 'button');
            $includeAllSafeOrChecked->GetDOM()->setAttribute('type', 'button');
            $removeAllSafe->GetDOM()->setAttribute('type', 'button');
            $removeAllSafeOrChecked->GetDOM()->setAttribute('type', 'button');

            $attributeColumnHead->appendChild($includeAllSafe->GetDOM());
            $attributeColumnHead->appendChild($includeAllSafeOrChecked->GetDOM());

            $attributeColumnHead->appendChild($removeAllSafe->GetDOM());
            $attributeColumnHead->appendChild($removeAllSafeOrChecked->GetDOM());
            $table->AddColumn($dom, $attributeColumnHead);
        }
    }
}

// //---------------------3

function GetNewEntryTableRow(&$dom, $email_key) {
    $Session = $GLOBALS['plugins']['AttributeChangerPlugin']->Current_Session;
    $row = $dom->createElement('tr');


    $row->appendChild(GetEmailBlock($dom, $email_key));

    foreach ($Session->attribute_list as $attribute_id => $attribute_info) {

        $cell = $dom->createElement('td');
        $cell->appendChild(Create_Attribute_Table_Elements($dom, $attribute_id, $email_key));

        $row->appendChild($cell);
    }
    return $row;
}

// //-------------------3.5

function GetEmailBlock(&$dom, $email_key) {

    $include = 'include';

    $emailBlock = $dom->createElement('td', $email_key);
    $Session = $GLOBALS['plugins']['AttributeChangerPlugin']->Current_Session;

    if(!isset($Session->Current_User_Values[$email_key])) {
        $class = 'New_Entry';
        $checkbox = new Input($dom, '', 'checkbox', 'New_Entry_List['.$email_key.']['.$include.']', 'include');
        $emailHidden = new Input($dom, '', 'hidden', 'Hidden_New_Entry_List['.$email_key.']', 'submitted');

    }
    else {
        $class = 'Modify_Entry Current_Value';
        $checkbox = new Input($dom, '', 'checkbox', 'Modify_Entry_List['.$email_key.']['.$include.']', 'include');
        $emailHidden = new Input($dom, '', 'hidden', 'Hidden_Modify_Entry_List['.$email_key.']', 'submitted');
    }

    $class .= ' Email_Block';

    if(isset($Session->Committed_Modify_Entries[$email_key]) || isset($Session->Committed_New_Entries[$email_key])) {

        $checkbox->GetDOM()->setAttribute('checked', 'checked');  
        $class .= ' Selected';
    }

    $checkbox->GetDOM()->setAttribute('class', $class); 
    $emailBlock->setAttribute('class', $class);

    $emailBlock->appendChild($emailHidden->GetDOM());
    $emailBlock->appendChild($checkbox->GetDOM());
    return $emailBlock;
}



// //----------------------4
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
    $domList = $dom->createElement("ul");

    foreach ($Session->New_Entry_List[$email_key][$attribute_id] as $numkey => $attribute_value) {

        $listMemeber = $dom->createElement('li');

        if(!isset($case_array[$Session->attribute_list[$attribute_id]['type']])) {
            continue;
        }        $this_string ='';

        $el;
        $class = 'New_Entry';
        $class .= ' attribute_'.$attribute_id;

        switch($case_array[$Session->attribute_list[$attribute_id]['type']]) {

            case "case_1": 

                $listMemeber->appendChild($dom->createElement("div", $attribute_value));
                $radio = new Input($dom, $attribute_value, 'radio', 'New_Entry_List['.$email_key.']['.$attribute_id.']', $attribute_value);
                $radio->GetDOM()->setAttribute('value', $attribute_value);
                
                if(isset($Session->Committed_New_Entries[$email_key]) && isset($Session->Committed_New_Entries[$email_key][$attribute_id]) && $Session->Committed_New_Entries[$email_key][$attribute_id] === $attribute_value) {
                    //if the attribute value is the already selected, mark as checked
                    $class .= ' Selected';
                    $radio->GetDOM()->setAttribute('checked', 'checked');
                }

                if($numkey == 0) {
                    $class .= ' Safe_Value';
                }

                $radio->GetDom()->setAttribute('class', $class);
                $listMemeber->setAttribute('class', $class);
                //$HTML_table_row= $HTML_table_row.$HTML_attribute_value_input.'<br>';
                $listMemeber->appendChild($radio->GetDOM());
                $domList->appendChild($listMemeber);
                break;
            
            case "case_2" :

                $listMemeber->appendChild($dom->createElement("div", $Session->attribute_list[$attribute_id]['allowed_value_ids'][$attribute_value]));

                $radio = new Input($dom, $Session->attribute_list[$attribute_id]['allowed_value_ids'][$attribute_value], 'radio', 'New_Entry_List['.$email_key.']['.$attribute_id.']', $attribute_value);
                $radio->GetDOM()->setAttribute('value', $Session->attribute_list[$attribute_id]['allowed_value_ids'][$attribute_value]);

                if(isset($Session->Committed_New_Entries[$email_key]) && isset($Session->Committed_New_Entries[$email_key][$attribute_id]) && $Session->Committed_New_Entries[$email_key][$attribute_id] === $attribute_value) {
                    $class .= ' Selected';
                    $radio->GetDOM()->setAttribute('checked', 'checked');
                }

                if($numkey == 0) {
                    $class .= ' Safe_Value';
                }

                $radio->GetDom()->setAttribute('class', $class);
                $listMemeber->setAttribute('class', $class);
                //$HTML_table_row= $HTML_table_row.$HTML_attribute_value_input.'<br>';
                $listMemeber->appendChild($radio->GetDOM());
                $domList->appendChild($listMemeber);

                break;

            case "case_3": 
                $listMemeber->appendChild($dom->createElement("div", $Session->attribute_list[$attribute_id]['allowed_value_ids'][$attribute_value]));
                $checkbox = $dom->createElement('input');

                $checkbox->setAttribute('type', 'checkbox');
                $checkbox->setAttribute('value', $attribute_value);
                $checkbox->setAttribute('name', 'New_Entry_List['.$email_key.']['.$attribute_id.']['.$attribute_value.']');

                $class .= ' Checkbox_Value';

                if(isset($Session->Committed_New_Entries[$email_key]) && isset($Session->Committed_New_Entries[$email_key][$attribute_id]) && in_array($attribute_value, $Session->Committed_New_Entries[$email_key][$attribute_id])) {
                    $checkbox->setAttribute('checked', 'checked');
                    $class .= ' Selected';
                }

                $checkbox->GetDom()->setAttribute('class', $class);
                $listMemeber->setAttribute('class', $class);
                //$HTML_table_row = $HTML_table_row.$HTML_attribute_value_input.'<br>';
                $listMemeber->appendChild($checkbox);
                $domList->appendChild($listMemeber);
               break;
            default:
                
        }
        $domElement->appendChild($domList);
        
    }

    return $domElement;
}


// //---------------------5 

$displayAmounts= array(
    10=>10,
    100=>100,
    1000=>1000,
    10000=>10000,
    'all'=>'all',
    );
    
function Get_New_Entry_Table_Navigation_Buttons (&$dom) {

        $Session = $GLOBALS['plugins']['AttributeChangerPlugin']->Current_Session;

        $buttonDiv= $dom->createElement('div');
        $submitAll = new Input($dom, '', 'submit', 'New_Entries_Table_Submit_All', 'New_Entries_Table_Submit_All');
        $buttonDiv->appendChild($submitAll->GetDOM());

        if($Session->Current_New_Entry_Block_Number > 0) {
            $nextPage = new Input($dom, '', 'submit', 'New_Entries_Table_Previous_Page', 'New_Entries_Table_Previous_Page');
            $buttonDiv->appendChild($nextPage->GetDOM());
        }
        if($Session->Current_New_Entry_Block_Number < $Session->New_Entries_Number_Of_Blocks - 1) {
            $previousPage = new Input($dom, '', 'submit', 'New_Entries_Table_Next_Page', 'New_Entries_Table_Next_Page');
            $buttonDiv->appendChild($previousPage->GetDOM());
        }

        $displayNumber = $dom->createElement('select');
        $displayNumber->setAttribute('name', 'New_Entries_New_Display_Amount');

        
$displayAmounts= array(
    10=>10,
    100=>100,
    1000=>1000,
    10000=>10000,
    'all'=>'all',
    );
        foreach ($displayAmounts as $amount) {

            $option = $dom->createElement('option');
            $option->setAttribute('value', $amount);
            $displayNumber->appendChild($option);
            $option->appendChild($dom->createElement('div', $amount));
        }
        $buttonDiv->appendChild($displayNumber);
  
        $changeDisplay = new Input($dom, '', 'submit', "New_Entry_Change_Display_Amount", "New_Entry_Change_Display_Amount");
      
        $buttonDiv->appendChild($changeDisplay->GetDOM());

        $HTML_current_table_info = $dom->createElement("div", "Current Block: ".($Session->Current_New_Entry_Block_Number+1)." of ".($Session->New_Entries_Number_Of_Blocks).". Displaying ".$Session->Current_New_Entries_Display_Amount." entries per page.");

        $buttonDiv->appendChild($HTML_current_table_info);

        return $buttonDiv;
    }








// //------------------6

function BuildModifyEntryDom() {

    $Session = $GLOBALS['plugins']['AttributeChangerPlugin']->Current_Session;

    $dom = new DOMDocument('1.0', 'utf-8');

    $htmlHeader = $dom->createElement('head');

    $dom->appendChild($htmlHeader);

    $form = $dom->createElement('form');
    $form->setAttribute("name", 'Modify_Entry_Submit_Form_Block__'.$Session->Current_Modify_Entry_Block_Number);
    $form->setAttribute("method", 'post');
    $form->setAttribute("action", "");

    $htmlHeader->appendChild($form);

    $hiddenFormData = new Input($dom, '', 'hidden', "Modify_Entry_Form_Submitted", 'submitted');
    $form->appendChild($hiddenFormData->GetDOM());

    $table = new Table_1($dom);

    $form->appendChild($table->GetDOM());

    GetModifyEntryTableHeader_And_Append_To_Table($dom,  $table);

    $tableDOM = $table->GetDOM();
    $Current_Modify_Entry_Block = array_slice($Session->Modify_Entry_List, $Session->Current_Modify_Entry_Block_Number*$Session->Current_Modify_Entries_Display_Amount, $Session->Current_Modify_Entries_Display_Amount);

    foreach ($Current_Modify_Entry_Block as $email_key => $Modify_user_attributes_and_values) {   


        $tableRow = GetModifyTableRow($dom, $email_key);

        $tableDOM->appendChild($tableRow);
    }

    $buttons = Get_Modify_Table_Navigation_Buttons($dom);
    $form->appendChild($buttons);

    return $dom;
}

//--------------------------7

function GetModifyEntryTableHeader_And_Append_To_Table(&$dom,  &$table) {
    print('<link rel="stylesheet" type="text/css" href="'.PLUGIN_ROOTDIR.'/AttributeChangerPlugin/cssStyles.css"><div class="Current_Value" class="Current_Value">ARARARARARA</div>"');

    $Session = $GLOBALS['plugins']['AttributeChangerPlugin']->Current_Session;

    $emailColumnHead = $dom->createElement('div', 'Email');

    $emailColumnCheckAll = new Button_1($dom, 'Include ALl Emails', 'Modify_Entry_Include_All_Emails', 'checkAll_ModifyEntry_Emails()');
    $emailColumnCheckAll->GetDOM()->setAttribute('type', 'button');
    $emailColumnHead->appendChild($emailColumnCheckAll->GetDOM());

    $emailColumnUncheckAll = new Button_1($dom, 'Uncheck all emails', 'Modify_Entry_Remove_All_Emails', "removeAll_ModifyEntry_Emails()");
    $emailColumnUncheckAll->GetDOM()->setAttribute('type', 'button');
    $emailColumnHead->appendChild($emailColumnUncheckAll->GetDOM());


    $table->AddColumn($dom, $emailColumnHead);

    foreach ($Session->attribute_list as $attribute_id => $attribute_info) {
           
        $attributeColumnHead = $dom->createElement('div',"Attribute: ".$attribute_info['name']);

        $includeCheckbox = new Input($dom, 'Include this attribute', 'checkbox','Modify_Entry_Attribute_Column_Select['.$attribute_id.']', 'checked');
        
        if(in_array($attribute_id, $Session->Modify_Entries_Columns_To_Select)) {
            $includeCheckbox->GetDOM()->setAttribute('checked', 'checked');


        }

        if($Session->New_Entries_Columns_To_Select[$attribute_id]== true) {
            $includeCheckbox->GetDOM()->setAttribute('checked', 'checked');
        }




        $attributeColumnHead->appendChild($includeCheckbox->GetDOM());

        if($attribute_info['type'] === 'checkboxgroup') {
            
            $nameIn = 'New_Entry_Include_All_Checkboxgroup_'.$attribute_id;
            $onClickIn = 'checkAll_ModifyEntry_CheckboxGroup("'.$attribute_id.'")';
            $textIn = "Include All Checkboxgroup";

            $nameRem = 'New_Entry_Remove_All_Checkboxgroup_'.$attribute_id;
            $onClickRem = 'removeAll_ModifyEntry_CheckboxGroup("'.$attribute_id.'")';
            $textRem = "Remove All Checkboxgroup";

            $includeAllCheckboxgroup = new Button_1($dom, $textIn, $nameIn, $onClickIn);
            $includeAllCheckboxgroup->GetDOM()->setAttribute('type', 'button');

            $removeAllCheckboxgroup = new Button_1($dom, $textRem, $nameRem, $onClickRem);
            $removeAllCheckboxgroup->GetDOM()->setAttribute('type', 'button');

            $attributeColumnHead->appendChild($includeAllCheckboxgroup->GetDOM());
            $attributeColumnHead->appendChild($removeAllCheckboxgroup->GetDOM());

            $table->AddColumn($dom, $attributeColumnHead);


        }
        else{
  
            $nameSafeIn = 'Modify_Entry_Include_All_Safe_Values_'.$attribute_id;
            $onClickSafeIn = 'checkAll_ModifyEntry_SafeValues("'.$attribute_id.'")';
            $textSafeIn = "Include all safe values";

            $nameSafeCheckedIn = 'Modify_Entry_Include_All_Safe_Or_Checked'.$attribute_id;
            $onClickSafeCheckedIn = 'checkAll_ModifyEntry_SafeValues_OrChecked("'.$attribute_id.'")';
            $textSafeCheckedIn = "Include all safe or checked values";



            $nameSafeRem = 'Modify_Entry_Remove_All_Safe_Or_Checked_'.$attribute_id;
            $onClickSafeRem = 'removeAll_ModifyEntry_SafeValues("'.$attribute_id.'")';
            $textSafeRem = "Remove All Safe Values";

            $nameSafeCheckedRem = 'Modify_Entry_Remove_All_Safe_Or_Checked'.$attribute_id;
            $onClickSafeCheckedRem = 'removeAll_ModifyEntry_SafeValues_OrChecked("'.$attribute_id.'")';
            $textSafeCheckedRem = "Remove All safe or checked";


            $includeAllSafe = new Button_1($dom, $textSafeIn, $nameSafeIn , $onClickSafeIn);
            $includeAllSafe->GetDOM()->setAttribute('type', 'button');

            $includeAllSafeOrChecked = new Button_1($dom, $textSafeCheckedIn, $nameSafeCheckedIn , $onClickSafeCheckedIn);
            $includeAllSafeOrChecked->GetDOM()->setAttribute('type', 'button');

            $removeAllSafe = new Button_1($dom, $textSafeRem, $nameSafeRem , $onClickSafeRem);
            $removeAllSafe->GetDOM()->setAttribute('type', 'button');

            $removeAllSafeOrChecked = new Button_1($dom, $textSafeCheckedRem, $nameSafeCheckedRem , $onClickSafeCheckedRem);
            $removeAllSafeOrChecked->GetDOM()->setAttribute('type', 'button');

            $attributeColumnHead->appendChild($includeAllSafe->GetDOM());
            $attributeColumnHead->appendChild($includeAllSafeOrChecked->GetDOM());

            $attributeColumnHead->appendChild($removeAllSafe->GetDOM());
            $attributeColumnHead->appendChild($removeAllSafeOrChecked->GetDOM());
            $table->AddColumn($dom, $attributeColumnHead);

        }
    }

}

// //-----------------------8

function GetModifyTableRow(&$dom, $email_key) {
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

    $Session = $GLOBALS['plugins']['AttributeChangerPlugin']->Current_Session;

    $row = $dom->createElement('tr');
                        
    $row->appendChild(GetEmailBlock($dom, $email_key));

    foreach ($Session->attribute_list as $attribute_id => $attribute_info) {
        $cell = $dom->createElement('td');


            //print_r($Session->Current_User_Values);
            //print("<br><br>".$email_key.'<br>');
        if(isset($Session->Modify_Entry_List[$email_key][$attribute_id])) {

            $cell->appendChild( Create_Attribute_Table_Elements($dom, $attribute_id, $email_key));
        }


        $row->appendChild($cell);

    }
    return $row;
}


// function Get_Attribute_Block($email_key, $attribute_id){


//         $case_array = array(
//         'textarea' => 'case_1',
//         'textline' => 'case_1',
//         'hidden' => 'case_1',
//         'date' => 'case_1',

//         'checkbox' => 'case_2',

//         'radio' => 'case_2',
//         'select' => 'case_2',

//         'checkboxgroup' => 'case_3',
//         );


//     $Session = $GLOBALS['plugins']['AttributeChangerPlugin']->Current_Session;
//     $HTML_block = $dom->createElement('div');

//     $domList = $dom->createElement('ui'); 

//     $attribute_type = $Session->attribute_list[$attribute_id]['type'];

//     $has_safe = false;



//     $Current_User_Values = $Session->Current_User_Values[$email_key][$attribute_id];
//     $Modify_Entry_List = $Session->Modify_Entry_List[$email_key][$attribute_id];
    
//     $list = $dom->createElement('ui');
//     if(isset($Session->Modify_Entry_List[$email_key])) {
//         $Committed_Attribute_Values = $Session->Committed_Modify_Entries[$email_key][$attribute_id];


//         foreach (array_merge($Current_User_Values, $Modify_Entry_List) as $key => $value) {
            
//             $class = 'Modify_Entry';
//             $list_element = $dom->createElement('li');

//             if(in_array($value, $Current_User_Values)) {
//                 $class .= ' Current_Value';
//             }

//             else if($key == 0) {
//                 $class .= ' Safe_Value'
//             }

//             if($case_array[$attribute_type] == 'case_1'){

//                 $radio = $dom->createElement('input', $value);
//                 $radio->setAttribute('type', 'radio');
                
//                 if (in_array($value, $Committed_Attribute_Values)) {
//                     $class .= ' Checked';
//                     $radio->setAttribute('checked', 'Checked');
//                     $radio->setAttribute('class', $class);

//                     $list_element->appendChild($radio);
//                 }
//             }
//             else if($case_array[$attribute_type] == 'case_2') {
//                 $radio = $dom->createElement('input', $Session->attribute_list[$attribute_id]['allowed_value_ids'][$value]);
//                 $radio->setAttribute('type', 'radio');
                
//                 if (in_array($value, $Committed_Attribute_Values)) {
//                     $class .= ' Checked';
//                     $radio->setAttribute('checked', 'Checked');
//                     $radio->setAttribute('class', $class);
//                     $list_element->appendChild($radio);
//                 }
//             }

//             else if($case_array[$attribute_type] == 'case_3') {
//                 $checkbox = $dom->createElement('input', $Session->attribute_list[$attribute_id]['allowed_value_ids'][$value]);
//                 $checkbox->setAttribute('type', 'checkbox');
//                 $class .= ' Checkbox_Value';
//                 if (in_array($value, $Committed_Attribute_Values)) {
//                     $class .= ' Checked';
//                     $checkbox->setAttribute('checked', 'Checked');
//                     $checkbox->setAttribute('class', $class);
//                     $list_element->appendChild($checkbox);
//                 }
//             }

//             $list_element->setAttribute('class', $class);
//         }

//     }
//     else if(isset($Session->New_Entry_List[$email_key])){

//         $attribute_values = $Session->New_Entry_List[$email_key][$attribute_id];
//         $Committed_Attribute_Values = $Session->Committed_New_Entries[$email_key][$attribute_id];

//         foreach ($attribute_values as $key => $value) {

//             $class = 'New_Entry';
//             $list_element = $dom->createElement('li');

//             if($key == 0) {
//                 $class .= ' Safe_Value'
//             }


// function Create_New_Entry_Elements($attribute_id, $email_key) {
//     $case_array = array(
//         'textarea' => 'case_1',
//         'textline' => 'case_1',
//         'hidden' => 'case_1',
//         'date' => 'case_1',

//         'checkbox' => 'case_2',

//         'radio' => 'case_2',
//         'select' => 'case_2',

//         'checkboxgroup' => 'case_3',
//     );

//     $Session = $GLOBALS['plugins']['AttributeChangerPlugin']->Current_Session;

//     $attribute_type = $case_array[$Session->attribute_list[$attribute_id]['type']];

//     $attribute_allowed_values = $Session->attribute_list[$attribute_id]['allowed_value_ids'];

//     $New_Entry_List = $Session->New_Entry_List[$email_key][$attribute_id];

//     $Committed_Attribute_Values = $Session->Committed_New_Entries[$email_key][$attribute_id];

//     $New_entry = 'New_Entry';

//     $list = $dom->createElement('ui');

//     foreach ($New_Entry_List as $key => $value) {

//         if($key == 0) {
//             $class = $New_entry.' Safe_Value'
//         }

//         $list_element = $dom->createElement('li');

//         if($attribute_type == 'case_1') {

//             $selector = $dom->createElement('input', $value);
//             $selector->setAttribute('type', 'radio');
//             $selector->setAttribute('name', 'New_Entry_List['$email_key']['$attribute_id']');
//             $selector->setAttribute('value', $value);
//         }
//         else if($attribute_type == 'case_2') {

//             $selector = $dom->createElement('input', $attribute_allowed_values[$value]);
//             $selector->setAttribute('type', 'radio');
//             $selector->setAttribute('name', 'New_Entry_List['$email_key']['$attribute_id']');
//             $selector->setAttribute('value', $value);
//         }
//         if($attribute_type == 'case_3') {
            
//             $class = $New_entry.' Checkbox_Value';
//             $selector = $dom->createElement('input', $attribute_allowed_values[$value]);
//             $selector->setAttribute('type', 'checkbox');

//             $selector->setAttribute('name', 'New_Entry_List['$email_key']['$attribute_id']['$value']');
//             $selector->setAttribute('value', $value);
//         }

//         if (in_array($value, $Committed_Attribute_Values)) {
//             $class .= ' Checked';
//             $radio->setAttribute('checked', 'Checked');
//             $radio->setAttribute('class', $class);
//         }
//         $selector->setAttribute('class', $class);
//         $list_element->appendChild($selector);
//         $list_element->setAttribute('class', $class);

//         $list->appendChild($list_element);
//     }
//     return $list;
// }



function Create_Attribute_Table_Elements(&$dom, $attribute_id, $email_key) {
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

    $Session = $GLOBALS['plugins']['AttributeChangerPlugin']->Current_Session;

    $attribute_type = $case_array[$Session->attribute_list[$attribute_id]['type']];


    $attribute_allowed_values = $Session->attribute_list[$attribute_id]['allowed_value_ids'];

    $Current_User_Values = null;

    if(isset($Session->New_Entry_List[$email_key])){
        $Entry_Type = 'New_Entry_List';
        $Entry_List = $Session->New_Entry_List[$email_key][$attribute_id];
        $class_head = 'New_Entry';

        $Committed_Attribute_Values = $Session->Committed_New_Entries[$email_key][$attribute_id];
    }

    else if(isset($Session->Modify_Entry_List[$email_key])){


        $Entry_Type = 'Modify_Entry_List';
        $Entry_List = array();

        foreach ($Session->Current_User_Values[$email_key][$attribute_id] as $value) {
            $Entry_List[] = $value;
        }

        foreach ($Session->Modify_Entry_List[$email_key][$attribute_id] as $value) {
            $Entry_List[] = $value;
        }

        $class_head = 'Modify_Entry';
        $Current_User_Values = $Session->Current_User_Values[$email_key][$attribute_id];

        $Committed_Attribute_Values = $Session->Committed_Modify_Entries[$email_key][$attribute_id];
    }


    $list = $dom->createElement('ul');
    $list->setAttribute('style', "list-style-type:none");

    foreach ($Entry_List as $key => $value) {

        $class = '';
        $class .= $class_head;

        if($key == 0) {
            $class .= ' Safe_Value';
        }



        if($Current_User_Values) {
            if(in_array($value, $Current_User_Values)) {
                $class .= ' Current_Value';
            }
        }

        if($attribute_type == 'case_1') {

            $selector = $dom->createElement('input');
            $selector->setAttribute('type', 'radio');
            $selector->setAttribute('name', $Entry_Type.'['.$email_key.']['.$attribute_id.']');
            $selector->setAttribute('value', $value);
            $list_element = $dom->createElement('li', $value);
        }

        else if($attribute_type == 'case_2') {

            $selector = $dom->createElement('input');
            $selector->setAttribute('type', 'radio');
            $selector->setAttribute('name', $Entry_Type.'['.$email_key.']['.$attribute_id.']');
            $selector->setAttribute('value', $value);
            $list_element = $dom->createElement('li', $attribute_allowed_values[$value]);
        }

        else if($attribute_type == 'case_3') {
            
            $class .= ' Checkbox_Value';
            $selector = $dom->createElement('input');
            $selector->setAttribute('type', 'checkbox');

            $selector->setAttribute('name', $Entry_Type.'['.$email_key.']['.$attribute_id.']['.$value.']');
            $selector->setAttribute('value', $value);
            $list_element = $dom->createElement('li', $attribute_allowed_values[$value]);
        }

        else{
            //blank due to no set case_3 error
            return $dom->createElement('div');
        }

        if (in_array($value, $Committed_Attribute_Values)) {
            $class .= ' Checked';
            $selector->setAttribute('checked', 'Checked');
            $selector->setAttribute('class', $class);
        }

        $class .= ' attribute_'.$attribute_id;

        $selector->setAttribute('class', $class);

        //$selector->setAttribute('onclick', 'selector_clicked(this)');


        $list_element->appendChild($selector);
        $list_element->setAttribute('class', $class);
        $list_element->setAttribute('onclick', 'list_element_clicked(this)');

        $list->appendChild($list_element);
    }

    return $list;
}
// function Create_Case_One_Element($Committed_Attribute_Values, $attribute_id) {
//     $radio = $dom->createElement('input', $value);
//     $radio->setAttribute('type', 'radio');
    
//     if (in_array($value, $Committed_Attribute_Values)) {
//         $class .= ' Checked';
//         $radio->setAttribute('checked', 'Checked');
//         $radio->setAttribute('class', $class);
//     }
//     $list_element->appendChild($radio);
// }

//             if($case_array[$attribute_type] == 'case_1'){

//                 $radio = $dom->createElement('input', $value);
//                 $radio->setAttribute('type', 'radio');
                
//                 if (in_array($value, $Committed_Attribute_Values)) {
//                     $class .= ' Checked';
//                     $radio->setAttribute('checked', 'Checked');
//                     $radio->setAttribute('class', $class);
//                 }
//                 $list_element->appendChild($radio);
//             }
//             else if($case_array[$attribute_type] == 'case_2') {
//                 $radio = $dom->createElement('input', $Session->attribute_list[$attribute_id]['allowed_value_ids'][$value]);
//                 $radio->setAttribute('type', 'radio');
                
//                 if (in_array($value, $Committed_Attribute_Values)) {
//                     $class .= ' Checked';
//                     $radio->setAttribute('checked', 'Checked');
//                     $radio->setAttribute('class', $class);
//                 }
//                 $list_element->appendChild($radio);
//             }

//             else if($case_array[$attribute_type] == 'case_3') {
//                 $checkbox = $dom->createElement('input', $Session->attribute_list[$attribute_id]['allowed_value_ids'][$value]);
//                 $checkbox->setAttribute('type', 'checkbox');
//                 $class .= ' Checkbox_Value';
//                 if (in_array($value, $Committed_Attribute_Values)) {
//                     $class .= ' Checked';
//                     $checkbox->setAttribute('checked', 'Checked');
//                     $checkbox->setAttribute('class', $class);
//                 }
//                 $list_element->appendChild($checkbox);
//             }

//             if(in_array($value, $Committed_Attribute_Values)) {
//                 $class .= ' Checked';
//             }
//         }
//     }
        


    /*

                }

                else if($case_array[$attribute_type] == 'case_2'){


                    $radio = $dom->createElement('input', $Session->attribute_list[$attribute_id]['allowed_value_ids'][$value]);
                    $radio->setAttribute('type', 'radio');

                    if (in_array($value, $Committed_Attribute_Values)) {
                        $class .= ' Checked';
                        $radio->setAttribute('checked', 'Checked');
                    }

                    $radio->setAttribute('class', $class);
                    $list_element = $dom->createElement('li');

                    $list_element->appendChild($radio);
                    $list_element->setAttribute('class', $class);
                    
                }

                if(in_array($value, $Current_User_Values)) {
                    $class .= ' Current_Value';
                }
                
                else if($key == 0) {
                    $class .= ' Safe_Value'
                }

 
                $radio->setAttribute('class', $class);
                $list_element = $dom->createElement('li');

                $list_element->appendChild($radio);
                $list_element->setAttribute('class', $class);
                $list->appendChild($list_element);



                else if($case_array[$attribute_type] == 'case_3'){
                
                }

                if($key == 0) {
                    $class .= ' Safe_Value';
                }

                if( in_array($value, $Current_User_Values)) {
                    $class .= ' Current_Value';
                }

                $list_element = $dom->createElement('li');
                $
            }
        

        if($case_array[$attribute_type] == 'case_1') {
            $has_safe = false;
            if($Current_User_Values){

            }
        }

        if($case_array[$attribute_type] == 'case_1') {
            $has_safe = false;
            if($Current_User_Values){

            }
        }*/



//     if (in_array($attribute_value, $Committed_Attribute_Values)) {
//         $class .= ' Checked';
//         $dom_object->checked = 'checked';
//     }

//     if(!$has_safe) {
//         $class .= ' Safe_Value';
//         $has_safe = true;
//     }

//     if( in_array($value, $Current_User_Values)) {
//         $class .= ' Current_Value';
//     }


//     if(isset($Session->Modify_Entry_List[$email_key])) {
//         $class = 'Modify_Entry';
//         if(isset($Session->Current_User_Values[$email_key][$attribute_id])) {

//             if($case_array[$attribute_type] == 'case_1'){
//                 $list_element = $dom->createElement('li', $Session->Current_User_Values[$email_key][$attribute_id]);
//                 $list_element->setAttribute('class', $class.' Selected Safe_Value');
//                 $has_safe = true;

//                 $domList->appendChild($list_element);
//             }

//             else if ($case_array[$attribute_type] == 'case_2'){

//                 $attribute_value_id = $Session->Current_User_Values[$email_key][$attribute_id];
//                 $list_element = $dom->createElement('li', $Session->attribute_list[$attribute_id]['allowed_value_ids'][$attribute_value_id]);
//                 if (isset($Session->Committed_Modify_Entries[$email_key][$attribute_id])
//                 $list_element->setAttribute('class', $class.' Selected Safe_Value');
//                 $has_safe = true;

//                 $domList->appendChild($list_element);
//             }

//             else if($case_array[$attribute_type] == 'case_3'){
//                 foreach ($Session->Current_User_Values[$email_key][$attribute_id] as $key => $value) {

//                     $list_element = $dom->createElement('li', $Session->attribute_list[$attribute_id]['allowed_value_ids'][$value]);
//                     $list_element->setAttribute('class', $class.' Selected Checkbox_Value');
                    
//                     $domList->appendChild($list_element);
//                 }
//             }
//         }

//         foreach ($Session->Modify_Entry_List[$email_key][$attribute_id] as $value) {
//             $class = 'Modify_Entry';
//             if($case_array[$attribute_type] == 'case_1'){
//                 $list_element = $dom->createElement('li', $value);
//                 if(isset($Session->Committed_Modify_Entries[$email_key][$attribute_id]) && $Session->Committed_Modify_Entries[$email_key][$attribute_id] == $value) {

//                 }
//                 if(!$has_safe) {
//                     $class .= ' Safe_Value'
//                     $has_safe = true;
//                 }

//                 $domList->appendChild($list_element);
//             }

//             else if ($case_array[$attribute_type] == 'case_2'){
//                 $list_element = $dom->createElement('li', $Session->attribute_list[$attribute_id]['allowed_value_ids'][$value]);
//                 $class .= ' Selected';

//                 if(!$has_safe) {
//                     $class .= ' Safe_Value'
//                     $has_safe = true;
//                 }
//                 $domList->appendChild($list_element);
//             }

//             else if($case_array[$attribute_type] == 'case_3'){
//                 foreach ($Session->Current_User_Values[$email_key][$attribute_id] as $key => $value) {

//                     $list_element = $dom->createElement('li', $Session->attribute_list[$attribute_id]['allowed_value_ids'][$value]);
//                     $list_element->setAttribute('class', $class.' Selected Checkbox_Value');
                    
//                     $domList->appendChild($list_element);
//                 }
//             }
//         }
//     }

//     else if(isset($Session->New_Entry_List[$email_key]){
//         $class = 'New_Entry';

//         foreach ($Session->New_Entry_List[$email_key][$attribute_id] as $value) {
//             if($case_array[$attribute_type] == 'case_1'){
//                 $list_element = $dom->createElement('li', $Session->Current_User_Values[$email_key][$attribute_id]);
//                 $class .= ' Selected';
//                 if(!$has_safe) {
//                     $class .= ' Safe_Value'
//                     $has_safe = true;
//                 }
//                 $domList->appendChild($list_element);
//             }

//             else if ($case_array[$attribute_type] == 'case_2'){
//                 $list_element = $dom->createElement('li', $Session->attribute_list[$attribute_id]['allowed_value_ids'][$Session->Current_User_Values[$email_key][$attribute_id]]);
//                 $class .= ' Selected';

//                 if(!$has_safe) {
//                     $class .= ' Safe_Value'
//                     $has_safe = true;
//                 }
//                 $domList->appendChild($list_element);
//             }

//             else if($case_array[$attribute_type] == 'case_3'){
//                 foreach ($Session->Current_User_Values[$email_key][$attribute_id] as $key => $value) {

//                     $list_element = $dom->createElement('li', $Session->attribute_list[$attribute_id]['allowed_value_ids'][$value]);
//                     $list_element->setAttribute('class', $class.' Selected Checkbox_Value');
                    
//                     $domList->appendChild($list_element);
//                 }
//             }
//         }
//     }

// }

// //---------------------------------------8.5

function Get_Current_Attribute_Block(&$dom, $email_key, $attribute_id){
    


    $Session = $GLOBALS['plugins']['AttributeChangerPlugin']->Current_Session;
    $HTML_block = $dom->createElement('div');


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

        $domList = $dom->createElement('ui');


        switch ($case_array[$Session->attribute_list[$attribute_id]['type']]) {
            case 'case_3':
                
                foreach ($Session->Current_User_Values[$email_key][$attribute_id] as $key => $current_single_value) {

                    $class .= 'Modify_Entry';
                    $class .= ' Current_Value';
                    $class .= ' attribute_'.$attribute_id;

                    $text = $Session->attribute_list[$attribute_id]['allowed_value_ids'][$current_single_value];
                    $name = 'Modify_Entry_List['.$email_key.']['.$attribute_id.']['.$current_single_value.']';
                    $value = $current_single_value;

                    $checkbox = new Input($dom, $Session->$text, 'checkbox', $name, $value);


                    if(isset($Session->Committed_Modify_Entries[$email_key][$attribute_id])) {
                        if(in_array($current_single_value, $Session->Committed_Modify_Entries[$email_key][$attribute_id])) {
                            $checkbox->GetDOM()->setAttribute('checked', 'checked');
                            $class .= ' Selected';
                        }
                    }

                    $checkbox->GetDOM()->setAttribute('class',$class);


                    $listOption = $dom->createElement('li');

                    $listOption->setAttribute('class', $class);

                    $listOption->appendChild($dom->createElement('div', $text));
                    $listOption->appendChild($checkbox->GetDOM());
                    $domList->appendChild($listOption);
                }
                
                break;
            
            case 'case_2' :
                $class .= 'Modify_Entry';
                $class .= ' Current_Value';
                $class .= ' attribute_'.$attribute_id;


                $text = $Session->attribute_list[$attribute_id]['allowed_value_ids'][$Session->Current_User_Values[$email_key][$attribute_id]];
                $name = 'Modify_Entry_List['.$email_key.']['.$attribute_id.']';
                $value = $Session->Current_User_Values[$email_key][$attribute_id];

                $radio = new Input($dom, $Session->$text, 'radio', $value);
                $radio->GetDOM()->setAttribute('class', $class);

                if(isset($Session->Committed_Modify_Entries[$email_key]) && isset($Session->Committed_Modify_Entries[$email_key][$attribute_id])) {
                    if($Session->Committed_Modify_Entries[$email_key][$attribute_id] === $Session->Current_User_Values[$email_key][$attribute_id]) {
                            $radio->GetDOM()->setAttribute('checked', 'checked');  
                            $class .= ' Selected';                 
                    }
                }

                $radio->GetDOM()->setAttribute('class',$class);

                $listOption = $dom->createElement('li');

                $listOption->setAttribute('class', $class);

                $listOption->appendChild($dom->createElement('div', $text));
                $listOption->appendChild($radio->GetDOM());
                $domList->appendChild($listOption);
                break;

            case 'case_1':
                $class .= 'Modify_Entry';
                $class .= ' Current_Value';
                $class .= ' attribute_'.$attribute_id;


                $text = $Session->attribute_list[$attribute_id]['allowed_value_ids'][$Session->Current_User_Values[$email_key][$attribute_id]];
                $name = 'Modify_Entry_List['.$email_key.']['.$attribute_id.']';
                $value = $Session->Current_User_Values[$email_key][$attribute_id];

                $radio = new Input($dom, $Session->$text, 'radio', $value);
                $radio->GetDOM()->setAttribute('class', $class);

                if(isset($Session->Committed_Modify_Entries[$email_key]) && isset($Session->Committed_Modify_Entries[$email_key][$attribute_id])) {
                    if($Session->Committed_Modify_Entries[$email_key][$attribute_id] === $Session->Current_User_Values[$email_key][$attribute_id]) {
                            $radio->GetDOM()->setAttribute('checked', 'checked');  
                            $class .= ' Selected';                 
                    }
                }
                $checkbox->GetDOM()->setAttribute('class',$class);

                $listOption = $dom->createElement('li');

                $listOption->setAttribute('class', $class);

                $listOption->appendChild($dom->createElement('div', $text));
                $listOption->appendChild($radio->GetDOM());
                $domList->appendChild($listOption);
                break;

            default:
                break;
        }
        $HTML_block->appendChild($domList);
        return $HTML_block;
    }
}


// //--------------------------9

function Get_Modify_Attribute_Value_Display_Checkboxgroup_New_Vals(&$dom, $email_key, $attribute_id) {




    $Session = $GLOBALS['plugins']['AttributeChangerPlugin']->Current_Session;
    $HTML_value_block = $dom->createElement('div');

    if(!isset($Session->Modify_Entry_List[$email_key][$attribute_id]) || count($Session->Modify_Entry_List[$email_key][$attribute_id]) == 0) {
        return $HTML_value_block;
    }


    $domList = $dom->createElement('ui');


    foreach ($Session->Modify_Entry_List[$email_key][$attribute_id] as $numkey => $checkbox_value_id) {

        $name = "Modify_Entry_List[".$email_key."][".$attribute_id."][".$checkbox_value_id."]";

        $class = 'Modify_Entry';
        $class .= ' Checkbox_Value';
        $class .= ' attribute_'.$attribute_id;

        $value = $checkbox_value_id;
        $text = $Session->attribute_list[$attribute_id]['allowed_value_ids'][$checkbox_value_id];

        $listOption = $dom->createElement('li');
        $listOption->appendChild($dom->createElement('div', $text));


        $checkbox = new Input($dom, $text, 'checkbox', $name, $value);

        if(isset($Session->Committed_Modify_Entries[$email_key]) && isset($Session->Committed_Modify_Entries[$email_key][$attribute_id])) {

            if(in_array($checkbox_value_id, $Session->Committed_Modify_Entries[$email_key][$attribute_id]) ) {
                $checkbox->GetDOM()->setAttribute('checked', 'checked');

               $class .= ' Selected';
            }
        }

        $checkbox->GetDOM()->setAttribute('class', $class);
        $listOption->setAttribute('class', $class);

        $listOption->appendChild($checkbox->GetDOM());
        $domList->appendChild($listOption);
    }
    $HTML_value_block->appendChild($domList);
    return $HTML_value_block;
}



// //-----------------------------------10

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

    $domList = $dom->createElement('ui');

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
        
        $listMember = $dom->createElement('li');
        $listMember->appendChild($dom->createElement('div', $this_value));

        $name = 'Modify_Entry_List['.$email_key.']['.$attribute_id.']';
        $value = $checkbox_value_id;

        $radio = new Input($dom, $this_value, 'radio', $name, $value);

        $class .= 'Modify_Entry';
        $class .= ' Current_Value';
        $class .= ' attribute_'.$attribute_id;

        if(isset($Session->Committed_Modify_Entries[$email_key]) && isset($Session->Committed_Modify_Entries[$email_key][$attribute_id])) {

            if($checkbox_value_id === $Session->Committed_Modify_Entries[$email_key][$attribute_id]) {
                $radio->GetDOM()->setAttribute('checked', 'checked');
                $class .= ' Selected';
            }
        }

        if($numkey == 0 && (!isset($Session->Current_User_Values[$email_key][$attribute_id]) || count($Session->Current_User_Values[$email_key][$attribute_id]) == 0) ) {
            $class .= 'Safe_Value';
        }

        $radio->Getdom()->setAttribute('class', $class);
        $listMember->setAttribute('class', $class);
        $listMember->appendChild($radio->GetDOM());

        $domList->appendChild($listMember);

    }
    $HTML_value_block->appendChild($domList);
    return $HTML_value_block;
}

// //----------------------------------------11

function Get_Modify_Table_Navigation_Buttons (&$dom) {

        $Session = $GLOBALS['plugins']['AttributeChangerPlugin']->Current_Session;

        $buttonDiv= $dom->createElement('div');
        $submitAll = new Input($dom, '', 'submit', 'Modify_Entries_Table_Submit_All', 'Modify_Entries_Table_Submit_All');
        $buttonDiv->appendChild($submitAll->GetDOM());

        if($Session->Current_New_Entry_Block_Number > 0) {
            $nextPage = new Input($dom, '', 'submit', 'Modify_Entries_Table_Previous_Page', 'Modify_Entries_Table_Previous_Page');
            $buttonDiv->appendChild($nextPage->GetDOM());
        }
        if($Session->Current_New_Entry_Block_Number < $Session->New_Entries_Number_Of_Blocks - 1) {
            $previousPage = new Input($dom, '', 'submit', 'Modify_Entries_Table_Next_Page', 'Modify_Entries_Table_Next_Page');
            $buttonDiv->appendChild($previousPage->GetDOM());
        }

        $displayNumber = $dom->createElement('select');
        $displayNumber->setAttribute('name', 'Modify_Entries_New_Display_Amount');

        
$displayAmounts= array(
    10=>10,
    100=>100,
    1000=>1000,
    10000=>10000,
    'all'=>'all',
    );
        foreach ($displayAmounts as $amount) {

            $option = $dom->createElement('option');
            $option->setAttribute('value', $amount);
            $displayNumber->appendChild($option);
            $option->appendChild($dom->createElement('div', $amount));
        }
        $buttonDiv->appendChild($displayNumber);
  
        $changeDisplay = new Input($dom, '', 'submit', "Modify_Entry_Change_Display_Amount", "Modify_Entry_Change_Display_Amount");
      
        $buttonDiv->appendChild($changeDisplay->GetDOM());

        $HTML_current_table_info = $dom->createElement("div", "Current Block: ".($Session->Current_Modify_Entry_Block_Number+1)." of ".$Session->Modify_Entries_Number_Of_Blocks.". Displaying ".$Session->Current_Modify_Entries_Display_Amount." entries per page.");

        $buttonDiv->appendChild($HTML_current_table_info);

        return $buttonDiv;
    }


















    // function Get_New_Entry_Table_Block() {

    //     $Session = $GLOBALS['plugins']['AttributeChangerPlugin']->Current_Session;
        
    //     $Current_New_Entry_Block = array_slice($Session->New_Entry_List, $Session->Current_New_Entry_Block_Number*$Session->Current_New_Entries_Display_Amount, $Session->Current_New_Entries_Display_Amount);

    //     $HTML_Display_Text = Get_New_Entry_Table_And_Top_Row();


    //     foreach ($Current_New_Entry_Block as $email_key => $new_user_attributes_and_values) {

    //         $HTML_table_row = '<tr><td>'.Get_New_Entry_Email_Block($email_key).'</td>';

    //         foreach ($Session->attribute_list as $attribute_id => $attribute_info) {

    //             $HTML_table_row = $HTML_table_row.'<td>'.Get_New_Entry_Attribute_Block($email_key, $attribute_id).'</td>';
    //         }
    //         $HTML_Display_Text = $HTML_Display_Text.$HTML_table_row.'</tr>';
             
    //     }
    //     //print($HTML_Display_Text."</table></form></html>");
    //    $HTML_Display_Text = $HTML_Display_Text.'</table>'.Get_New_Entry_Table_Navigation_Buttons();


    //     return $HTML_Display_Text;
    // }


    // function Get_New_Entry_Table_And_Top_Row() {

    //     $Session = $GLOBALS['plugins']['AttributeChangerPlugin']->Current_Session;

    //     $HTML_Display_Text = sprintf('<form name="New_Entry_Submit_Form_Block__%d" action="" method="post"><input type="hidden" name="New_Entry_Form_Submitted" value="submitted">', $Session->Current_New_Entry_Block_Number);
    //     $HTML_Display_Text = $HTML_Display_Text.sprintf('<table id="New_User_Attribute_Select_Table_Block__%d">', $Session->Current_New_Entry_Block_Number);
    //     $HTML_table_row = sprintf('<tr><td>EMAIL<br><input type="button" id="New_Entry_Include_All_Emails" name="New_Entry_Include_All_Emails" value="Include All Emails" onClick="checkAll_NewEntry_Emails()"></input>');
    //     $HTML_table_row = $HTML_table_row.sprintf('<input type="button" id="New_Entry_Remove_All_Emails" name="New_Entry_Include_Remove_Emails" value="Remove All Emails" onClick="removeAll_NewEntry_Emails()"></input></td>');

    //     foreach ($Session->attribute_list as $attribute_id => $attribute_info) {
    //         $HTML_table_row = $HTML_table_row.sprintf('<td>Attribute: %s<br><input type="checkbox" name="New_Entry_Attribute_Column_Select[%s]" value="checked" %s>Include This Attribute</input>', $attribute_info['name'], $attribute_id, $Session->New_Entries_Columns_To_Select?'checked':'');
    //         if($attribute_info['type'] === 'checkboxgroup') {
    //             $HTML_table_row = $HTML_table_row.sprintf('<br><input type="button" name="New_Entry_Include_All_Checkboxgroup_%s" id="New_Entry_Include_All_Checkboxgroup_%s" value="Include All Checkboxgroup Values" onClick="checkAll_NewEntry_CheckboxGroup(\'%s\')"></input>', $attribute_id, $attribute_id, $attribute_id);
    //             $HTML_table_row = $HTML_table_row.sprintf('<br><input type="button" name="New_Entry_Remove_All_Checkboxgroup_%s" id=="New_Entry_Remove_All_Checkboxgroup_%s" value="Remove All Checkboxgroup Values" onClick="removeAll_NewEntry_CheckboxGroup(\'%s\')"></input>', $attribute_id, $attribute_id, $attribute_id);
    //         }
    //         else{
    //             $HTML_table_row = $HTML_table_row.sprintf('<br><input type="button" name="New_Entry_Include_All_Safe_Values_%s" value="Include All Safe Values" onClick="checkAll_NewEntry_SafeValues(\'%s\')"></input></td>', $attribute_id, $attribute_id);
    //             $HTML_table_row = $HTML_table_row.sprintf('<br><input type="button" name="New_Entry_Include_All_Safe_Values_Or_Checked_%s" value="Include All Safe Values Or Checked" onClick="checkAll_NewEntry_SafeValues_OrChecked(\'%s\')"></input></td>', $attribute_id, $attribute_id);
    //             $HTML_table_row = $HTML_table_row.sprintf('<br><input type="button" name="New_Entry_Remove_All_Safe_Values_%s" value="Remove All Safe Values" onClick="removeAll_NewEntry_SafeValues(\'%s\')"></input></td>', $attribute_id, $attribute_id);
    //             $HTML_table_row = $HTML_table_row.sprintf('<br><input type="button" name="New_Entry_Remove_All_Safe_Values_Or_Checked_%s" value="Remove All Safe Values Or Checked" onClick="removeAll_NewEntry_SafeValues_OrChecked(\'%s\')"></input></td>', $attribute_id, $attribute_id);
    //         }
    //     }
    //     $HTML_Display_Text = $HTML_Display_Text.$HTML_table_row.'</tr>';

    //     return $HTML_Display_Text;
    // }



    // function Get_New_Entry_Email_Block($email_key) {

    //     $Session = $GLOBALS['plugins']['AttributeChangerPlugin']->Current_Session;

    //     if(isset($Session->Committed_New_Entries[$email_key])) {
    //         $HTML_table_row = sprintf('%s<br><input type="checkbox" class="New_Entry_Email" name="New_Entry_List[%s][%s]" value="include" checked>Include This Email</input><input type="hidden" name="Hidden_New_Entry_List[%s]" value="submitted">',$email_key, $email_key, 'include', $email_key);
    //     }
    //     else{
    //         $HTML_table_row = sprintf('%s<br><input type="checkbox" class="New_Entry_Email" name="New_Entry_List[%s][%s]" value="include">Include This Email</input><input type="hidden" name="Hidden_New_Entry_List[%s]" value="submitted">',$email_key, $email_key, 'include', $email_key);
    //     }
    //     return $HTML_table_row;        
    // }



    // function Get_New_Entry_Attribute_Block($email_key, $attribute_id) {

    //     $Session = $GLOBALS['plugins']['AttributeChangerPlugin']->Current_Session;

    //     $text= '';
    //     if(!isset($Session->New_Entry_List[$email_key][$attribute_id]) || count($Session->New_Entry_List[$email_key][$attribute_id]) == 0) {
    //         return $text;
    //     }

    //     $case_array = array(
    //         'textarea' => 'case_1',
    //         'textline' => 'case_1',
    //         'hidden' => 'case_1',
    //         'date' => 'case_1',

    //         'checkbox' => 'case_2',
    //         'radio' => 'case_2',
    //         'select' => 'case_2',

    //         'checkboxgroup' => 'case_3',
    //         );

    //     //print_r($Session->New_Entry_List);

    //     //att value is actually the value id where applicable

    //     foreach ($Session->New_Entry_List[$email_key][$attribute_id] as $numkey => $attribute_value) {

    //         if(!isset($case_array[$Session->attribute_list[$attribute_id]['type']])) {
    //             continue;
    //         }

    //         $this_string ='';


    //         switch($case_array[$Session->attribute_list[$attribute_id]['type']]) {

    //             case "case_1": 

    //                 if(isset($Session->Committed_New_Entries[$email_key]) && isset($Session->Committed_New_Entries[$email_key][$attribute_id]) && $Session->Committed_New_Entries[$email_key][$attribute_id] === $attribute_value) {
    //                     //if the attribute value is the already selected, mark as checked
    //                     if($numkey == 0) {
    //                         $this_string = sprintf('<input type="radio" class="New_Entry_Safe_Value_Attribute_%s" name="New_Entry_List[%s][%s]" value="%s" checked>%s</input>', $attribute_id, $email_key, $attribute_id, $attribute_value, $attribute_value);
    //                     }
    //                     else{
    //                         $this_string = sprintf('<input type="radio" class="New_Entry_Attribute_%s" name="New_Entry_List[%s][%s]" value="%s" checked>%s</input>', $email_key, $attribute_id, $attribute_value, $attribute_value);
    //                     }
                         
    //                 }
    //                 else{
    //                     //else not yet selected so just create the input
    //                     if($numkey == 0) {
    //                         $this_string = sprintf('<input type="radio" class="New_Entry_Safe_Value_Attribute_%s" name="New_Entry_List[%s][%s]" value="%s">%s</input>', $attribute_id, $email_key, $attribute_id, $attribute_value, $attribute_value);
    //                     }
    //                     else{
    //                         $this_string = sprintf('<input type="radio" name="New_Entry_List[%s][%s]" value="%s">%s</input>', $email_key, $attribute_id, $attribute_value, $attribute_value);
    //                     }
    //                 }
    //                 //$HTML_table_row= $HTML_table_row.$HTML_attribute_value_input.'<br>';
    //                 break;
                
    //             case "case_2" :

    //                 if(isset($Session->Committed_New_Entries[$email_key]) && isset($Session->Committed_New_Entries[$email_key][$attribute_id]) && $Session->Committed_New_Entries[$email_key][$attribute_id] === $attribute_value) {
    //                     //if the attribute value is the already selected, mark as checked
    //                     if($numkey == 0) {
    //                         $this_string = sprintf('<input type="radio" class="New_Entry_Safe_Value_Attribute_%s" name="New_Entry_List[%s][%s]" value="%s" checked>%s</input>', $attribute_id, $email_key, $attribute_id, $attribute_value, $Session->attribute_list[$attribute_id]['allowed_value_ids'][$attribute_value]);
    //                     }
    //                     else{
    //                         $this_string = sprintf('<input type="radio" class="New_Entry_Attribute_%s" name="New_Entry_List[%s][%s]" value="%s" checked>%s</input>', $attribute_id, $email_key, $attribute_value, $attribute_value, $Session->attribute_list[$attribute_id]['allowed_value_ids'][$attribute_value]);
    //                     }
                         
    //                 }
    //                 else{
    //                     //else not yet selected so just create the input
    //                     if($numkey == 0) {
    //                         $this_string = sprintf('<input type="radio" class="New_Entry_Safe_Value_Attribute_%s" name="New_Entry_List[%s][%s]" value="%s">%s</input>', $attribute_id, $email_key, $attribute_id, $attribute_value, $Session->attribute_list[$attribute_id]['allowed_value_ids'][$attribute_value]);
    //                     }
    //                     else{
    //                         $this_string = sprintf('<input type="radio" class="New_Entry_Attribute_%s" name="New_Entry_List[%s][%s]" value="%s">%s</input>', $attribute_id, $email_key, $attribute_id, $attribute_value, $Session->attribute_list[$attribute_id]['allowed_value_ids'][$attribute_value]);
    //                     }
    //                 }
    //                 //$HTML_table_row= $HTML_table_row.$HTML_attribute_value_input.'<br>';
    //                 break;

    //             case "case_3": 

    //                 if(isset($Session->Committed_New_Entries[$email_key]) && isset($Session->Committed_New_Entries[$email_key][$attribute_id]) && in_array($attribute_value, $Session->Committed_New_Entries[$email_key][$attribute_id])) {
    //                     //the current attribute value should already be checked

    //                     $this_string = sprintf('<input type="checkbox" class="New_Entry_Checkbox_Value_Attribute_%s" name="New_Entry_List[%s][%s][%s]" value="%s" checked>%s</input><br>', $attribute_id, $email_key, $attribute_id, $attribute_value, $attribute_value, $Session->attribute_list[$attribute_id]['allowed_value_ids'][$attribute_value]);
    //                 }
    //                 else{
    //                     //not already checked
    //                     $this_string = sprintf('<input type="checkbox" class="New_Entry_Checkbox_Value_Attribute_%s" name="New_Entry_List[%s][%s][%s]" value="%s">%s</input><br>', $attribute_id, $email_key, $attribute_id, $attribute_value, $attribute_value, $Session->attribute_list[$attribute_id]['allowed_value_ids'][$attribute_value]);

    //                 }
    //                 //$HTML_table_row = $HTML_table_row.$HTML_attribute_value_input.'<br>';
    //                break;
    //             default:
                    
    //         }
    //         $text = $text.$this_string.'<br>';

    //     }
    //     return $text;
    // }
                


    // function Get_New_Entry_Table_Navigation_Buttons () {

    //     $Session = $GLOBALS['plugins']['AttributeChangerPlugin']->Current_Session;

    //     $HTML_submit_buttons = '<input type="submit" name="New_Entries_Table_Submit_All" value="New_Entries_Table_Submit_All"></input>';
    //     if($Session->Current_New_Entry_Block_Number > 0) {
    //         $HTML_submit_buttons = $HTML_submit_buttons.'<input type="submit" name="value="New_Entries_Table_Previous_Page" value="New_Entries_Table_Previous_Page"></input>';
    //     }
    //     if($Session->Current_New_Entry_Block_Number < $Session->New_Entries_Number_Of_Blocks - 1) {
    //         $HTML_submit_buttons = $HTML_submit_buttons.'<input type="submit" name="New_Entries_Table_Next_page" value="New_Entries_Table_Next_page"></input>';
    //     }
    //     switch($Session->Current_New_Entries_Display_Amount){
    //         case 10:
    //             $HTML_Display_Size_Submit = '<select name="New_Entries_New_Display_Amount"><option value="10" checked>10</option><option value="100">100</option><option value="1000">1000</option><option value="10000">10000</option><option value="all">all</option>';
    //         case 100:
    //             $HTML_Display_Size_Submit = '<select name="New_Entries_New_Display_Amount"><option value="10">10</option><option value="100" checked>100</option><option value="1000">1000</option><option value="10000">10000</option><option value="all">all</option>';
    //         case 1000:
    //             $HTML_Display_Size_Submit = '<select name="New_Entries_New_Display_Amount"><option value="10">10</option><option value="100">100</option><option value="1000" checked>1000</option><option value="10000">10000</option><option value="all">all</option>';
    //         case 10000:

    //             $HTML_Display_Size_Submit = '<select name="New_Entries_New_Display_Amount"><option value="10">10</option><option value="100">100</option><option value="1000">1000</option><option value="10000" checked>10000</option><option value="all">all</option>';
    //         default:
    //             $HTML_Display_Size_Submit = '<select name="New_Entries_New_Display_Amount"><option value="10">10</option><option value="100">100</option><option value="1000">1000</option><option value="10000">10000</option><option value="all" checked>all</option>';
    //     }
    //     $HTML_Display_Size_Submit = $HTML_Display_Size_Submit.'<input type="submit" name="New_Entry_Change_Display_Amount" value="New_Entry_Change_Display_Amount"></input>';
    //     $HTML_Table_Navigation = $HTML_submit_buttons.$HTML_Display_Size_Submit.'</form>';
    //     $HTML_current_table_info = sprintf("<div>Current Block : %d of %d. Displaying %d entries per page.</div>", $Session->Current_New_Entry_Block_Number+1, $Session->New_Entries_Number_Of_Blocks, $Session->Current_New_Entries_Display_Amount);
    //     $HTML_Table_Navigation = $HTML_Table_Navigation.$HTML_current_table_info;

    //     return $HTML_Table_Navigation;
    // }





















    // function Get_Modify_Email_Block($email_key) {

    //     $Session = $GLOBALS['plugins']['AttributeChangerPlugin']->Current_Session;

    //     $HTML_table_row = '<td>';
    //     if(!isset($Session->Current_User_Values[$email_key])) {
    //         $GLOBALS['plugins']['AttributeChangerPlugin']->Get_Current_User_Values();

    //     }
         
    //     if(isset($Session->Committed_Modify_Entries[$email_key])) {
    //         $HTML_table_row = $HTML_table_row.sprintf('<tr><td>%s<br><input type="checkbox" class="Modify_Entry_Email" name="Modify_Entry_List[%s][%s]" value="include" checked>Include This Email</input><input type="hidden" name="Hidden_Modify_Entry_List[%s]" value="submitted"></td>',$email_key, $email_key, 'include', $email_key);
    //     }
    //     else{
    //         $HTML_table_row = $HTML_table_row.sprintf('<tr><td>%s<br><input type="checkbox" class="Modify_Entry_Email" name="Modify_Entry_List[%s][%s]" value="include">Include This Email</input><input type="hidden" name="Hidden_Modify_Entry_List[%s]" value="submitted"></td>',$email_key, $email_key, 'include', $email_key);
    //     }

    //     return $HTML_table_row;
    // }

    // function Get_Modify_Attribute_Block_Current_Values($email_key, $attribute_id) {
    //     $Session = $GLOBALS['plugins']['AttributeChangerPlugin']->Current_Session;
    //     $HTML_block = '';


    //     if(!isset($Session->Current_User_Values[$email_key][$attribute_id])) {
            
    //         return $HTML_block;
    //     }
        
    //     else {
            
    //         $case_array = array(
    //             'textarea' => 'case_1',
    //             'textline' => 'case_1',
    //             'hidden' => 'case_1',
    //             'date' => 'case_1',

    //             'checkbox' => 'case_2',
    //             'radio' => 'case_2',
    //             'select' => 'case_2',

    //             'checkboxgroup' => 'case_3',
    //         );
    //         switch ($case_array[$Session->attribute_list[$attribute_id]['type']]) {
    //             case 'case_3':
                    
    //                 foreach ($Session->Current_User_Values[$email_key]['attributes'][$attribute_id] as $key => $current_single_value) {
                        
    //                     if(isset($Session->Committed_Modify_Entries[$email_key]) && isset($Session->Committed_Modify_Entries[$email_key][$attribute_id])) {
    //                         if(in_array($current_single_value, $Session->Committed_Modify_Entries[$email_key][$attribute_id])) {
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
                
    //             case 'case_2' :
                    
    //                 if(isset($Session->Committed_Modify_Entries[$email_key]) && isset($Session->Committed_Modify_Entries[$email_key][$attribute_id])) {
    //                     if($Session->Committed_Modify_Entries[$email_key][$attribute_id] === $Session->Current_User_Values[$email_key][$attribute_id]) {
    //                         $HTML_attribute_value_input = sprintf('<input type="radio class="Current_Modify_Attribute_Value_%s" name="Modify_Entry_List[%s][%s]" value="%s" checked>%s</input><br>', $attribute_id, $email_key, $attribute_id, $Session->Current_User_Values[$email_key][$attribute_id], $Session->attribute_list[$attribute_id]['allowed_value_ids'][$Session->Current_User_Values[$email_key][$attribute_id]]);                             
    //                     }
    //                     else {
    //                         $HTML_attribute_value_input = sprintf('<input type="radio" class="Current_Modify_Attribute_Value_%s" name="Modify_Entry_List[%s][%s]" value="%s">%s</input><br>', $attribute_name, $email_key, $attribute_id, $Session->Current_User_Values[$email_key]['attributes'][$attribute_id], $Session->Current_User_Values[$email_key][$attribute_id], $Session->attribute_list[$attribute_id]['allowed_value_ids'][$Session->Current_User_Values[$email_key][$attribute_id]]);
    //                     }
    //                 }
    //                 else{
    //                     //no current committed entries, so this current value should be checked
    //                     $HTML_attribute_value_input = sprintf('<input type="radio" class="Current_Modify_Attribute_Value_%s" name="Modify_Entry_List[%s][%s]" value="%s" checked>%s</input><br>', $attribute_id, $email_key, $attribute_id, $Session->Current_User_Values[$email_key][$attribute_id], $Session->Current_User_Values[$email_key][$attribute_id], $Session->Current_User_Values[$email_key][$attribute_id]);
    //                 }
    //                 $HTML_block = $HTML_block.$HTML_attribute_value_input;
    //                 break;

    //             case 'case_1':

    //                 if(isset($Session->Committed_Modify_Entries[$email_key]) && isset($Session->Committed_Modify_Entries[$email_key][$attribute_id])) {

    //                     if($Session->Committed_Modify_Entries[$email_key][$attribute_id] === $Session->Current_User_Values[$email_key][$attribute_id]) {
    //                         $HTML_attribute_value_input = sprintf('<input type="radio" class="Current_Modify_Attribute_Value_%s" name="Modify_Entry_List[%s][%s]" value="%s" checked>%s</input><br>', $attribute_id, $email_key, $attribute_id, $Session->Current_User_Values[$email_key][$attribute_id], $Session->Current_User_Values[$email_key][$attribute_id]);                             
    //                     }
    //                     else {
    //                         $HTML_attribute_value_input = sprintf('<input type="radio" class="Current_Modify_Attribute_Value_%s" name="Modify_Entry_List[%s][%s]" value="%s">%s</input><br>', $attribute_id, $email_key, $attribute_id, $Session->Current_User_Values[$email_key][$attribute_id], $Session->Current_User_Values[$email_key][$attribute_id]);
    //                     }
    //                 }
    //                 else {

    //                     //no current committed entries, so this current value should be checked
    //                     $HTML_attribute_value_input = sprintf('<input type="radio" class="Current_Modify_Attribute_Value_%s" name="Modify_Entry_List[%s][%s]" value="%s">%s</input><br>', $attribute_id, $email_key, $attribute_id, $Session->Current_User_Values[$email_key][$attribute_id], $Session->Current_User_Values[$email_key][$attribute_id], $attribute_id);

    //                 }
    //                 $HTML_block = $HTML_block.$HTML_attribute_value_input;
    //                 break;
    //             default:
    //                 break;
    //         }
    //         return $HTML_block;
    //     }
    // }

    // function Get_Modify_Table_Header() {

    //     $Session = $GLOBALS['plugins']['AttributeChangerPlugin']->Current_Session;

    //     $HTML_Modify_Table_Header = sprintf('<form name="Modify_Entry_Submit_Form_Block__%d" action="" method="post"><input type="hidden" name="Modify_Entry_Form_Submitted" value="submitted">', $Session->Current_Modify_Entry_Block_Number);
    //     $HTML_Modify_Table_Header = $HTML_Modify_Table_Header.sprintf('<table id="Modify_User_Attribute_Select_Table_Block__%d">', $Session->Current_Modify_Entry_Block_Number);

    //     $HTML_table_row = sprintf('<tr><td>EMAIL<br><input type="button" id="Modify_Entry_Include_All_Emails" name="Modify_Entry_Include_All_Emails" value="Include All Emails" onClick="checkAll_ModifyEntry_Emails()"></input>');
    //     $HTML_table_row = $HTML_table_row.sprintf('<input type="button" id="Modify_Entry_Remove_All_Emails" name="Modify_Entry_Remove_All_Emails" value="Remove All Emails" onClick="removeAll_ModifyEntry_Emails()"></input></td>');

    //     $HTML_Modify_Table_Header = $HTML_Modify_Table_Header.$HTML_table_row;

    //     foreach ($Session->attribute_list as $attribute_id => $attribute_info) {

    //         $HTML_table_row = sprintf('<td>Attribute: %s<input type="checkbox" name="Modify_Entry_Attribute_Column_Select[%s]" value="checked" %s>',$attribute_info['name'], $attribute_id, in_array($attribute_id, $Session->Modify_Entries_Columns_To_Select)?'checked':'');
    //         if($attribute_info['type'] === 'checkboxgroup') {
    //             $HTML_table_row = $HTML_table_row.sprintf('<br><input type="button" name="Modify_Entry_Include_All_Checkboxgroup_%s" value="Include All Checkboxgroup Values" onClick="checkAll_ModifyEntry_CheckboxGroup(\'%s\')"></input>', $attribute_id, $attribute_id);
    //             $HTML_table_row = $HTML_table_row.sprintf('<br><input type="button" name="Modify_Entry_Remove_All_Checkboxgroup_%s" value="Remove All Checkboxgroup Values" onClick="removeAll_ModifyEntry_CheckboxGroup(\'%s\')"></input></td>', $attribute_id, $attribute_id);
    //         }
    //         else{
    
    //             $HTML_table_row = $HTML_table_row.sprintf('<br><input type="button" name="Modify_Entry_Include_All_Safe_Values_%s" value="Include All Safe Values" onClick="checkAll_ModifyEntry_SafeValues(\'%s\')"></input>', $attribute_id, $attribute_id);
    //             $HTML_table_row = $HTML_table_row.sprintf('<br><input type="button" name="Modify_Entry_Remove_All_Safe_Values_%s"  value="Remove All Safe Values" onClick="removeAll_ModifyEntry_SafeValues(\'%s\')"></input></td>', $attribute_id, $attribute_id);
    //         }

    //         $HTML_Modify_Table_Header = $HTML_Modify_Table_Header.$HTML_table_row;

    //     }

    //     return $HTML_Modify_Table_Header;

    // }

    // function Get_Modify_Table_Navigation_Buttons() {

    //     $Session = $GLOBALS['plugins']['AttributeChangerPlugin']->Current_Session;

    //     $HTML_Navigation = '<input type="submit" name ="Modify_Entries_Table_Submit_All" value="Modify_Entries_Table_Submit_All"></input>';
    //     if($Session->Current_New_Entry_Block_Number > 0) {
    //         $HTML_Navigation = $HTML_Navigation.'<input type="submit" name ="Modify_Entries_Table_Previous_Page" value="Modify_Entries_Table_Previous_Page"></input>';
    //     }
    //     if($Session->Current_New_Entry_Block_Number < $Session->New_Entries_Number_Of_Blocks - 1) {
    //         $HTML_Navigation = $HTML_Navigation.'<input type="submit" name ="Modify_Entries_Table_Next_Page" value="Modify_Entries_Table_Next_Page"></input>';
    //     }
    //     switch($Session->Current_Modify_Entries_Display_Amount){
    //         case 10:
    //             $HTML_Display_Size_Submit = '<select name="Modify_Entries_New_Display_Amount"><option value="10" checked>10</option><option value="100">100</option><option value="1000">1000</option><option value="10000">10000</option><option value="all">all</option>';
    //         case 100:
    //             $HTML_Display_Size_Submit = '<select name="Modify_Entries_New_Display_Amount"><option value="10">10</option><option value="100" checked>100</option><option value="1000">1000</option><option value="10000">10000</option><option value="all">all</option>';
    //         case 1000:
    //             $HTML_Display_Size_Submit = '<select name="Modify_Entries_New_Display_Amount"><option value="10">10</option><option value="100">100</option><option value="1000" checked>1000</option><option value="10000">10000</option><option value="all">all</option>';
    //         case 10000:
    //             $HTML_Display_Size_Submit = '<select name="Modify_Entries_New_Display_Amount"><option value="10">10</option><option value="100">100</option><option value="1000">1000</option><option value="10000" checked>10000</option><option value="all">all</option>';
    //         default:
    //             $HTML_Display_Size_Submit = '<select name="Modify_Entries_New_Display_Amount"><option value="10">10</option><option value="100">100</option><option value="1000">1000</option><option value="10000">10000</option><option value="all" checked>all</option>';

    //     }

    //     $HTML_Display_Size_Submit = $HTML_Display_Size_Submit.'<input type="submit" name="Modify_Entry_Change_Display_Amount" value="Modify_Entry_Change_Display_Amount"></input>';
    //     $HTML_Navigation = $HTML_Navigation.$HTML_Display_Size_Submit.'</form>';
    //     $HTML_current_table_info = sprintf("<div>Current Block : %d of %d. Displaying %d entries per page.</div>", $Session->Current_Modify_Entry_Block_Number+1, $Session->Modify_Entries_Number_Of_Blocks, $Session->Current_Modify_Entries_Display_Amount);
    //     $HTML_Navigation = $HTML_Navigation.$HTML_current_table_info;
    //     return $HTML_Navigation;
    // }

    // function Get_Modify_Entry_Table_Block() {
    //     $Session = $GLOBALS['plugins']['AttributeChangerPlugin']->Current_Session;

    //     $Current_Modify_Entry_Block = array_slice($Session->Modify_Entry_List, $Session->Current_Modify_Entry_Block_Number*$Session->Current_Modify_Entries_Display_Amount, $Session->Current_Modify_Entries_Display_Amount);

    //     $HTML_Display_Text = Get_Modify_Table_Header().'</tr>';

    //     foreach ($Current_Modify_Entry_Block as $email_key => $modify_user_attributes_and_values) {
        
    //         $HTML_table_row = '<tr><td>'.Get_Modify_Email_Block($email_key).'</td>';

    //         foreach ($Session->attribute_list as $attribute_id => $attribute_info) {
    //             $HTML_table_row = $HTML_table_row.'<td>';

    //             $HTML_table_row = $HTML_table_row.Get_Modify_Attribute_Block_Current_Values($email_key, $attribute_id);
    //             if($attribute_info['type'] === 'checkboxgroup') {
    //                 $HTML_table_row = $HTML_table_row.Get_Modify_Attribute_Value_Display_Checkboxgroup_New_Vals($email_key, $attribute_id);
    //             }
    //             else{
    //                 $HTML_table_row = $HTML_table_row.Get_Modify_Attribute_Value_Display_Other_Type_New_Vals($email_key, $attribute_id);
    //             }
    //             $HTML_table_row = $HTML_table_row.'</td>';
    //         }
    //         $HTML_Display_Text = $HTML_Display_Text.$HTML_table_row .'</tr>';
    //     }
    //     $HTML_Display_Text = $HTML_Display_Text.'</table>'.Get_Modify_Table_Navigation_Buttons();
    //     //$HTML_Display_Text = '';
    //     return $HTML_Display_Text;
    // }

    // function Get_Modify_Attribute_Value_Display_Checkboxgroup_New_Vals($email_key, $attribute_id) {
    //     $Session = $GLOBALS['plugins']['AttributeChangerPlugin']->Current_Session;
    //     $HTML_value_block = '';

    //     if(!isset($Session->Modify_Entry_List[$email_key][$attribute_id]) || count($Session->Modify_Entry_List[$email_key][$attribute_id]) == 0) {
    //         return $HTML_value_block;
    //     }

    //     else foreach ($Session->Modify_Entry_List[$email_key][$attribute_id] as $numkey => $checkbox_value_id) {

    //         if(isset($Session->Committed_Modify_Entries[$email_key]) && isset($Session->Committed_Modify_Entries[$email_key][$attribute_id])) {

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
    //     $Session = $GLOBALS['plugins']['AttributeChangerPlugin']->Current_Session;

    //     if($Session->attribute_list[$attribute_id]['type'] === 'checkboxgroup') {
    //         return $HTML_value_block;
    //     }


    //     $case_array = array(
    //         'textarea' => 'case_1',
    //         'textline' => 'case_1',
    //         'hidden' => 'case_1',
    //         'date' => 'case_1',

    //         'checkbox' => 'case_2',
    //         'radio' => 'case_2',
    //         'select' => 'case_2',

    //         'checkboxgroup' => 'case_3',
    //     );

    //     foreach ($Session->Modify_Entry_List[$email_key][$attribute_id] as $numkey => $checkbox_value_id) {

    //         if(!isset($case_array[$Session->attribute_list[$attribute_id]['type']])) {
    //             return $HTML_value_block;
    //         }
    //         $this_value ='';

    //         if($case_array[$Session->attribute_list[$attribute_id]['type']] === 'case_2') {
    //             $this_value = $Session->$attribute_list[$attribute_id]['allowed_value_ids'][$checkbox_value_id];
    //         }
    //         else{
    //             $this_value = $checkbox_value_id;
    //         }

    //         if(isset($Session->Committed_Modify_Entries[$email_key]) && isset($Session->Committed_Modify_Entries[$email_key][$attribute_id])) {

    //             if($checkbox_value_id === $Session->Committed_Modify_Entries[$email_key][$attribute_id]) {
    //                 if( ($numkey == 0) && (!isset($Session->Current_User_Values[$email_key][$attribute_id]) || count($Session->Current_User_Values[$email_key][$attribute_id]) == 0) ) {
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


                    // class radio(char*){
                    //     radio::(){
                    //         return [char] || err
                    //     }

                    //     radio::new_Class(char[]){
                    //         super.class =. char
                    //     }
                    //     radio::remove_Class(char[]){
                    //         int index=0;
                    //         $array = array_keys(super);
                    //         for(i=0; i<array.length(); i++){
                    //             if($array[i] == char[0]){
                    //                 good = true
                    //                 $i++;
                    //                 $k=$i
                    //                 $n = 0
                    //                 while(good=true)
                    //                 {
                    //                     if(!$array[k] == char[$n]){
                    //                         if(k++ == array.length){
                    //                             go to:
                    //                                 set chars to null
                    //                         }
                    //                     }
                    //                 }
                    //             }
                    //         }
                    //     }
                    // }

?>