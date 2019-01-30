*******Lakmal*******

ALTER TABLE `candidates`  ADD `linkedin_url` VARCHAR(100) NULL  AFTER `phone`,  ADD `facebook_url` VARCHAR(100) NULL  AFTER `linkedin_url`;






*******Heshan*******

ALTER TABLE `jobs` CHANGE `client_name_confidential` `client_name_confidential` TINYINT(1) NOT NULL DEFAULT '0';

ALTER TABLE `jobs` ADD `relocate` VARCHAR(3) NOT NULL DEFAULT 'No' AFTER `updated_at`;

ALTER TABLE `jobs` CHANGE `visa_sponsorship` `visa_sponsorship` VARCHAR(3) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL;

ALTER TABLE `candidates` ADD `client_accepted_at` DATETIME NULL AFTER `updated_at`;

ALTER TABLE `users` ADD `last_job_type` VARCHAR(45) NULL AFTER `updated_at`;



***********Lakmal***********
INSERT INTO `site_messages` (`id`, `msg`, `type`, `updated_at`) VALUES
(12, '<p>I understand and agree to the following:</p>\r\n                    <ul>\r\n                        <li>I will use the messaging feature in the TalentCapture Platform for communication with other Agencies and Employers.</li>\r\n                        <li>I will not call an employer that posts a job listing on TalentCapture, without first receiving consent from the employer through the messaging feature.</li>\r\n                        <li>When I post a job listing and successfully hire a candidate who was presented by another agency, it is my responsibility to pay the agency directly that represented the candidate.</li>\r\n                        <li>It is my responsibility to have a split fee agreement in place with other agencies and TalentCapture is free from any liability if an agency does not make payment on a split placement.</li>\r\n                        <li>I will provide honest reviews and all times use a professional etiquette.</li>\r\n                        <li>I agree to the terms and conditions set forth which can be read in entirety here.</li>\r\n                    </ul>', 'terms_and_condition_agency', '2017-07-13 10:10:10'),
(13, '<p>I understand and agree to the following:</p>                    <ul>                        <li>I agree to use the TalentCapture messaging feature for email communication with Agenices.</li>                        <li>I understand that when a candidate is successfully hired through the TalentCapture Platform, the placement fee the Employer pays is 15% of the candidate''s agreed upon annual salary.</li>                        <li>I understand that I can rate Agencies on their performance and provide written reviews for others to see.</li>                        <li>I understand I can mark an Agency as a Preferred Agency. This list is found under the section My Agencies. I have the option to notify any Preferred Agency of future job listings.</li>                        <li>I understand any time an Employer provides a review of 2 1/2 starts or less to an Agency, the Agency will no longer be notified of future job listings by the Employer who provided the rating.</li>                <li>I agree to the terms and conditions set forth which can be read in entirety here.</li>                            </ul>', 'terms_and_condition_employer', '2017-07-13 10:10:10');


*******Heshan*******
ALTER TABLE `rating` ADD `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `review`, ADD `rat_status` BOOLEAN NOT NULL DEFAULT TRUE AFTER `created_at`;


***********Lakmal***********
INSERT INTO `email_templates` (`id`, `template_name`, `template_subject`, `template_body`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(19, 'AgencyAcceptance', 'Review Agency on TalentCapture', '<p>$AGENCYNAME$ has accepted your TalentGram for $JOBNAME$.</p>\r\n\r\n<p>From your Dashboard, you can view the agency profile, and approve or decline the agency as one of the five verified agencies you select to recruit for your opening.</p>\r\n\r\n<p>Log into your account here (@SITEURL@).</p>\r\n', '1', '2017-07-16 16:43:05', '2017-07-16 11:13:05', NULL);

INSERT INTO `email_templates` (`id`, `template_name`, `template_subject`, `template_body`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(20, 'AgencyVerified', 'TalentGram request verified', '<p>Congratulations! You have been verified as an approved agency for the following TalentGram: </p>\r\n\r\n<p>$JOBNAME$</p>\r\n\r\n<p>You can now begin submitting candidates for this opening on the TalentCapture Platform.</p>\r\n\r\n<p>Log into your account here (@SITEURL@).</p>\r\n', '1', '2017-07-16 16:43:05', '2017-07-16 11:13:05', NULL);

INSERT INTO `email_templates` (`id`, `template_name`, `template_subject`, `template_body`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(21, 'AgencyRejected', 'TalentGram request declined', "<p>I'm sorry to inform you that you have been declined as an approved agency for the following TalentGram: </p>\r\n\r\n<p>$JOBNAME$</p>\r\n", '1', '2017-07-16 16:43:05', '2017-07-16 11:13:05', NULL)

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `raincatc_dev`
--

-- --------------------------------------------------------

--
-- Table structure for table `user_notifications`
--

CREATE TABLE `user_notifications` (
  `id` int(11) NOT NULL,
  `notification_text` text NOT NULL,
  `notification_url` text NOT NULL,
  `user_id` int(11) NOT NULL,
  `status` enum('1','0') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `user_notifications`
--
ALTER TABLE `user_notifications`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `user_notifications`
--
ALTER TABLE `user_notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;


***** Lakmal 2017-07-30***** 
INSERT INTO `site_messages` (`id`, `msg`, `type`, `updated_at`) VALUES ('14', 'Preferred Agencies are agency users you have added to your Preferred Agency list. You can choose to send your Job only to agency users on this list, or you can notify all agencies that recruit for your industry. You also have the option to call 832-258-1367 for agency referrals that are best qualified to recruit for your Job types', 'agency_notification', '2017-07-31 00:00:00');

***** Lakmal 2017-08-11***** 
UPDATE `email_templates` SET `template_subject` = '$CANDIDATENAME$ Has Been Hired!' WHERE `email_templates`.`id` = 1;
UPDATE `email_templates` SET `template_body` = '<p>Congratulations! $FIRSTNAME$ $LASTNAME$ has marked your candidate as hired! We recommend you log into your account and verify the hire details by selecting the $ icon on the candidate&#39;s profile.&nbsp;</p>\r\n\r\n<p>Once the candidate has successfully started the new job, an invoice will be generated to the client. You will then be paid in accordance with the agreed upon TalentCapture terms and conditions.</p>\r\n\r\n<p>Start Date: $STARTDATE$</p>\r\n\r\n<p>Salary or Rate: $BASESALARY$</p>\r\n\r\n<p>&nbsp;Please @SITEURL@ Log into!@ your account.</p>\r\n', `deleted_at` = NULL WHERE `email_templates`.`id` = 2;

***** Lakmal 2017-08-15*****
ALTER TABLE `free_trials` ADD UNIQUE( `agency_id`);
ALTER TABLE `accepted_jobs` ADD UNIQUE( `user_id`, `job_id`);