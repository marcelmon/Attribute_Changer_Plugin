    /*


can do, if class="xx" exists
or, id="hello" is checked



    Require easy to initiate operation using selects
    TYPE 2 + 1

    _Action     _Subject            _Conditional    _Subject      _Predicate
    |check      |All                |               |               |
    |uncheck    |Safe_Value         |unless         |Any            |Exists
                |Current_Value      |if             |Safe_Value     |Not_Exists
                |Other_Value                        |Current_Value  |Checked
                                                    |Other_Value    |Not_Checked
                
    TYPE 3 

    _Action     _Subject            *_Conditional   *_Subject       *_Predicate
    |check      |All                |               |               |
    |uncheck    |Checkbox_Value     |unless         |Any            |Exists
                |Current_Value      |if             |Checkbox_Value |Not_Exists
                |Other_Value                        |Current_Value  |Checked
                                                    |Other_Value    |Not_Checked

    */


//     function Process_Input_Command(the_string) {
//         var commands = the_string.split(' ');
//         if (!commands || commands.length <= 0) {
//             return -1;
//         }
//         var action = _Action(commands[0]);
//         if(!action) {
//             return -1;
//         }
//         if(commands.length == 1) {

//         }
//     }

//     function process_command(attribute_id, attribute_type, action, subject_1, conditional, subject_2, condition) {
//         if(!action || !subject){
//             return -1;
//         }
//         var this_action = _Action(action);
//         if(!this_action){
//             return -1;
//         }

//         var this_subject_1 = _Subject(subject_1);
//         if(!this_subject_1){
//             return -1;
//         }

//         if(!conditional){
//             var className = this_subject_1
//         }

//     }


//     function _Action(action) {
//         switch(action) {

//             case 'Check':
//                 return Check;

//             case 'Uncheck':
//                 return Uncheck;

//             default:
//                 return -1;
//         }
//     }

//     var Check = function (subject) {
//         this.Action = 'Check';
//         this.subject = subject;

//         this.conditional = null;

//         this.setConditional = function(conditional) {
//             this.conditional = conditional;
//         }

//         this.check_subject = function(subject){
//             for(var i=0; i<subject.length; i++) {
//                 if(subject[i].className.indexOf('Checked') < 0){
//                     subject[i].className += ' Checked';
//                     if(subject[i].type == 'checkbox' || subject[i].type == 'radio') {
//                         subject[i].checked = true;
//                     }
//                 }
//             }       
//         }

//         this.execute = function(){
//             if(!this.conditional) {

//                 this.check_subject(this.subject);
//             }
//             else{
//                 var new_subject = new array();
//                 for(var j=0; j<this.subject.length; j++){
//                     if(this.conditional.execute(this.subject[i])){
//                         new_subject.push(this.subject)
//                     }
//                 }
//                 this.check_subject(new_subject);
//             }
//             return true;
//         }
//     }

//     var Uncheck= function(subject) {
//         this.Action = 'Uncheck';
//         this.subject = subject;

//         this.conditional = null;

//         this.setConditional = function(conditional) {
//             this.conditional = conditional;
//         }

//         this.uncheck_subject = function(subject){
//             for(var i=0; i<subject.length; i++) {
                
//                 var class_names = subject[i].className.split(' ');
//                 var index = class_names.indexOf('Checked');
//                 if(index > -1){
//                     class_names.splice(index, 1);
//                     if(subject[i].type == 'checkbox' || subject[i].type == 'radio') {
//                         subject[i].checked = false;
//                     }
//                 }
//                 subject[i].className = class_names.join(' ');
//             }       
//         }

//         this.execute = function(){
//             if(!this.conditional) {

//                 this.uncheck_subject(this.subject);
//             }
//             else{
//                 var new_subject = this.conditional(this.subject);
//                 this.uncheck_subject(new_subject);
//             }
//             return true;
//         }
//     }

//     function _Subject(subject) {
//         switch(subject){

//             case 'All':
//                 return All;

//             case 'Any':
//                 return Any;

//             case 'Safe_Value':
//                 return Safe_Value;

//             case 'Current_Value':
//                 return Current_Value;

