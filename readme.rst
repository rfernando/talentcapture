####################
TalentCapture Portal
####################

TalentCapture Portal is a Job portal where different Agencies and Employers can connect and find candidates matching the
job profile. The system has been designed with Codeigniter Framework  (Version 3.0) and some features of Laravel
Framework has been used to reduce the complexity of the application. The website is currently ( at the time of
writing of this documentation ) under development  and can be found at the below links :

- `Front End <http://raincatcherportal.com/dev/raincatcher>`_
- `Admin Panel <http://raincatcherportal.com/dev/raincatcher/admin/login>`_

*******************
Server Requirements
*******************

- PHP version 5.6 or higher.
- Composer
- Mysql PDO Driver

*******************
Application Working
*******************

There are 3 kinds of users in the System.
- Admin User (Users who can login into the Admin Panel and has the authority to approve Jobs and block Users)
- Employer (These Users can add Job and hire candidates who have been added to their Job)
- Agency (These Users can add both Jobs and Candidates to a Job posted by some other Employer or Agency)

A user can register as an Employer or Agency into a system. Both Employer and Agency have the ability to add a new Job.
The Job is then approved by admin from the admin panel after which it is displayed on the Agency user's Dashboard if the
Industry and Professions (Editable from user's Profile page) match the industry and profession for the Job which an agency
can either accept or reject a job. All accepted Jobs are displayed under the My Searches Page for an Agency. An agency can
then add candidates to that Job which are displayed on the Dashboard of the User who had originally posted the Job.
He/She can then either accept or Reject a candidate for that Job. Candidate's contact details are mentioned in the 

**************************
Changelog and New Features
**************************

You can find a list of all changes for each release in the `user
guide change log <https://github.com/bcit-ci/CodeIgniter/blob/develop/user_guide_src/source/changelog.rst>`_.



************
Installation
************

Please see the `installation section <https://codeigniter.com/user_guide/installation/index.html>`_
of the CodeIgniter User Guide.

*******
License
*******

Please see the `license
agreement <https://github.com/bcit-ci/CodeIgniter/blob/develop/user_guide_src/source/license.rst>`_.

*********
Resources
*********

-  `User Guide <https://codeigniter.com/docs>`_
-  `Language File Translations <https://github.com/bcit-ci/codeigniter3-translations>`_
-  `Community Forums <http://forum.codeigniter.com/>`_
-  `Community Wiki <https://github.com/bcit-ci/CodeIgniter/wiki>`_
-  `Community IRC <https://webchat.freenode.net/?channels=%23codeigniter>`_

Report security issues to our `Security Panel <mailto:security@codeigniter.com>`_
or via our `page on HackerOne <https://hackerone.com/codeigniter>`_, thank you.

***************
Acknowledgement
***************

The CodeIgniter team would like to thank EllisLab, all the
contributors to the CodeIgniter project and you, the CodeIgniter user.