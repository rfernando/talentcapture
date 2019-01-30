<?php
/**
 * This file is to Extend the CI Form helper
 */


if(!function_exists('generate_form_fields')){
    function generate_form_fields($formField, $labelWidth = 0){
        $html = '';

        foreach ($formField  as $field) :                             //  Loop through Each Field in the Array

            $extra = [];

           


            if(isset($field['validation'])){                          // handle Validation for that Particular Field
                $validationRules = explode('|',$field['validation']); unset($field['validation']);
                $jsValidation = [];

                array_walk( $validationRules, function($rule) use (&$jsValidation){

                    $validationRuleMap = [                              // An Array to match CI Validation Rules with Jquery Validation Rules
                        'required'      => 'required',
                        'valid_email'   => 'email',
                        'min_length'    => 'minlength',
                        'max_length'    => 'maxlength',
                        'numeric'       => 'number',
                        'trim'          => 'trim',
                        'matches'       => 'equalTo',
                        'valid_url'     => 'url',
                        'is_unique'     => 'remote'
                    ];

                    preg_match('/\[(.*?)\]/',$rule, $matches);
                    if(isset($matches[0])){
                        $rule = str_replace($matches[0],'', $rule);
                        if($rule == 'matches'){
                            $matches[1] = "#$matches[1]";
                        }else if($rule == 'is_unique'){
                            $uniqueVals = explode('.', $matches[1]);
                            $matches[1] = base_url('validate_unique');
                        }
                    }
                    $fieldRule = isset($validationRuleMap[$rule]) ? $validationRuleMap[$rule] : $rule;
                    $jsValidation['data-'.$fieldRule] = isset($matches[1]) ? $matches[1] : 'true';
                }) ;

                foreach ($jsValidation as $rule => $value){
                    $extra[$rule] = $value;
                }
            }

            $extra['id'] = rtrim(str_replace(['[',']'],['-',''],$field['name']),'-') ;
            $fieldWidth = 12 - $labelWidth;
            $html .= ($labelWidth) ? '<div class="form-group">' : '<div class="form-group has-feedback">';

            if($field['name'] == 'notify_agencies' && $field['value'] == '1'):
                $agency_notification = Site_messages::where('type','=','agency_notification')->first();

                $html .= "<label for='{$extra['id']}' class='col-sm-$labelWidth control-label'>Agency Notification</label>";
                $html .= '<a rel="popover" data-toggle="collapse" data-html="true" data-placement="bottom" data-parent="#accordion" data-content_id="popover_agency_notf"><i class="glyphicon glyphicon-question-sign" ></i></a>';
                $html .= '<div id="popover_agency_notf" class="hide" style="width:90%">';
                $html .= "{$agency_notification->msg}</div>";
            endif;

            if($field['name'] == 'jobattachment'):
                $agency_notification = Site_messages::where('type','=','jobattachment')->first();

                $html .= '    <a rel="popover" data-toggle="collapse" data-html="true" data-placement="bottom" data-parent="#accordion" data-content_id="popover_agency_notf2"><span style="color:white;">_</span><i class="glyphicon glyphicon-question-sign" ></i></a>';
                $html .= '<div id="popover_agency_notf2" class="hide" style="width:90%">';
                $html .= "{$agency_notification->msg}</div>";
            endif;

            /*   For RP-805 addidng Question mark sign to interview notes*/
            if ($field['name'] == 'candidates[notes]'):
                $interview_notes = Site_messages::where('type','=','interview_notes')->first();
                $html .= '<a rel="popover" data-toggle="collapse" data-html="true" data-placement="bottom" data-parent="#accordion" data-content_id="popover_agency_notf3"><i class="glyphicon glyphicon-question-sign" ></i></a>';
                $html .= '<div id="popover_agency_notf3" class="hide" style="width:90%">';
                $html .= "{$interview_notes->msg}</div>";
            endif;   

            if(!in_array($field['type'], ['checkbox', 'radio']) ) :
                if($field['name'] == 'candidates[employment_history]'):
                    $html .= ($labelWidth) ? "<label for='{$extra['id']}' class='col-sm-$labelWidth control-label'>Candidate Summary</label>" : '';
                   /* $html .= ($labelWidth) ? "<label for='{$extra['id']}' class='col-sm-$labelWidth control-label'>Job Description sachin</label>" : '';*/
                else :

                    if(isset($field['for_diff-label'])){
                        if($field['for_diff-label']=='social-icon-linked-in')
                        {
                            $html .= ($labelWidth) ? "<label for='{$extra['id']}' id='label-{$extra['id']}' class='col-sm-$labelWidth control-label social-icon-color'><i class='fa fa-linkedin-square fa-2x margin-r-10'></i></label>" : '';
                        } else if($field['for_diff-label']=='social-icon-facebook')
                        {
                             $html .= ($labelWidth) ? "<label for='{$extra['id']}' id='label-{$extra['id']}' class='col-sm-$labelWidth control-label social-icon-color'><i class='fa fa-facebook-square fa-2x margin-r-10'></i></label>" : '';
                        }  else if($field['for_diff-label']=='social-icon-twitter')
                        {
                             $html .= ($labelWidth) ? "<label for='{$extra['id']}' id='label-{$extra['id']}' class='col-sm-$labelWidth control-label social-icon-color'><i class='fa fa-twitter-square fa-2x margin-r-10'></i></label>" : '';
                        } else if($field['for_diff-label']=='Interview Notes'){
                            $html .= ($labelWidth) ? "<label for='{$extra['id']}' id='label-{$extra['id']}' class='col-sm-$labelWidth control-label'>{$field['for_diff-label']}</label>" : '';
                        }else if($field['for_diff-label']=='Job Description'){
                            $html .= ($labelWidth) ? "<label for='{$extra['id']}' id='label-{$extra['id']}' class='col-sm-$labelWidth control-label'>{$field['for_diff-label']}</label>" : '';
                        }else if($field['for_diff-label']=='Interview Questions'){     /*Changing the Candidate Screening Questions to Interview Question*/
                             $html .= ($labelWidth) ? "<label for='{$extra['id']}' id='label-{$extra['id']}' class='col-sm-$labelWidth control-label'>{$field['for_diff-label']}</label>" : '';
                        }
                        else if($field['for_diff-label']=='General Notes'){
                             $html .= ($labelWidth) ? "<label for='{$extra['id']}' id='label-{$extra['id']}' class='col-sm-$labelWidth control-label'>{$field['for_diff-label']}</label>" : '';
                        }else{
                            $html .= ($labelWidth) ? "<label for='{$extra['id']}' id='label-{$extra['id']}' class='col-sm-$labelWidth control-label social-icon-color'>{$field['for_diff-label']}</label>" : '';
                        }
                    } else{

                    $html .= ($labelWidth) ? "<label for='{$extra['id']}' id='label-{$extra['id']}' class='col-sm-$labelWidth control-label'>{$field['placeholder']}</label>" : '';
                    }

                endif;
        
                $html .= ($labelWidth) ? "<div class='col-sm-$fieldWidth'>" : '';
                $extra['class'] = (isset($field['class'])) ? $field['class']." form-control" : "form-control";
                if($field['type'] == 'password'):
                    $html .= form_password($field, isset($field['value']) ? $field['value'] : '', $extra);
                elseif($field['type'] == 'select'):
                    $html .= form_dropdown($field,$field['options'],isset($field['value']) ? $field['value'] : '', $extra);
                elseif($field['type'] == 'textarea'):
                    $html .= form_textarea($field, isset($field['value']) ? $field['value'] : '', $extra);
                else :
                    $html .= form_input($field, isset($field['value']) ? $field['value'] : '', $extra);
                endif;
                $html .= ($labelWidth) ? "</div>" : '';
            else :  $label = $field['label']; unset($field['label']);
                        $html .= "<div class='checkbox col-sm-offset-$labelWidth col-md-$fieldWidth'><label>". form_checkbox($field, isset($field['value']) ? $field['value'] : '', FALSE, $extra ). $label. '  </label>';
                    if ($field['name'] == 'client_name_confidential' && $field['value'] == '1') :
                        $agency_notification = Site_messages::where('type','=','client_name_confidential')->first();
                        $html .= '<a rel="popover" data-toggle="collapse" data-html="true" data-placement="bottom" data-parent="#accordion" data-content_id="popover_agency_notf1"><span style="color:white;">_</span><i class="glyphicon glyphicon-question-sign"></i></a>';
                        $html .= '<div id="popover_agency_notf1" class="hide" style="width:90%;">';
                        $html .= "{$agency_notification->msg}</div></div>";
                    else :
                        $html .= '</div>';
                        endif; 
            endif;
            $html .= '</div>';
        endforeach;
        return $html;
    }
}
/*
if(!function_exists('form_dropdown_tree')){
    function form_dropdown_tree($options, $newArray = array()){
        foreach ($options as $opt){
            $newArray[$options['id']] = $options['title'];
            if(isset($opt['children'])){
                $options = form_dropdown_tree($opt,$newArray);
            }
        }
        return $options;
    }
}*/