//             case 'Other_Value':
//                 return Other_Value;

//             case 'Checkbox_Value':
//                 return Checkbox_Value;

//             default:
//                 return -1;
//         }
//     }


//     var All = function (attribute_id) {
        
//         var attribute_class = 'attribute_'.concat(attribute_id);
//         var attribute_elements = document.getElementsByClassName(attribute_class);
//         return attribute_elements;
//     }


//     var Any= function(attribute_id) {
//         var attribute_class = 'attribute_'.concat(attribute_id);
//         var attribute_elements = document.getElementsByClassName(attribute_class);
//         return attribute_elements;
//     }
 

//     var Safe_Value = function(attribute_id) {
//         return Filter_Value(attribute_id, 'Safe_Value');
//     }


//     var Current_Value = function(attribute_id) {
//         return Filter_Value(attribute_id, 'Current_Value');
//     }

//     var Other_Value= function(attribute_id) {
//         var all = All(attribute_id);

//         var others = new array();
//         //now have 2 arrays to compare to all, all the reset 
//         for(var i=0; i<all.length; i++) {
//             if(all[i].className.indexOf('Current_Value') > -1){
//                 continue;
//             }
//             if(all[i].className.indexOf('Safe_Value') > -1){
//                 continue;
//             }
//             others.push(all[i]);
//         }
//         return others;
//     }


//     var Checkbox_Value= function(attribute_id) {

//         return Filter_Value(attribute_id, 'Checkbox_Value');
//     }
// //Checkbox, other, current safe
//     var Filter_Value = function(attribute_id, class_string){
//         var elements = document.getElementsByClassName('attribute_'.concat(attribute_id));
//         var return_list = new Array();

//         for(var i=0; i < elements.length; i++) {
//             if(elements[i].className.indexOf(class_string) < 0) {
//                 return_list.push(elements[i]);
//             }
//         }
//         return return_list;
//     }


//     var Safe_Value = function(attribute_id) {
//         return Filter_Value(attribute_id, 'Safe_Value');
//     }

//     function _Conditional(conditional) {
//         switch(conditional){

//             case 'Unless':
//                 return Unless;

//             case 'If':
//                 return If;

//             default:
//                 return -1;
//         }
//     }
    

//     var Unless = function (leading_subject, condition, conditioned_subject) {
//         this.Conditional = 'Unless'
//         this.leading_subject = leading_subject;
//         this.Condition = condition;
//         return this;

//         this.execute = function() {
//             if(this.Condition(this.leading_subject)) {
//                 return false;
//             }
//         }
//     }

//     var If = function(leading_subject, condition, conditioned_subject) {
//         this.Conditional = 'Unless'
//         this.leading_subject = leading_subject;
//         this.conditioned_subject = conditioned_subject;
//         //are in the same columns, so attribute id, email key are the same
//         //name would be the same, unless its checkbox

//         this.Condition = condition;
//         return this;

//         this.execute = function() {
//             if(this.Condition(this.conditioned_subject)) {
//                 return true;
//             }
//         }
//     }




//     function _Predicate(predicate) {
//         switch(predicate){

//             case 'Exists':
//                 return Exists;

//             case 'Not_Exists':
//                 return Not_Exists;

//             case 'Checked':
//                 return Checked;

//             case 'Not_Checked':
//                 return Not_Checked;

//             default:
//                 return -1;
//         }
//     }
    
    
//     var Exists = function (leading_subject, subject) {
//         Filter_Conditional(leading_subject, subject);
//     }  

//     var Not_Exists = function(leading_subject, subject) {
//         (Filter_Conditional(leading_subject, subject)) ? false : true;
//     }

//     var Checked = function(leading_subject) {
//         Filter_Conditional(leading_subject, 'Checked');
//     }

//     var Not_Checked = function(leading_subject, subject) {
//         (Filter_Conditional(leading_subject, 'Checked')) ? false : true;
//     }


//     var Filter_Conditional = function(leading_subject, subject) {

