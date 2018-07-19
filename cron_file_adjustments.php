<?php
require_once("include/config.php");
require_once(DIR_FS."islogin.php");

$instance =  new payroll();
$return = $instance->select_adjustments_master();
foreach($return as $key_adjust=>$val_adjust)
{
    if(isset($val_adjust['recurring_type_id']) && $val_adjust['recurring_type_id'] == 2)
    {
        $today = date('Y-m-d');
        $first_day_of_month = date('Y-m-01');
        $mid_day_of_month = date('Y-m-16');
        //$half_month = date('d-m-Y', (strtotime($first_day)+ (86400 * 15)));
        
        if($today == $first_day_of_month || $today == $mid_day_of_month)
        {
            $is_expired=0;
            $total_usage=$val_adjust['total_usage']+1;
            $renew_adjustments = $instance->renew_mid_month_adjustments($is_expired,'','',$total_usage);
        }
    }
    else if(isset($val_adjust['recurring_type_id']) && $val_adjust['recurring_type_id'] == 3)
    {
        $today = date('Y-m-d');
        $end_of_month = date('Y-m-01');
        
        if($today == $end_of_month)
        {
            $is_expired=0;
            $total_usage=$val_adjust['total_usage']+1;
            $renew_adjustments = $instance->renew_end_month_adjustments($is_expired,$val_adjust['id'],'',$total_usage);
        }
    }
    else if(isset($val_adjust['recurring_type_id']) && $val_adjust['recurring_type_id'] == 4)
    {
        $today = date('Y-m-d');
        $semi_annually_mid_month_first = date('Y-06-16');
        $semi_annually_mid_month_last = date('Y-12-16');
        
        if($today == $semi_annually_mid_month_first || $today == $semi_annually_mid_month_last)
        {
            $is_expired=0;
            $total_usage=$val_adjust['total_usage']+1;
            $renew_adjustments = $instance->renew_semi_mid_month_adjustments($is_expired,'','',$total_usage);
        }
    }
    else if(isset($val_adjust['recurring_type_id']) && $val_adjust['recurring_type_id'] == 5)
    {
        $today = date('Y-m-d');
        $semi_annually_end_month_first = date('Y-01-01');
        $semi_annually_end_month_last = date('Y-07-01');
        
        if($today == $semi_annually_end_month_first || $today == $semi_annually_end_month_last)
        {
            $is_expired=0;
            $total_usage=$val_adjust['total_usage']+1;
            $renew_adjustments = $instance->renew_semi_end_month_adjustments($is_expired,'','',$total_usage);
        }
    }
    else if(isset($val_adjust['recurring_type_id']) && $val_adjust['recurring_type_id'] == 6)
    {
        $today = date('Y-m-d');
        $quarterly_mid_month_first = date('Y-03-16');
        $quarterly_mid_month_second = date('Y-06-16');
        $quarterly_mid_month_third = date('Y-09-16');
        $quarterly_mid_month_fourth = date('Y-12-16');
        
        if($today == $quarterly_mid_month_first || $today == $quarterly_mid_month_second || $today == $quarterly_mid_month_third || $today == $quarterly_mid_month_fourth )
        {
            $is_expired=0;
            $total_usage=$val_adjust['total_usage']+1;
            $renew_adjustments = $instance->renew_qua_mid_month_adjustments($is_expired,'','',$total_usage);
        }
    }
    else if(isset($val_adjust['recurring_type_id']) && $val_adjust['recurring_type_id'] == 7)
    {
        $today = date('Y-m-d');
        $quarterly_end_month_first = date('Y-04-01');
        $quarterly_end_month_second = date('Y-07-01');
        $quarterly_end_month_third = date('Y-10-01');
        $quarterly_end_month_fourth = date('Y-01-01');
        
        if($today == $quarterly_end_month_first || $today == $quarterly_end_month_second || $today == $quarterly_end_month_third || $today == $quarterly_end_month_fourth )
        {
            $is_expired=0;
            $total_usage=$val_adjust['total_usage']+1;
            $renew_adjustments = $instance->renew_qua_end_month_adjustments($is_expired,'','',$total_usage);
        }
    }
}
?>