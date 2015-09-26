<?php



    function Initialize_New_Entries_Display() {
        
        $Session = $GLOBALS['plugins']['AttributeChangerPlugin']->Current_Session;

        if(count($Session->New_Entry_List) == 0) {
            return null;
        }

        $Session->Commited_New_Entires = array();
        ksort($Session->New_Entry_List);

        $Session->Current_New_Entries_Display_Amount = 100;
        $Session->New_Entries_Total_Amount = count($Session->New_Entry_List);
        $Session->New_Entires_Number_Of_Blocks = $Session->New_Entries_Total_Amount/$Session->Current_New_Entries_Display_Amount + (($Session->New_Entries_Total_Amount % $Session->Current_New_Entries_Display_Amount)? 1:0);
     
        $Session->Current_New_Entry_Block_Number = 0;
        return true;
         
    }
    
    function New_Entry_Change_Display_Amount($New_Amount) {

        $Session = $GLOBALS['plugins']['AttributeChangerPlugin']->Current_Session;
        $new_display_amounts = array(
            10=>true,
            100=>true,
            1000=>true,
            10000=>true,
            "all"=>true,
            );
        if(!isset($new_display_amounts[$New_Amount]) || $new_display_amounts[$New_Amount] != true) {
            return false;
        }
        if($New_Amount === 'all') {
            $Session->New_Entires_Number_Of_Blocks =1;
            $Session->Current_New_Entries_Display_Amount = $Session->New_Entries_Total_Amount;
            $Session->Current_New_Entry_Block_Number = 0;
            return true;
        }
        $Session->Current_New_Entries_Display_Amount = $New_Amount;
        $Session->New_Entires_Number_Of_Blocks = $Session->New_Entries_Total_Amount/$Session->Current_New_Entries_Display_Amount + (($Session->New_Entries_Total_Amount % $Session->Current_New_Entries_Display_Amount)? 1:0);
        


        $Session->Current_New_Entry_Block_Number = 0;
        return true;
    }

    function New_Entry_Display_Next_Page() {
        $Session = $GLOBALS['plugins']['AttributeChangerPlugin']->Current_Session;
        if($Session->Current_New_Entry_Block_Number < $Session->New_Entires_Number_Of_Blocks-1) {
            //$Session->Current_New_Entry_Block_Number = $Session->Current_New_Entry_Block_Number + 1;
            return true;
        }
        else{
            //because there are no more blocks
            return false;
        }
    }
    
    function New_Entry_Display_Previous_Page() {
        $Session = $GLOBALS['plugins']['AttributeChangerPlugin']->Current_Session;
        if($Session->Current_New_Entry_Block_Number > 0) {
            $Session->Current_New_Entry_Block_Number = $Session->Current_New_Entry_Block_Number-1;
            return true;
        }
        else{
            //because there are no more blocks
            return false;
        }
    }




    function Initialize_Modify_Entries_Display() {
        $Session = $GLOBALS['plugins']['AttributeChangerPlugin']->Current_Session;
            
        if(count($Session->Modify_Entry_List) == 0) {
            print("ARRARARAR brroooo");
            return null; 
        }
        ksort($Session->Modify_Entry_List);
        $Session->Commited_Modify_Entries = array();

        $Session->Current_Modify_Entries_Display_Amount = 100;
        $Session->Modify_Enties_Total_Amount = count($Session->Modify_Entry_List);
        $Session->Modify_Entires_Number_Of_Blocks = $Session->Modify_Enties_Total_Amount/$Session->Current_Modify_Entries_Display_Amount + (($Session->Current_Modify_Entries_Display_Amount % $Session->Modify_Enties_Total_Amount)? 1:0);
     
        $Session->Current_Modify_Entry_Block_Number = 0;
        return true;
         
    }   
    // function Modify_Entry_Display_Next_Page() {
    //     if($Session->Current_Modify_Entry_Block_Number < $Session->Modify_Entires_Number_Of_Blocks-1) {
    //         $Session->Current_Modify_Entry_Block_Number++;
    //         return Get_Modify_Entry_Table_Block($Session->Current_Modify_Entry_Block_Number);
    //     }
    //     else{
    //         //because there are no more blocks
    //         return false;
    //     }
    // }
    // function Modify_Entry_Display_Previous_Page() {
    //     if($Session->Current_Modify_Entry_Block_Number > 0) {
    //         $Session->Current_Modify_Entry_Block_Number--;
    //         return Get_Modify_Entry_Table_Block($Session->Current_Modify_Entry_Block_Number);
    //     }
    //     else{
    //         //because there are no more blocks
    //         return false;
    //     }
    // }
    // function Modify_Entry_Change_Display_Amount($New_Amount) {
    //     if($New_Amount !== (10|100|1000|10000|"all")) {
    //         return false;
    //     }
    //     if($New_Amount === 'all') {
    //         $Session->New_Entires_Number_Of_Blocks =1;
    //         $Session->Current_Modify_Entries_Display_Amount = $Session->Modify_Enties_Total_Amount;
    //         $Session->Current_Modify_Entry_Block_Number = 0;
    //         return true;
    //     }
    //     $Session->Current_Modify_Entries_Display_Amount = $New_Amount;
    //     $Session->Modify_Entires_Number_Of_Blocks = $Session->Modify_Enties_Total_Amount/$Session->Current_Modify_Entries_Display_Amount + (($Session->Current_Modify_Entries_Display_Amount % $Session->Modify_Enties_Total_Amount)? 1:0);
    //     $Session->Current_New_Entry_Block_Number = 0;
    //     return true;
    // }




?>