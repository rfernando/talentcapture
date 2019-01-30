ALTER TABLE `user_profiles`  ADD `recruiter_profile` text DEFAULT NULL  AFTER `company_website_url`;
ALTER TABLE `messages`  ADD `type`  enum('1','0') NOT NULL DEFAULT '0' COMMENT '0=>Normal, 1=>Special'  AFTER `text`;
ALTER TABLE `messages`  ADD `candidate_id`  int(11) DEFAULT '0'  AFTER `type`;

/*Lakmal:2018-04-26 for RP-678*/
ALTER TABLE `candidates`  ADD `will_relocate` VARCHAR(3) NULL DEFAULT 'No'  AFTER `state_id`;

/*Lakmal:2018-04-26 for RP-683*/
ALTER TABLE `admin_blogs`  ADD `view_by` VARCHAR(8) NULL DEFAULT 'Both'  AFTER `status`;


/*Job Description edited by Agency*/
create table agency_job_description(
   id int(11) NOT NULL AUTO_INCREMENT,
   agency_id int(11) NOT NULL,
   job_id int(11) NOT NULL,
   job_description text DEFAULT NULL,
   added_by int(11) NOT NULL DEFAULT '0',
   created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
   updated_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
   deleted_at timestamp NULL DEFAULT NULL,
   PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


/*Pramod:2018-05-02 for RP-685*/

INSERT INTO `site_messages` (`id`, `msg`, `type`, `updated_at`) VALUES ('18', 'Keep Client Confidential - update this message from the admin panel', 'client_name_confidential', '2018-05-02 00:00:00');

INSERT INTO `site_messages` (`id`, `msg`, `type`, `updated_at`) VALUES ('19', 'This is job attachment message and need to finalize the message', 'jobattachment', '2018-05-02 00:00:00');



/*Lakmal:2018-05-02 for RP-694*/
ALTER TABLE `hire_details`  ADD `hire_cancelled` INT NOT NULL DEFAULT '0'  AFTER `type`;
ALTER TABLE `hire_details`  ADD `hire_cancelled_by` INT NULL  AFTER `hire_cancelled`;
ALTER TABLE `hire_details`  ADD `hire_cancelled_at` DATETIME NULL  AFTER `hire_cancelled_by`;

/*Lakmal:2018-05-06 for RP-694*/
INSERT INTO `email_templates` (`id`, `template_name`, `template_subject`, `template_body`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(29, 'Hire Details Cancelled', 'New Hire Details Cancelled', '<p>Following hire is cancelled by $CANCELLEDUSER$.</p>\r\n\r\n\<p>Candidate Name: $CANDIDATENAME$ </p>\r\n<p>Candidate\'s Agency: $AGENCYUSER$, $AGENCYCOMP$ </p>\r\n<p>Job owner: $JOBOWNERNAME$, $JOBOWNERCOMP$ </p>\r\n\r\n', '1', '2018-05-06 12:00:00', '2018-05-06 12:00:00', NULL);


UPDATE `email_templates` SET `template_body` = '<p>$JOBOWNER$ is inviting you to a meeting on the TalentGram for $JOBNAME$.</p>\r\n\r\n\r\n<p>Title: $MEETINGTITLE$ </p>\r\n<p>Location: $MEETINGLOCATION$ </p>\r\n<p>Time: $MEETINGTIME$ </p>\r\n<p>Message: $MEETINGMESSAGE$ </p>\r\n\r\n<p>Join from PC, Mac, Linux, iOS or Android: @MEETINGURL@Click Here!@.</p>\r\n<p>Meeting ID: $MEETINGID$ </p>\r\n' WHERE `email_templates`.`id` = 27;