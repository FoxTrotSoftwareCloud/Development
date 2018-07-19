<?php
 require_once("islogin.php"); 
 $que= mysql_query("CREATE DEFINER=`CloudFox_jjixgbv9my493495`@`localhost` TRIGGER `updateBranch` AFTER UPDATE ON `ft_branch_master` FOR EACH ROW BEGIN
IF NEW.name <> OLD.name THEN
INSERT INTO ft_branch_history (branch_id,field,old_value,new_value,status,is_delete,created_by,created_time,created_ip,modified_by,modified_time)
VALUES(NEW.id,'name',OLD.name,NEW.name,NEW.status,NEW.is_delete,NEW.created_by,NEW.created_time,NEW.created_ip,NEW.modified_by,NEW.modified_time);
END IF;
IF NEW.broker <> OLD.broker THEN
INSERT INTO ft_branch_history (branch_id,field,old_value,new_value,status,is_delete,created_by,created_time,created_ip,modified_by,modified_time)
VALUES(NEW.id,'broker',OLD.broker,NEW.broker,NEW.status,NEW.is_delete,NEW.created_by,NEW.created_time,NEW.created_ip,NEW.modified_by,NEW.modified_time);
END IF;
IF NEW.b_status <> OLD.b_status THEN
INSERT INTO ft_branch_history (branch_id,field,old_value,new_value,status,is_delete,created_by,created_time,created_ip,modified_by,modified_time)
VALUES(NEW.id,'b_status',OLD.b_status,NEW.b_status,NEW.status,NEW.is_delete,NEW.created_by,NEW.created_time,NEW.created_ip,NEW.modified_by,NEW.modified_time);
END IF;
IF NEW.contact <> OLD.contact THEN
INSERT INTO ft_branch_history (branch_id,field,old_value,new_value,status,is_delete,created_by,created_time,created_ip,modified_by,modified_time)
VALUES(NEW.id,'contact',OLD.contact,NEW.contact,NEW.status,NEW.is_delete,NEW.created_by,NEW.created_time,NEW.created_ip,NEW.modified_by,NEW.modified_time);
END IF;
IF NEW.osj <> OLD.osj THEN
INSERT INTO ft_branch_history (branch_id,field,old_value,new_value,status,is_delete,created_by,created_time,created_ip,modified_by,modified_time)
VALUES(NEW.id,'osj',OLD.osj,NEW.osj,NEW.status,NEW.is_delete,NEW.created_by,NEW.created_time,NEW.created_ip,NEW.modified_by,NEW.modified_time);
END IF;
IF NEW.non_registered <> OLD.non_registered THEN
INSERT INTO ft_branch_history (branch_id,field,old_value,new_value,status,is_delete,created_by,created_time,created_ip,modified_by,modified_time)
VALUES(NEW.id,'non_registered',OLD.non_registered,NEW.non_registered,NEW.status,NEW.is_delete,NEW.created_by,NEW.created_time,NEW.created_ip,NEW.modified_by,NEW.modified_time);
END IF;
IF NEW.finra_fee <> OLD.finra_fee THEN
INSERT INTO ft_branch_history (branch_id,field,old_value,new_value,status,is_delete,created_by,created_time,created_ip,modified_by,modified_time)
VALUES(NEW.id,'finra_fee',OLD.finra_fee,NEW.finra_fee,NEW.status,NEW.is_delete,NEW.created_by,NEW.created_time,NEW.created_ip,NEW.modified_by,NEW.modified_time);
END IF;
IF NEW.business_address1 <> OLD.business_address1 THEN
INSERT INTO ft_branch_history (branch_id,field,old_value,new_value,status,is_delete,created_by,created_time,created_ip,modified_by,modified_time)
VALUES(NEW.id,'business_address1',OLD.business_address1,NEW.business_address1,NEW.status,NEW.is_delete,NEW.created_by,NEW.created_time,NEW.created_ip,NEW.modified_by,NEW.modified_time);
END IF;
IF NEW.business_address2 <> OLD.business_address2 THEN
INSERT INTO ft_branch_history (branch_id,field,old_value,new_value,status,is_delete,created_by,created_time,created_ip,modified_by,modified_time)
VALUES(NEW.id,'business_address2',OLD.business_address2,NEW.business_address2,NEW.status,NEW.is_delete,NEW.created_by,NEW.created_time,NEW.created_ip,NEW.modified_by,NEW.modified_time);
END IF;
IF NEW.business_city <> OLD.business_city THEN
INSERT INTO ft_branch_history (branch_id,field,old_value,new_value,status,is_delete,created_by,created_time,created_ip,modified_by,modified_time)
VALUES(NEW.id,'business_city',OLD.business_city,NEW.business_city,NEW.status,NEW.is_delete,NEW.created_by,NEW.created_time,NEW.created_ip,NEW.modified_by,NEW.modified_time);
END IF;
IF NEW.business_state <> OLD.business_state THEN
INSERT INTO ft_branch_history (branch_id,field,old_value,new_value,status,is_delete,created_by,created_time,created_ip,modified_by,modified_time)
VALUES(NEW.id,'business_state',OLD.business_state,NEW.business_state,NEW.status,NEW.is_delete,NEW.created_by,NEW.created_time,NEW.created_ip,NEW.modified_by,NEW.modified_time);
END IF;
IF NEW.business_zipcode <> OLD.business_zipcode THEN
INSERT INTO ft_branch_history (branch_id,field,old_value,new_value,status,is_delete,created_by,created_time,created_ip,modified_by,modified_time)
VALUES(NEW.id,'business_zipcode',OLD.business_zipcode,NEW.business_zipcode,NEW.status,NEW.is_delete,NEW.created_by,NEW.created_time,NEW.created_ip,NEW.modified_by,NEW.modified_time);
END IF;
IF NEW.mailing_address1 <> OLD.mailing_address1 THEN
INSERT INTO ft_branch_history (branch_id,field,old_value,new_value,status,is_delete,created_by,created_time,created_ip,modified_by,modified_time)
VALUES(NEW.id,'mailing_address1',OLD.mailing_address1,NEW.mailing_address1,NEW.status,NEW.is_delete,NEW.created_by,NEW.created_time,NEW.created_ip,NEW.modified_by,NEW.modified_time);
END IF;
IF NEW.mailing_address2 <> OLD.mailing_address2 THEN
INSERT INTO ft_branch_history (branch_id,field,old_value,new_value,status,is_delete,created_by,created_time,created_ip,modified_by,modified_time)
VALUES(NEW.id,'mailing_address2',OLD.mailing_address2,NEW.mailing_address2,NEW.status,NEW.is_delete,NEW.created_by,NEW.created_time,NEW.created_ip,NEW.modified_by,NEW.modified_time);
END IF;
IF NEW.mailing_city <> OLD.mailing_city THEN
INSERT INTO ft_branch_history (branch_id,field,old_value,new_value,status,is_delete,created_by,created_time,created_ip,modified_by,modified_time)
VALUES(NEW.id,'mailing_city',OLD.mailing_city,NEW.mailing_city,NEW.status,NEW.is_delete,NEW.created_by,NEW.created_time,NEW.created_ip,NEW.modified_by,NEW.modified_time);
END IF;

IF NEW.mailing_state <> OLD.mailing_state THEN
INSERT INTO ft_branch_history (branch_id,field,old_value,new_value,status,is_delete,created_by,created_time,created_ip,modified_by,modified_time)
VALUES(NEW.id,'mailing_state',OLD.mailing_state,NEW.mailing_state,NEW.status,NEW.is_delete,NEW.created_by,NEW.created_time,NEW.created_ip,NEW.modified_by,NEW.modified_time);
END IF;
IF NEW.mailing_zipcode <> OLD.mailing_zipcode THEN
INSERT INTO ft_branch_history (branch_id,field,old_value,new_value,status,is_delete,created_by,created_time,created_ip,modified_by,modified_time)
VALUES(NEW.id,'mailing_zipcode',OLD.mailing_zipcode,NEW.mailing_zipcode,NEW.status,NEW.is_delete,NEW.created_by,NEW.created_time,NEW.created_ip,NEW.modified_by,NEW.modified_time);
END IF;
IF NEW.email <> OLD.email THEN
INSERT INTO ft_branch_history (branch_id,field,old_value,new_value,status,is_delete,created_by,created_time,created_ip,modified_by,modified_time)
VALUES(NEW.id,'email',OLD.email,NEW.email,NEW.status,NEW.is_delete,NEW.created_by,NEW.created_time,NEW.created_ip,NEW.modified_by,NEW.modified_time);
END IF;
IF NEW.website <> OLD.website THEN
INSERT INTO ft_branch_history (branch_id,field,old_value,new_value,status,is_delete,created_by,created_time,created_ip,modified_by,modified_time)
VALUES(NEW.id,'website',OLD.website,NEW.website,NEW.status,NEW.is_delete,NEW.created_by,NEW.created_time,NEW.created_ip,NEW.modified_by,NEW.modified_time);
END IF;
IF NEW.phone <> OLD.phone THEN
INSERT INTO ft_branch_history (branch_id,field,old_value,new_value,status,is_delete,created_by,created_time,created_ip,modified_by,modified_time)
VALUES(NEW.id,'phone',OLD.phone,NEW.phone,NEW.status,NEW.is_delete,NEW.created_by,NEW.created_time,NEW.created_ip,NEW.modified_by,NEW.modified_time);
END IF;
IF NEW.facsimile <> OLD.facsimile THEN
INSERT INTO ft_branch_history (branch_id,field,old_value,new_value,status,is_delete,created_by,created_time,created_ip,modified_by,modified_time)
VALUES(NEW.id,'facsimile',OLD.facsimile,NEW.facsimile,NEW.status,NEW.is_delete,NEW.created_by,NEW.created_time,NEW.created_ip,NEW.modified_by,NEW.modified_time);
END IF;
IF NEW.date_established <> OLD.date_established THEN
INSERT INTO ft_branch_history (branch_id,field,old_value,new_value,status,is_delete,created_by,created_time,created_ip,modified_by,modified_time)
VALUES(NEW.id,'date_established',OLD.date_established,NEW.date_established,NEW.status,NEW.is_delete,NEW.created_by,NEW.created_time,NEW.created_ip,NEW.modified_by,NEW.modified_time);
END IF;
IF NEW.date_terminated <> OLD.date_terminated THEN
INSERT INTO ft_branch_history (branch_id,field,old_value,new_value,status,is_delete,created_by,created_time,created_ip,modified_by,modified_time)
VALUES(NEW.id,'date_terminated',OLD.date_terminated,NEW.date_terminated,NEW.status,NEW.is_delete,NEW.created_by,NEW.created_time,NEW.created_ip,NEW.modified_by,NEW.modified_time);
END IF;
IF NEW.finra_start_date <> OLD.finra_start_date THEN
INSERT INTO ft_branch_history (branch_id,field,old_value,new_value,status,is_delete,created_by,created_time,created_ip,modified_by,modified_time)
VALUES(NEW.id,'finra_start_date',OLD.finra_start_date,NEW.finra_start_date,NEW.status,NEW.is_delete,NEW.created_by,NEW.created_time,NEW.created_ip,NEW.modified_by,NEW.modified_time);
END IF;
IF NEW.finra_end_date <> OLD.finra_end_date THEN
INSERT INTO ft_branch_history (branch_id,field,old_value,new_value,status,is_delete,created_by,created_time,created_ip,modified_by,modified_time)
VALUES(NEW.id,'finra_end_date',OLD.finra_end_date,NEW.finra_end_date,NEW.status,NEW.is_delete,NEW.created_by,NEW.created_time,NEW.created_ip,NEW.modified_by,NEW.modified_time);
END") or die(mysql_error());

if($que)
{
    echo "done";
}

?>