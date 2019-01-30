<?php
/**
 * This is a view Partial for Generating a Form
 * To create a form just load this view from anywhere
 * You have to pass an Array of FormFields
 * which is declared in Models and the view will take care of the rest.
 *
 * Also Remember that the Opening and Closing form tag is not
 * generated from this page so you will have to manually include it before and after loading this view.
 *
 */



foreach ($formField  as $field) :                             //  Loop through Each Field in the Array
    
    $extra = [];

    if(isset($field['validation'])){                          // handle Validation for that Particular Field
        $validationRules = explode('|',$field['validation']); unset($field['validation']);
        $jsValidation = [];

        array_walk( $validationRules, function($rule) use (&$jsValidation){

        $validationRuleMap = [                               // An Array to match CI Validation Rules with Jquery Validation Rules
                'required'      => 'required',
                'valid_email'   => 'email',
                'min_length'    => 'minlength',
                'max_length'    => 'maxlength',
                'numeric'       => 'number',
                'trim'          => 'trim',
                'matches'       => 'equalTo',
                'valid_url'     => 'url'
            ];

            preg_match('/\[(.*?)\]/',$rule, $matches);
            if(isset($matches[0])){
                $rule = str_replace($matches[0],'', $rule);
                $matches[1] = ($rule == 'matches') ? "#$matches[1]" : $matches[1];
            }
            $jsValidation['data-'.$validationRuleMap[$rule]] = isset($matches[1]) ? $matches[1] : 'true';
        }) ;

        foreach ($jsValidation as $rule => $value){
            $extra[$rule] = $value;
        }
    }

    $extra['id'] = str_replace(['[',']'],['-',''],$field['name']) ;

    echo '<div class="form-group">';
        if(!in_array($field['type'], ['checkbox', 'radio']) ) :
            $extra['class'] = "form-control";
                echo '<label class="col-md-4 control-label">',$field['label'],'</label>'; unset($field['label']);
            if($field['type'] == 'password') :
                echo '<div class="col-md-8">',form_password($field, isset($field['value']) ? $field['value'] : '', $extra),'<span class="help-block"></span></div>';
            elseif($field['type'] == 'select'):
                echo '<div class="col-md-8">',form_dropdown($field,$field['options'],isset($field['value']) ? $field['value'] : '', $extra),'<span class="help-block"></span></div>';
            elseif($field['type'] == 'textarea'):
                echo '<div class="col-md-8">',form_textarea($field, isset($field['value']) ? $field['value'] : '', $extra),'<span class="help-block"></span></div>';
            else :
                echo '<div class="col-md-8">',form_input($field, isset($field['value']) ? $field['value'] : '', $extra),'<span class="help-block"></span></div>';
            endif;
        else :  $label = $field['label']; unset($field['label']);
            echo '<div class="checkbox col-md-12"><label>', form_checkbox($field, isset($field['value']) ? $field['value'] : '', FALSE, $extra ), $label, '</label></div>';
        endif;
    echo '</div>';
endforeach;
