/*Heshan:2018-01-18 for RP-627*/

INSERT INTO `site_messages` (`id`, `msg`, `type`, `updated_at`) VALUES ('16', '<strong>Thank you!</strong> The details will be verified and an invoice will be submitted. Your representative employer will contact you within the next 48 hours.', 'candidate_payment_request_emp', '2018-01-18 00:00:00'), ('17', '<strong>Thank you!</strong> The details will be verified and an invoice will be submitted. Your representative agency will contact you within the next 48 hours.', 'candidate_payment_request_agn', '2018-01-18 00:00:00');


/*Lakmal:2018-01-18 for RP-632*/
INSERT INTO `email_templates` (`id`, `template_name`, `template_subject`, `template_body`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(26, 'AgencyAddedByAdmin', 'Agency added for TalentGram by Admin ', '<p>$AGENCYNAME$ has been added to a TalentGram for $JOBNAME$.</p>\r\n\r\n\r\n\r\n<p>Log into your account here (@SITEURL@).</p>\r\n', '1', '2018-01-19 12:00:00', '2018-01-19 12:00:00', NULL);


/*Week 2 changes*/
/*Heshan:2018-01-26 for RP-645*/

CREATE TABLE `job_documents` (
  `id` int(11) NOT NULL,
  `title` varchar(45) NOT NULL,
  `file_path` varchar(60) NOT NULL,
  `job_id` int(11) NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
ALTER TABLE `job_documents` ADD PRIMARY KEY (`id`);
ALTER TABLE `job_documents` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;


/*Heshan:2018-01-26 for RP-646*/

UPDATE `email_templates` SET `template_body` = '<p>$COMPANYNAME$&nbsp;has posted a new TalentGram that is an industry match for your agency.</p>

<p>@SITEURL@Log into!@&nbsp;your TalentCapture account to review the details and respond. If you express interest in the TalentGram, the hiring manager will have the option to review, and verify or decline you as an approved&nbsp;agency.&nbsp; If your agency is approved, you&nbsp;have the ability to submit candidates for consideration through the TalentCapture marketplace. &nbsp;</p> 

<p>Here are the&nbsp;TalentGram Details,</p>

<p><strong>Job Title: </strong>$J_TITLE$</p>

<p><strong>Industry: </strong>$J_INDUSTRY$</p>

<p><strong>Profession: </strong>$J_PROFESSION$</p>

<p><strong>Primary Skills Required: </strong>$J_SKILLS$</p>

<p><strong>Location: </strong>$J_LOCATION$</p>

<p><strong>Resource Type: </strong>$J_RESOURCE$</p>

<p><strong>Salary or Hourly rate: </strong>$J_SALARY$</p>

<p><strong>Placement Fee<strong>: </strong></strong>$J_FEE$</p>

<p><strong>Notes: </strong>$J_NOTES$</p>

<p>The TalentGram will be closed for agency consideration after five agencies have been approved. &nbsp;</p>', `deleted_at` = NULL WHERE `email_templates`.`id` = 10;


/*Lakmal:2018-01-26 for RP-637*/
UPDATE `email_templates` SET `template_body` = '<p>TalentGram: $TALENTGRAMNAME$ </p> <p>A candidate has expressed interest in the TalentGram you shared on social media.</p> <p>Name: $CANDIDATENAME$</p> <p>Email: $CANDIDATEEMAIL$</p> <p>Phone: $CANDIDATEPHONE$</p> <p>LinkedIn: $CANDIDATELINKEDIN$</p> <p>LinkedIn: $CANDIDATEMESSAGE$</p> ', `deleted_at` = NULL WHERE `email_templates`.`id` = 25;

/*Lakmal:2018-01-29 for RP-657*/
CREATE TABLE `admin_blogs` (
  `id` int(11) NOT NULL,
  `blog_title` text NOT NULL,
  `blog_desc` text NOT NULL,
  `status` enum('1','0') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `admin_blogs`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `admin_blogs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

/*Heshan:2018-02-02 for RP-660*/
ALTER TABLE `states` ADD FULLTEXT KEY `abbreviation` (`abbreviation`);

/*Week 3*/

INSERT INTO `email_templates` (`id`, `template_name`, `template_subject`, `template_body`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(27, 'Meeting Invitation', 'TalentGram Meeting Invitation', '<p>Hi $AGENCYNAME$,</p><p>$JOBOWNER$ is inviting you to a meeting on the TalentGram for $JOBNAME$.</p>\r\n\r\n\r\n<p>Title: $MEETINGTITLE$ </p>\r\n<p>Location: $MEETINGLOCATION$ </p>\r\n<p>Time: $MEETINGTIME$ </p>\r\n<p>Message: $MEETINGMESSAGE$ </p>\r\n\r\n<p>Join from PC, Mac, Linux, iOS or Android: @MEETINGURL@Click Here!@.</p>\r\n<p>Meeting ID: $MEETINGID$ </p>\r\n\r\n<p>Add to:</p>\r\n<p>@GOOGLELINK@Google Calender!@</p>\r\n<p>@OUTLOOKLINK@Outlook Calendar (.ics)!@</p>\r\n', '1', '2018-02-13 12:00:00', '2018-02-13 12:00:00', NULL);

/*Week 3 fixes*/
UPDATE `email_templates` SET `template_subject` = 'Agency Recruiter Assigned to your Job', `template_body` = '<p>$USERNAME$ with $AGENCYNAME$ has successfully been assigned as an approved agency to recruit for your open job: $JOBNAME$.</p>

<p>Log into your account here (@SITEURL@).</p>
', `deleted_at` = NULL WHERE `email_templates`.`id` = 26;


/*Week 4 fixes*/
UPDATE `email_templates` SET `template_body` = '<p>$COMPANYNAME$&nbsp;has posted a new TalentGram that is an industry match for your agency.</p>

<p>@SITEURL@Log into!@&nbsp;your TalentCapture account to review the details and respond. If you express interest in the TalentGram, the hiring manager will have the option to review, and verify or decline you as an approved&nbsp;agency.&nbsp; If your agency is approved, you&nbsp;have the ability to submit candidates for consideration through the TalentCapture marketplace. &nbsp;</p> 

<p>Here are the&nbsp;TalentGram Details,</p>

<p><strong>Job Title: </strong>$J_TITLE$</p>

<p><strong>Industry: </strong>$J_INDUSTRY$</p>

<p><strong>Profession: </strong>$J_PROFESSION$</p>

<p><strong>Primary Skills Required: </strong>$J_SKILLS$</p>

<p><strong>Location: </strong>$J_LOCATION$</p>

<p><strong>Resource Type: </strong>$J_RESOURCE$</p>

<p><strong>Salary or Hourly rate: </strong>$J_SALARY$</p>

<p><strong>Placement Fee<strong>: </strong></strong>$J_FEE$</p>

<p><strong>Split Fee Details<strong>: </strong></strong>$J_SPL_FEE$</p>

<p><strong>Notes: </strong>$J_NOTES$</p>

<p>The TalentGram will be closed for agency consideration after five agencies have been approved. &nbsp;</p>', `deleted_at` = NULL WHERE `email_templates`.`id` = 10;


update user_notifications set status = 1 where notification_url = 'searches/job_detail//any/new000'


UPDATE `email_templates` SET `template_body` = '<p><strong>$USERNAME$</strong> with <strong>$AGENCYNAME$ </strong>has successfully been assigned as an approved agency to recruit for your open Job: <strong>$JOBNAME$</strong>.</p>\r\n\r\n<p>Log into your account @SITEURL@here!@.</p>\r\n', `deleted_at` = NULL WHERE `email_templates`.`id` = 26;


delete from candidate_documents where candidate_id in (SELECT id from candidates) and title in (SELECT RESUME from candidates)

/*Week 5*/
INSERT INTO `email_templates` (`id`, `template_name`, `template_subject`, `template_body`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(28, 'Meeting Scheduled', 'TalentGram Meeting Scheduled', '<p>You have successfully scheduled the following conference call with agency recruiters you have approved for the following job: $JOBNAME$.</p>\r\n\r\n\r\n<p>Title: $MEETINGTITLE$ </p>\r\n<p>Location: $MEETINGLOCATION$ </p>\r\n<p>Time: $MEETINGTIME$ </p>\r\n<p>Message: $MEETINGMESSAGE$ </p>\r\n\r\n<p>Join from PC, Mac, Linux, iOS or Android: @MEETINGURL@Click Here!@.</p>\r\n<p>Meeting ID: $MEETINGID$ </p>\r\n\r\n<p>Add to:</p>\r\n<p>@GOOGLELINK@Google Calender!@</p>\r\n<p>@OUTLOOKLINK@Outlook Calendar (.ics)!@</p>\r\n', '1', '2018-02-23 12:00:00', '2018-02-23 12:00:00', NULL);


/*2018-03-22*/
ALTER TABLE `states`  ADD `abb_for_search` VARCHAR(5) NOT NULL  AFTER `abbreviation`;
ALTER TABLE `states` ADD FULLTEXT(`abb_for_search`);
ALTER TABLE `talentc9_stage`.`states` ADD FULLTEXT `name_abb_for_search` (`name`, `abb_for_search`);
UPDATE states SET abb_for_search = concat(abbreviation,"XX")
