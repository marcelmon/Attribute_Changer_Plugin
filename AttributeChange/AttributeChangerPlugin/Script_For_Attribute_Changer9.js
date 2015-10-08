    /*






    Require easy to initiate operation using selects
    TYPE 2 + 1

    _Action     _Subject            _Conditional    _Subject      _Condition
    |check      |All                |               |               |
    |uncheck    |Safe_Value         |unless         |Any            |Exists
                |Current_Value      |if             |Safe_Value     |Not_Exists
                |Other_Value                        |Current_Value  |Checked
                                                    |Other_Value    |Not_Checked
                
    TYPE 3 

    _Action     _Subject            *_Conditional   *_Subject       *_Condition
    |check      |All                |               |               |
    |uncheck    |Checkbox_Value     |unless         |Any            |Exists
                |Current_Value      |if             |Checkbox_Value |Not_Exists
                |Other_Value                        |Current_Value  |Checked
                                                    |Other_Value    |Not_Checked

    */


    function Process_Input_Command(the_string) {
        var commands = the_string.split(' ');
        if (!commands || commands.length <= 0) {
            return -1;
        }
        var action = _Action(commands[0]);
        if(!action) {
            return -1;
        }
        if(commands.length == 1) {

        }
    }

    function process_command(attribute_id, attribute_type, action, subject_1, conditional, subject_2, condition) {
        if(!action || !subject){
            return -1;
        }
        var this_action = _Action(action);
        if(!this_action){
            return -1;
        }

        var this_subject_1 = _Subject(subject_1);
        if(!this_subject_1){
            return -1;
        }

        if(!conditional){
            var className = this_subject_1
        }

    }


    function _Action(action) {
        switch(action) {

            case 'Check':
                return Check;

            case 'Uncheck':
                return Uncheck;

            default:
                return -1;
        }
    }

    var Check = function (subject) {
        this.Action = 'Check';
        this.subject = subject;

        this.conditional = null;

        this.setConditional = function(conditional) {
            this.conditional = conditional;
        }

        this.check_subject = function(subject){
            for(var i=0; i<subject.length; i++) {
                if(subject[i].className.indexOf('Checked') < 0){
                    subject[i].className += ' Checked';
                    if(subject[i].type == 'checkbox' || subject[i].type == 'radio') {
                        subject[i].checked = true;
                    }
                }
            }       
        }

        this.execute = function(){
            if(!this.conditional) {

                this.check_subject(this.subject);
            }
            else{
                var new_subject = new array();
                for(var j=0; j<this.subject.length; j++){
                    if(this.conditional.execute(this.subject[i])){
                        new_subject.push(this.subject)
                    }
                }
                this.check_subject(new_subject);
            }
            return true;
        }
    }

    var Uncheck= function(subject) {
        this.Action = 'Uncheck';
        this.subject = subject;

        this.conditional = null;

        this.setConditional = function(conditional) {
            this.conditional = conditional;
        }

        this.uncheck_subject = function(subject){
            for(var i=0; i<subject.length; i++) {
                
                var class_names = subject[i].className.split(' ');
                var index = class_names.indexOf('Checked');
                if(index > -1){
                    class_names.splice(index, 1);
                    if(subject[i].type == 'checkbox' || subject[i].type == 'radio') {
                        subject[i].checked = false;
                    }
                }
                subject[i].className = class_names.join(' ');
            }       
        }

        this.execute = function(){
            if(!this.conditional) {

                this.uncheck_subject(this.subject);
            }
            else{
                var new_subject = this.conditional(this.subject);
                this.uncheck_subject(new_subject);
            }
            return true;
        }
    }

    function _Subject(subject) {
        switch(subject){

            case 'All':
                return All;

            case 'Any':
                return Any;

            case 'Safe_Value':
                return Safe_Value;

            case 'Current_Value':
                return Current_Value;

            case 'Other_Value':
                return Other_Value;

            case 'Checkbox_Value':
                return Checkbox_Value;

            default:
                return -1;
        }
    }


    var All = function (attribute_id) {
        
        var attribute_class = 'attribute_'.concat(attribute_id);
        var attribute_elements = document.getElementsByClassName(attribute_class);
        return attribute_elements;
    }


    var Any= function(attribute_id) {
        var attribute_class = 'attribute_'.concat(attribute_id);
        var attribute_elements = document.getElementsByClassName(attribute_class);
        return attribute_elements;
    }
 

    var Safe_Value = function(attribute_id) {
        return Filter_Value(attribute_id, 'Safe_Value');
    }


    var Current_Value = function(attribute_id) {
        return Filter_Value(attribute_id, 'Current_Value');
    }

    var Other_Value= function(attribute_id) {
        var all = All(attribute_id);

        var others = new array();
        //now have 2 arrays to compare to all, all the reset 
        for(var i=0; i<all.length; i++) {
            if(all[i].className.indexOf('Current_Value') > -1){
                continue;
            }
            if(all[i].className.indexOf('Safe_Value') > -1){
                continue;
            }
            others.push(all[i]);
        }
        return others;
    }


    var Checkbox_Value= function(attribute_id) {

        return Filter_Value(attribute_id, 'Checkbox_Value');
    }