//         i=leading_subject.tagName;
//         while(i != 'ul'){
//             leading_subject = leading_subject.parentNode;
//             i = leading_subject.tagName;
//         }
//         child_subject = leading_subject.childNodes;
//         for(var i=0; i<child_subject.length; i++) {
//             if(child_subject[i].className.indexOf(subject) > -1) {
//                 return true;
//             }
//         }
//         return false;
//     }









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



    var check_elements = function (elements) {

        for(var i=0; i<subject.length; i++) {
            if(subject[i].className.indexOf('Checked') < 0){
                subject[i].className += ' Checked';
                if(subject[i].type == 'checkbox' || subject[i].type == 'radio') {
                    subject[i].checked = true;
                }
            }
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


    var Safe_Value = function(attribute_id) {
        return Filter_Value(attribute_id, 'Safe_Value');
    }

    function check_all_Safe_Value_UNLESS(attribute_id, [any_other, other, current, safe], [Exists, Not_Exists, Checked, Not_Checked]) {
        var safe_value_elements = Safe_Value(attribute_id);
        if(!args[1]) {
            var safe_array = Get_Safe_Value_Elements(attribute_id);
            if(safe_array && safe_array.length > 0) {
                check_elements(safe_array);
            }
        }

        else{
            if(!args[2]) {
                return -1;
            }
            else{
                switch(args[1]) {


                    case 'Any_Other':

                        switch(args[2]) {
                            case 'Exists':
                            //CHECK ALL SAFE VALUES UNLES ANY EXIST (WILL ALWAYS CHECK NONE BECAUSE SOME OTHER HAS TO EXIST)
                            //CAN SET THE siblings.length < 1 to < 2 and will become UNLESS ANY-OTHER EXIST
                            //NEED TO KNOWN IF IS IN SAME   <li>-<input>   TREE
                                for(var i=0; i<safe_value_elements.length; i++) {
                                    var siblings = Get_Sibling_All(safe_value_elements[i], 'td');

                                    var to_check = new array();

                                    if(siblings.length < 2) {
                                        to_check.push(safe_value_elements[i]);
                                    }
                                    check_elements(to_check);
                                }
                                break;

                            case 'Not_Exists': 
                            //IF THERE ARE ANY OTHERS EXISTING THEN IS OK
                                for(var i=0; i<safe_value_elements.length; i++) {
                                    var siblings = Get_Sibling_All(safe_value_elements[i], 'td');

                                    var to_check = new array();

                                    if(siblings.length > 1) {
                                        to_check.push(safe_value_elements[i]) 
                                    }
                                    check_elements(to_check);
                                }
                                break;
                        }

                }
            }


            var Get_Checked = function(attribute_id) {

                var return_array = Filter_Class(attribute_id, 'Checked');
                return return_array;
            }

            var Get_Safe_Value = function(attribute_id) {
                var attribute_class = 'attribute_'.concat(attribute_id);
                var attribute_array = document.getElementsByClassName(attribute_class);

                var return_array = Filter_Class(attribute_array, 'Safe_value');
                return return_array;
            }


            var Get_Current_Value = function(attribute_id) {
                var attribute_class = 'attribute_'.concat(attribute_id);
                var attribute_array = document.getElementsByClassName(attribute_class);

                var return_array = Filter_Class(attribute_array, 'Current_Value');
                return return_array;
            }


            var Get_Other = function(attribute_id) {
                var attribute_class = 'attribute_'.concat(attribute_id);
                var attribute_array = document.getElementsByClassName(attribute_class);

                var current_array = Filter_Class(attribute_array, 'Current_Value');
                var safe_array = Filter_Class(attribute_array, 'Safe_Value');

                attribute_array = Remove_Matches(attribute_array, current_array);
                attribute_array = Remove_Matches(attribute_array, safe_array);

                return attribute_array;
            }


            var Filter_Class = function(attribute_id, class_to_match) {
                var attribute_array = document.getElementsByClassName('attribute_'.concat(attribute_id));
                var return_array = new array();

                for(var i=0; i<elements.length; i++) {
                    if(elements[i].className.indexOf(class_to_match)) {
                        return_array.push(elements[i]);
                    }
                }
                return return_array;
            }

            var Get_All = function(attribute_id) {
                var attribute_class = 'attribute_'.concat(attribute_id);
                var attribute_array = document.getElementsByClassName(attribute_class);
                return attribute_array;
            }





            if(args[1] == 'Any') {
                

                var Get_Sibling_Checked = function(leading_subject, top_delimiter) {

                    var top_element = Get_Top_Element(leading_subject, top_delimiter);
                    if(!top_element) {
                        return null;
                    }

                    var checked_array = top_element.getElementsByClassName('Checked');

                    var tag_to_use = 'li';

                    var new_checked_array = new array();

                    for(var i=0; i<checked_array.length; i++) {
                        if(checked_array[i].tagName == 'li') {
                            if(checked_array[i] != leading_subject) {
                                new_checked_array.push(checked_array[i]);
                            }
                        }
                    }

                    return new_checked_array;
                }

                var Get_Checkbox_Sibling_Not_Current = function(leading_subject, top_delimiter) {
                    
                    var attribute_class = Get_Attribute_Class(leading_subject);
                    if(!attribute_class) {
                        return -1;
                    }

                    var top_element = Get_Top_Element(leading_subject, top_delimiter);
                    if(!top_element) {
                        return null;
                    }

                    var current_array = top_element.getElementsByClassName('Current_Value');

                    var all_array = top_element.getElementsByClassName(attribute_class);
                    var tag_to_use = 'li';


                    var new_checked_not_current = new array();

                    for(var i=0; i<current_array.length; i++) {
                        if(current_array[i].tagName == 'li'){
                            if(current_array[i].className.indexOf('Current_Value') < 0){
                                if(current_array[i] != leading_subject) {
                                    new_checked_not_current.push(current_array[i]); 
                                }
                            }
                        }
                    }

                    return new_checked_not_current;
                }

                var Get_Sibling_Matching = function(class_to_match) {
                    switch(class_to_match):
                        case 'Current_Value':


                        case 'All':


                        case 'Checked':

                        default:
                            return null;
                }

                var Get_Sibling_Checked = function(leading_subject, top_delimiter) {
                    var return_array = Find_Sibling_Match(leading_subject, top_delimiter, 'Checked');
                    return return_array;
                }


                var Get_Sibling_All = function(leading_subject, top_delimiter) {

                    var attribute_class = Get_Attribute_Class(leading_subject);
                    if(!attribute_class) {
                        return -1;
                    }

                    var return_array = Find_Sibling_Match(leading_subject, top_delimiter, attribute_class);
                    return return_array;
                }

                var Get_Sibling_Current_Value = function(leading_subject, top_delimiter) {

                    var current_array = Find_Sibling_Match(leading_subject, top_delimiter, 'Current_Value');
                    return current_array;
                }


                var Get_Sibling_Safe_Value = function(leading_subject, top_delimiter) {

                    var safe_array = Find_Sibling_Match(leading_subject, top_delimiter, 'Safe_Value');
                    return safe_array;
                }

                var Get_Sibling_Not_Current_Value = function(leading_subject, top_delimiter) {

                    var all_array = Get_Sibling_All(leading_subject, top_delimiter);
                    var current_array = Get_Sibling_Current_Value(leading_subject, top_delimiter);
                }

                var Get_Sibling_Not_Other = function(leading_subject, top_delimiter) {

                    var return_array = Find_Sibling_Match(leading_subject, top_delimiter, 'Current_Value');
                    var safe_array = Find_Sibling_Match(leading_subject, top_delimiter, 'Safe_Value');

                    for(var i=0; i<safe_array.length; i++) {
                        if(safe_array[i].)
                    }
                }


                var Get_Sibling_Not_Current_Not_Safe = function(leading_subject, top_delimiter) {

                    var current_array = Find_Sibling_Match(leading_subject, top_delimiter, 'Current_Value');
                    var safe_array = Find_Sibling_Match(leading_subject, top_delimiter, 'Safe_Value');

                    var all_array = Get_Sibling_All(leading_subject, top_delimiter);

                    all_array = Remove_Matches(all_array, safe_array);
                    all_array = Remove_Matches(all_array, current_array);

                    return all_array;
                }




                var Find_Sibling_Match = function(leading_subject, top_delimiter, class_to_match) {


                    var top_element = Get_Top_Element(leading_subject, top_delimiter);
                    if(!top_element) {
                        return null;
                    }

                    var match_array = top_element.getElementsByClassName(class_to_match);

                    var return_array = new array();

                    for(var i=0; i<match_array.length; i++) {
                        if(match_array[i].tagName == 'li'){

                            if(match_array[i] != leading_subject) {
                                return_array.push(match_array[i]); 
                            }
                        }
                    }
                    return return_array;
                }







                var Remove_Matches = function(to_remove_from, to_match) {
                    //iterate through to_remove_from, see if theres a match in to_match
                        //if theres no match then add to filtered list 
                    if(!to_remove_from || !to_match) {
                        return -1;
                    }
                    if(!isArray(to_remove_from) || !isArray(to_match)){
                        return -1;
                    }
                    var return_array = new array();
                    for(var i=0; i<to_remove_from.length; i++) {

                        if(!to_match.indexOf(to_remove_from[i])) {
                            return_array.push(to_remove_from[i]);
                        }
                    }
                    return return_array;
                }
                

                var Get_Attribute_Class = function(leading_subject) {
                    if(!leading_subject || typeof leading_subject != 'string') {
                        return -1;
                    }
                    var attribute_class = null;
                    var classes = leading_subject.className.split(' ');

                    for(var i=0; i<classes.length; i++) {
                        if(classes[i].indexOf('attribute_')){
                            attribute_class = classes[i];
                            break;
                        }
                    }
                    return attribute_class;
                }


                var Get_Top_Element = function(leading_subject, top_delimiter) {
                    if(!leading_subject || typeof leading_subject != 'string') {
                        return -1;
                    }
                    if(!top_delimiter || typeof top_delimiter != 'string') {
                        return -1;
                    }
                    var tag = leading_subject.tagName;
                    var top_element = null;

                    while(tagName != top_delimiter) {
                        if(top_element == document.body) {
                            return null;
                        }
                        top_element = leading_subject.parentNode;
                    }
                    return top_element;
                }


                // function Get_Matching_Child_Nodes(classesToMatch, top_element) {
                //     var return_array = new Array();

                //     if(top_element.childNodes.length == 0) {
                //         return return_array;
                //     }

                //     for(var i=0; i<top_element.childNodes.length; i++) {
                //         new_array = Get_Matching_Child_Nodes(classesToMatch, top_element.childNodes[i]);

                //         for(var j=0; j<new_array.length; j++) {
                //             return_array.push(new_array[j]);
                //         }
                //         var is_good = true;

                //         for(var j=0; j<classesToMatch; j++) {
                //             if(top_element.childNodes[i].className.indexOf(classesToMatch[j]) < 0) {
                //                 is_good = false;
                //                 //not going to add this child if not good
                //             }
                //         }
                //         if(is_good == true) {
                //             return_array.push(top_element.childNodes[i]);
                //         }

                //     }
                //     return return_array;

                // }



                if(args[2] == 'Exists'){

                }
                if(args[2] == 'Not_Exists'){

                }
                if(args[2] == 'Checked'){

                }
                if(args[2] == 'Not_Checked'){

                }
            }
            if(args[1] == 'Other') {
                if(args[2] == 'Exists'){

                }
                if(args[2] == 'Not_Exists'){

                }
                if(args[2] == 'Checked'){

                }
                if(args[2] == 'Not_Checked'){

                }
            }
            if(args[1] == 'Current_Value') {
                
                if(args[2] == 'Exists'){

                }
                if(args[2] == 'Not_Exists'){

                }
                if(args[2] == 'Checked'){

                }
                if(args[2] == 'Not_Checked'){

                }
            }
            if(args[1] == 'Safe_Value') {
                
                if(args[2] == 'Exists'){

                }
                if(args[2] == 'Not_Exists'){

                }
                if(args[2] == 'Checked'){

                }
                if(args[2] == 'Not_Checked'){

                }
            }

            for(var i=0; i>safe_value_elements.length; i++) {

            }
        }
    }

    function uncheck_all_Safe_Value_OR([any, other, current], [Exists, Not_Exists, Checked, Not_Checked]) {

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