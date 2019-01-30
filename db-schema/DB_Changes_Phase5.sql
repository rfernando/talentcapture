/*Lakmal:2017-09-28 for RP-540*/
DELETE FROM `recruiter_notes` WHERE `feedback` = ''

/*Lakmal:2017-10-05 for RP-521*/
INSERT INTO `email_templates` (`id`, `template_name`, `template_subject`, `template_body`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(22, 'TalentCaptureResumeUpload', 'TalentCapture Resume Uploaded', "<p> </p>", '1', '2017-10-10 16:43:05', '2017-10-10 11:13:05', NULL)

/*Lakmal:2017-10-06 for RP-533*/
INSERT INTO `email_templates` (`id`, `template_name`, `template_subject`, `template_body`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(23, 'JobClosure', 'Job Has Been Closed', "<p>$USERNAME$ has closed the following job: </p>\r\n\r\n<p>$JOBNAME$</p>\r\n<p>Candidates Hired:</p>\r\n<p>$CANDIDATELIST$</p>\r\n\r\n<p>Agency Ratings:</p>\r\n<p>$AGENCYLIST$</p>\r\n", '1', '2017-10-10 00:00:00', '2017-10-10 00:00:00', NULL)

INSERT INTO `site_messages` (`id`, `msg`, `type`, `updated_at`) VALUES ('15', 'The hiring manager must approve your request to recruit for this TalentGram before you can submit candidates.', 'approval_pending', '2017-10-10 00:00:00');



/*Lakmal:2017-10-10 for RP-379*/
CREATE TABLE `my_charities` (
  `id` int(11) NOT NULL,
  `title` varchar(45) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `my_charities`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `my_charities` CHANGE `id` `id` INT(11) NOT NULL AUTO_INCREMENT;


CREATE TABLE `my_charities_user` (
  `user_id` int(11) NOT NULL,
  `my_charities_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `my_charities_user`
  ADD PRIMARY KEY (`user_id`,`my_charities_id`),
  ADD KEY `fk_users_has_charities_charities1_idx` (`my_charities_id`),
  ADD KEY `fk_users_has_charities_users1_idx` (`user_id`);

ALTER TABLE `my_charities_user`
  ADD CONSTRAINT `fk_users_has_charities_charities1` FOREIGN KEY (`my_charities_id`) REFERENCES `my_charities` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_users_has_charities_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;



ALTER TABLE `user_notifications`  ADD `cn_status` INT NOT NULL DEFAULT '0'  AFTER `status`;
UPDATE `user_notifications` SET `cn_status` = 1


/*Lakmal:2017-10-17 for week 4*/
INSERT INTO `email_templates` (`id`, `template_name`, `template_subject`, `template_body`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(24, 'CharitableOrganizationsAssigned', 'Charitable Organizations Assigned', "<p>User: $FULLNAME$ </p>\r\n\r\n<p>Agency: $COMPNAME$</p>\r\n<p>Selections:$CHARITYLIST$</p>\r\n", '1', '2017-10-17 00:00:00', '2017-10-17 00:00:00', NULL)


INSERT INTO `email_templates` (`id`, `template_name`, `template_subject`, `template_body`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(25, 'Application for TalentGram Shared on <Linkedin or Facebook or Twitter>', 'Interest in your TalentGram', "<p>TalentGram: $TALENTGRAMNAME$ </p>\r\n\r\n<p>A candidate has expressed interest in the TalentGram you shared on social media.</p>\r\n<p>Name: $CANDIDATENAME$</p>\r\n<p>Email: $CANDIDATEEMAIL$</p>\r\n<p>Phone: $CANDIDATEPHONE$</p>\r\n<p>LinkedIn: $CANDIDATELINKEDIN$</p>\r\n", '1', '2017-10-17 00:00:00', '2017-10-17 00:00:00', NULL)


ALTER TABLE `jobs`  ADD `question` TEXT NULL  AFTER `description`;


/*To remove software developer and update the records with software development*/
DELETE FROM profession_user WHERE profession_id=16 AND user_id IN (SELECT user_id FROM tmpprofession_user WHERE profession_id IN (16,2) GROUP BY user_id HAVING COUNT(user_id)>1)

UPDATE profession_user SET profession_id=2 WHERE profession_id=16

DELETE FROM industry_profession WHERE profession_id=16 AND industry_id IN (SELECT industry_id FROM tmpindustry_profession WHERE profession_id IN (16,2) GROUP BY industry_id HAVING COUNT(industry_id)>1)

UPDATE industry_profession SET profession_id=2 WHERE profession_id=16

DELETE FROM professions WHERE id=16

ALTER TABLE `users` CHANGE `phone` `phone` VARCHAR(13) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;


/*Lakmal:2017-10-25 for week 5*/
DELETE FROM messages WHERE to_user_id = 1;

UPDATE users SET first_name = 'Customer', last_name = 'Support' WHERE type = 'admin';

INSERT INTO messages (from_user_id, to_user_id,text,viewed,created_at,updated_at)
SELECT 1, id, 'Welcome to TalentCapture support', 0,'2017-10-28 00:00:00','2017-10-28 00:00:00'
FROM users

/*---*/
ALTER TABLE `jobs` ADD `add_agency` INT NOT NULL DEFAULT '0' AFTER `relocate`;

/*To integrate LinkedIn*/
composer require league/oauth2-linkedin


ALTER TABLE `users`  ADD `register_mode` INT NOT NULL DEFAULT '0'  AFTER `is_trial`;ALTER TABLE `users`  ADD `register_mode` INT NOT NULL DEFAULT '0'  AFTER `is_trial`;

/*---After week 6---*/
ALTER TABLE `users` ADD FULLTEXT(`first_name`);
ALTER TABLE `users` ADD FULLTEXT(`last_name`);
ALTER TABLE `users` ADD FULLTEXT `first_last_names` (`first_name`, `last_name`);

ALTER TABLE `user_profiles` ADD FULLTEXT(`city`);
ALTER TABLE `user_profiles` ADD FULLTEXT(`zipcode`);
ALTER TABLE `user_profiles` ADD FULLTEXT(`company_name`);
ALTER TABLE `user_profiles` ADD FULLTEXT `city_zip_comp` (`city`, `zipcode`, `company_name`);

ALTER TABLE `industries` ADD FULLTEXT(`title`);

/*-----11/30/2017----*/
ALTER TABLE `states` ADD FULLTEXT(`name`);