//Checkbox, other, current safe
    var Filter_Value = function(attribute_id, class_string){
        var elements = document.getElementsByClassName('attribute_'.concat(attribute_id));
        var return_list = new Array();

        for(var i=0; i < elements.length; i++) {
            if(elements[i].className.indexOf(class_string) < 0) {
                return_list.push(elements[i]);
            }
        }
        return return_list;
    }




    function _Conditional(conditional) {
        switch(conditional){

            case 'Unless':
                return Unless;

            case 'If':
                return If;

            default:
                return -1;
        }
    }
    

    var Unless = function (leading_subject, condition, conditioned_subject) {
        this.Conditional = 'Unless'
        this.leading_subject = leading_subject;
        this.Condition = condition;
        return this;

        this.execute = function() {
            if(this.Condition(this.leading_subject)) {
                return false;
            }
        }
    }

    var If = function(leading_subject, condition, conditioned_subject) {
        this.Conditional = 'Unless'
        this.leading_subject = leading_subject;
        this.conditioned_subject = conditioned_subject;
        //are in the same columns, so attribute id, email key are the same
        //name would be the same, unless its checkbox

        this.Condition = condition;
        return this;

        this.execute = function() {
            if(this.Condition(this.conditioned_subject)) {
                return true;
            }
        }
    }




    function _Condition(condition) {
        switch(condition){

            case 'Exists':
                return Exists;

            case 'Not_Exists':
                return Not_Exists;

            case 'Checked':
                return Checked;

            case 'Not_Checked':
                return Not_Checked;

            default:
                return -1;
        }
    }
    
    
    var Exists = function (leading_subject, subject) {
        Filter_Conditional(leading_subject, subject);
    }  

    var Not_Exists = function(leading_subject, subject) {
        (Filter_Conditional(leading_subject, subject)) ? false : true;
    }

    var Checked = function(leading_subject) {
        Filter_Conditional(leading_subject, 'Checked');
    }

    var Not_Checked = function(leading_subject, subject) {
        (Filter_Conditional(leading_subject, 'Checked')) ? false : true;
    }


    var Filter_Conditional = function(leading_subject, subject) {

        i=leading_subject.tagName;
        while(i != 'ul'){
            leading_subject = leading_subject.parentNode;
            i = leading_subject.tagName;
        }
        child_subject = leading_subject.childNodes;
        for(var i=0; i<child_subject.length; i++) {
            if(child_subject[i].className.indexOf(subject) > -1) {
                return true;
            }
        }
        return false;
    }









    function Clear_Column_Select(column_class) {
        var column_radios = document.getElementsByClassName(column_class);
        var i;
        for(i=0; i<column_radios.length; i++) {
            column_radios[i].checked = false;
        }
    }


    function email_block_clicked(e) {
        var classes = e.className.split(' ');
       // window.alert(e.className);
        var index = classes.indexOf('Checked');
        var selector = e.getElementsByClassName('Email_Block')[0];

        if(index > -1){
            classes.splice(index, 1);
            selector.checked= false;
        }
        else{
            classes.push('Checked');
            selector.checked = true;
        }

        e.className = classes.join(' ');
        selector.className = e.className;
    }



    function list_element_clicked(e) {

        var classes = e.className.split(' ');
       // window.alert(e.className);
        var index = classes.indexOf('Checked');
        var selector = e.getElementsByTagName('input')[0];

        if(index > -1){
            classes.splice(index, 1);
            selector.checked= false;
        }
        else{
            classes.push('Checked');
            selector.checked = true;
        }

        e.className = classes.join(' ');
        selector.className = e.className;

       // window.alert(e.className);
    }

    function checkAll_Emails() {
        var elements = document.getElementsByClassName('Email_Block');
        var i;
        for(i=0; i<elements.length; i++) {
            var classes = elements[i].className.split(' ');
            var index = classes.indexOf('Checked');

            if(index < 0) {
                classes.push('Checked');
                if(elements[i].type == 'checkbox') {
                    elements[i].checked = true;
                }   
            }
            elements[i].className = classes.join(' ');
        }
    }

    function removeAll_Emails() {
        var elements = document.getElementsByClassName('Email_Block');
        var i;
        for(i=0; i<elements.length; i++) {
            var classes = elements[i].className.split(' ');
            var index = classes.indexOf('Checked');

            if(index > -1) {
                classes.splice(index, 1);
                if(elements[i].type == 'checkbox') {
                    elements[i].checked = false;
                }   
            }
            elements[i].className = classes.join(' ');
        }
    }



function check_all_Safe_Value(attribute_id, rules[]) {
    var attribute_name = ('attribute_' += attribute_id);
    var elements = document.getElementsByClassName(attribute_name);

    if(rules != null && rules.length > 0) {
        ->or checked, or not checked, check all not checked safe values
    }

    check all Safe_Values unless (Current && || Other) is Checked

    check all Current Values unless (Safe_Value && || Other) is Checked





    if(!rules){

        var i;
        for(i=0; i<elements.length; i++) {

        }

    }

}

    function check_all_Safe_Value(attribute) {

    }

    function check_all_Checked_or_Safe_Value(attribute) {

    }

    function check_all_Checked_or_Current_Value


    function check_all_current(attribute) {

    }

    function check_all_current

    function check_all_checkbox(attribute) {

    }



    function check_all_current_values(attribute) {

    }

    function uncheck_all_current_values(attribute) {

    }


























    function removeAll_NewEntry_Emails() {
        var element_blocks = document.getElementsByClassName('New_Entry_Email');
        var i;
        for(i=0; i<element_blocks.length; i++) {
            element_blocks[i].checked = false;
        }
    }


    function checkAll_NewEntry_CheckboxGroup(attribute) {
        var class_string = 'New_Entry_Checkbox_Value_Attribute_'.concat(attribute);

        var checkboxgroup_element_blocks = document.getElementsByClassName(class_string);
        for(i=0; i<checkboxgroup_element_blocks.length; i++) {
            checkboxgroup_element_blocks[i].checked = true;
        }
    }

    function removeAll_NewEntry_CheckboxGroup(attribute) {
        var class_string = 'New_Entry_Checkbox_Value_Attribute_'.concat(attribute);

        var checkboxgroup_element_blocks = document.getElementsByClassName(class_string);
        for(i=0; i<checkboxgroup_element_blocks.length; i++) {
            checkboxgroup_element_blocks[i].checked = false;
        }
    }

    function checkAll_NewEntry_SafeValues(attribute) {
        
        var class_string = 'New_Entry_Safe_Value_Attribute_'.concat(attribute);



        var checkboxgroup_safe_element_blocks = document.getElementsByClassName(class_string);

        for(i=0; i<checkboxgroup_safe_element_blocks.length; i++) {

            checkboxgroup_safe_element_blocks[i].checked = true;    
        }
    }

    function checkAll_NewEntry_SafeValues_OrChecked(attribute) {
        var class_string = 'New_Entry_Safe_Value_Attribute_'.concat(attribute);

        var checkboxgroup_safe_element_blocks = document.getElementsByClassName(class_string);
        for(i=0; i<checkboxgroup_safe_element_blocks.length; i++) {
            var current_name = checkboxgroup_safe_element_blocks[i].name;
            var same_name_elements = document.getElementsByName(current_name);
            var has_checked = false;
            for(j=0; j < same_name_elements; j++) {
                if(same_name_elements[j].checked == true) {
                    has_checked = true;
                    break;
                }
            }
            if(has_checked == false) {
                checkboxgroup_safe_element_blocks[i].checked = true;
            }
        }
    }

    function removeAll_NewEntry_SafeValues(attribute) {
        var class_string = 'New_Entry_Safe_Value_Attribute_'.concat(attribute);

        var checkboxgroup_safe_element_blocks = document.getElementsByClassName(class_string);
        for(i=0; i<checkboxgroup_safe_element_blocks.length; i++) {
            checkboxgroup_safe_element_blocks[i].checked = false;
        }
    }
    function removeAll_NewEntry_SafeValues_OrChecked(attribute) {

        var class_string = 'New_Entry_Safe_Value_Attribute_'.concat(attribute);
        var class_string_2 = 'New_Entry_Attribute_'.concat(attribute);

        var checkboxgroup_safe_element_blocks = document.getElementsByClassName(class_string);
        for(i=0; i<checkboxgroup_safe_element_blocks.length; i++) {
            checkboxgroup_safe_element_blocks[i].checked = false;
        }

        var other_element_blocks = document.getElementsByClassName(class_string_2);
        for(i=0; i<checkboxgroup_safe_element_blocks.length; i++) {
            checkboxgroup_safe_element_blocks[i].checked = false;
        }
    }








    function checkAll_ModifyEntry_CheckboxGroup(attribute) {
        var class_string = 'Modify_Entry_Checkbox_Value_Attribute_'.concat(attribute);


        var checkboxgroup_element_blocks = document.getElementsByClassName(class_string);
        for(i=0; i<checkboxgroup_element_blocks.length; i++) {
            checkboxgroup_element_blocks[i].checked = true;
        }
    }

    function removeAll_ModifyEntry_CheckboxGroup(attribute) {

        var class_string = 'Modify_Entry_Checkbox_Value_Attribute_'.concat(attribute);

        var checkboxgroup_element_blocks = document.getElementsByClassName(class_string);
        for(i=0; i<checkboxgroup_element_blocks.length; i++) {
            checkboxgroup_element_blocks[i].checked = false;
        }
    }

    function checkAll_ModifyEntry_SafeValues(attribute) {

        var class_string = 'Modify_Entry_Safe_Value_Attribute_'.concat(attribute);

        var checkboxgroup_safe_element_blocks = document.getElementsByClassName(class_string);

        for(i=0; i<checkboxgroup_safe_element_blocks.length; i++) {

            checkboxgroup_safe_element_blocks[i].checked = true;    
        }
    }

    function checkAll_ModifyEntry_SafeValues_OrChecked(attribute) {
        var class_string = 'Modify_Entry_Safe_Value_Attribute_'.concat(attribute);

        var safeOrChecked_element_blocks = document.getElementsByClassName(class_string);
        for(i=0; i<safeOrChecked_element_blocks.length; i++) {
            var current_name = safeOrChecked_element_blocks[i].name;
            var same_name_elements = document.getElementsByName(current_name);
            var has_checked = false;
            for(j=0; j < same_name_elements; j++) {
                if(same_name_elements[j].checked == true) {
                    has_checked = true;
                    break;
                }
            }
            if(has_checked == false) {
                safeOrChecked_element_blocks[i].checked = true;
            }
        }
    }

    function removeAll_ModifyEntry_SafeValues(attribute) {
        var class_string = 'Modify_Entry_Safe_Value_Attribute_'.concat(attribute);

        var safe_element_blocks = document.getElementsByClassName(class_string);
        for(i=0; i<safe_element_blocks.length; i++) {
            safe_element_blocks[i].checked = false;
        }
    }

    function removeAll_ModifyEntry_SafeValues_OrChecked(attribute) {
        var class_string = 'Modify_Entry_Safe_Value_Attribute_'.concat(attribute);
        var safe_element_blocks = document.getElementsByClassName(class_string);


        for(i=0; i<safe_element_blocks.length; i++) {
            safe_element_blocks[i].checked = false;
        }

        var class_string_2 = 'Modify_Entry_'.concat(attribute);
        var safe_element_blocks = document.getElementsByClassName(class_string_2);
        for(i=0; i<safe_element_blocks.length; i++) {
            safe_element_blocks[i].checked = false;
        }
    }



    function checkAll_ModifyEntry_Emails() {
        var element_blocks = document.getElementsByClassName('Modify_Entry_Email');
        var i;
        for(i=0; i<element_blocks.length; i++) {
            element_blocks[i].checked = true;
        }
    }

    function removeAll_ModifyEntry_Emails() {
        var element_blocks = document.getElementsByClassName('Modify_Entry_Email');
        var i;
        for(i=0; i<element_blocks.length; i++) {
            element_blocks[i].checked = false;
        }
    }



//FOR FIRST PAGE
    function Test_Upload_Text(){
        var the_text = document.getElementById("attribute_changer_text_to_upload");
        if(the_text.innerHTML == "") {
            document.getElementById("error_printing").innerHTML="Error: No Text Input";
            return;
        }
        else{
            if(the_text.innerHTML[0].length > 1000000000) {
                document.getElementById("error_printing").innerHTML="Error: Text Cannot Exceed 1 Billion Characters";
                return;
            }
            else{
                document.getElementById("text_upload_form").submit();
            }
        }
    }

    function Test_Upload_File(){
        var the_file = document.getElementById("attribute_changer_file_to_upload");
        if(!the_file.files) {
            document.getElementById("error_printing").innerHTML="Error: Not Supported By This Browser";
            return;
        }
        if(!the_file.files[0]) {
            document.getElementById("error_printing").innerHTML="Error: Must Have File Selected";
            return;
        }
        else{
            if(the_file.files[0].size > 1000000000) {
                document.getElementById("error_printing").innerHTML="Error: File Cannot Exceed 1GB";
                return;
            }
            else{
                document.getElementById("file_upload_form").submit();
            }
        }
    }