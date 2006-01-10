CREATE TABLE `menu` (
  `menu_id` int(11) NOT NULL auto_increment,
  `site_section` varchar(50) NOT NULL default 'default',
  `parent` int(11) NOT NULL default '0',
  `dynamic_key` varchar(50) NOT NULL default '',
  `section` enum('children','more','dynamic') NOT NULL default 'children',
  `display_order` int(11) NOT NULL default '0',
  `title` varchar(255) NOT NULL default '',
  `action` varchar(255) NOT NULL default '',
  `prefix` varchar(100) NOT NULL default 'main',
  PRIMARY KEY  (`menu_id`)
) TYPE=MyISAM;
INSERT INTO `menu` VALUES (1,'',1,'','children',0,'','','main'),(2,'default',39,'','children',100,'Logout','Access/logout','main'),(3,'default',39,'','children',10,'Preferences','Preferences/list','main'),(4,'admin',1,'','children',800,'Reports','','main/Admin'),(5,'admin',1,'','children',400,'Schedules','',''),(7,'admin',5,'','children',10,'Add New Schedule','Location/edit_schedule','main'),(8,'default',5,'','children',20,'Add New Practice','Location/edit_practive','main'),(9,'admin',80,'','children',30,'Add New Building','Location/edit_building','main'),(10,'admin',80,'','children',40,'Add New Room','Location/edit_room','main'),(82,'admin',26,'','children',10,'List Forms','Form/list','main'),(12,'default',65,'','children',10,'Day','Calendar/day','main'),(13,'default',65,'','children',50,'Week Brief','Calendar/week','main'),(14,'default',65,'','children',20,'Week','Calendar/week_grid','main'),(15,'default',65,'','children',30,'Month','Calendar/month','main'),(16,'default',65,'','children',40,'Day Brief','Calendar/day_brief','main'),(17,'default',65,'','children',60,'Search','Calendar/search','main'),(18,'admin',45,'','children',10,'List Fee Schedules','FeeSchedule/default','main'),(19,'admin',45,'','children',20,'Add Fee Schedule','FeeSchedule/edit','main'),(20,'admin',4,'','children',10,'Add Report','Report/edit','main'),(21,'admin',81,'','children',10,'List Users','User/list','main'),(22,'admin',81,'','children',20,'Add User','User/edit','main'),(80,'admin',1,'','children',100,'Facilities','',''),(24,'admin',110,'','children',30,'List Enumerations','Enumeration/list','main'),(25,'admin',110,'','children',40,'Add Enumeration','Enumeration/edit','main'),(26,'admin',1,'','children',750,'Forms','',''),(27,'admin',26,'','children',20,'Add Form','Form/edit','main'),(28,'admin',26,'','children',30,'View Form Data','Form/view','main'),(29,'patient',68,'','children',10,'Fillout Form','Form/fillout','main'),(30,'patient',1,'','children',100,'Patients','',''),(31,'patient',30,'','children',20,'Add Patient','Patient/edit','main'),(32,'admin',109,'','children',160,'List Payers','Insurance/list','main'),(33,'admin',109,'','children',170,'Add Payer','Insurance/edit','main'),(36,'admin',110,'','children',50,'Document Categories','DocumentCategory/list','main'),(37,'patient',68,'','children',20,'Documents','Document/list','main'),(38,'admin',45,'','children',30,'Edit Superbill','Superbill/list','main'),(39,'default',1,'','children',300,'My Account','','main'),(81,'admin',1,'','children',300,'Users','',''),(42,'billing',1,'','children',300,'Reports','','main/Billing'),(43,'default',1,'','children',200,'Reports','','main/Calendar'),(44,'patient',1,'','children',300,'Reports','','main/Patient'),(45,'admin',1,'','children',200,'Billing','','main'),(46,'patient',1,'','children',400,'My Account','','main'),(47,'patient',46,'','children',100,'Logout','Access/logout','main'),(48,'patient',46,'','children',10,'Preferences','Preferences/list','main'),(49,'billing',1,'','children',500,'My Account','','main'),(57,'billing',49,'','children',100,'Logout','Access/logout','main'),(58,'billing',49,'','children',10,'Preferences','Preferences/list','main'),(59,'admin',1,'','children',900,'My Account','','main'),(60,'admin',59,'','children',100,'Logout','Access/logout','main'),(61,'admin',59,'','children',10,'Preferences','Preferences/list','main'),(62,'billing',1,'','children',100,'Claims','','freeb2'),(63,'billing',62,'','children',10,'List Claims','Claim/list','freeb2'),(64,'billing',62,'','children',20,'Add Claim','Claim/edit','freeb2'),(65,'default',1,'','children',100,'View','',''),(66,'default',1,'','children',400,'Help','',''),(67,'patient',30,'','children',10,'List Patients','Patient/list','main'),(68,'patient',1,'','children',200,'Actions','',''),(69,'patient',30,'','children',30,'Search','PatientFinder/find','main'),(70,'patient',68,'','children',30,'Encounter','Encounter/add','main'),(71,'default',66,'','children',10,'API Docs','Docs/api','main'),(72,'patient',68,'','children',5,'Dashboard','PatientDashboard/view','main'),(74,'patient',1,'','children',500,'Help','',''),(75,'patient',74,'','children',10,'API Docs','Docs/api','main'),(76,'billing',1,'','children',600,'Help','',''),(77,'billing',76,'','children',10,'API Docs','Docs/api','main'),(78,'admin',1,'','children',1000,'Help','',''),(79,'admin',78,'','children',10,'API Docs','Docs/api','main'),(83,'admin',80,'','children',5,'List Facilities','Location/list','main'),(84,'admin',80,'','children',20,'Add New Practice','Location/edit_practice','main'),(85,'admin',4,'','children',5,'List Reports','Report/list','main'),(86,'admin',1,'','children',900,'','Admin/default','main'),(87,'admin',4,'','children',50,'Connect Report','Report/connect','main'),(88,'billing',1,'','children',0,'','Billing/default','main'),(89,'patient',1,'','children',-1,'Dashboard Reports','','main/Patient'),(90,'patient',1,'','children',-1,'Dashboard Forms','','main/Patient'),(91,'patient',1,'','children',-1,'Encounter Forms','','main/Encounter'),(92,'admin',26,'','children',100,'Connect','Form/connect','main'),(93,'billing',1,'','children',0,'','Eob/Payment','main'),(94,'default',39,'','children',50,'Change Password','MyAccount/password','main'),(95,'patient',46,'','children',50,'Change Password','MyAccount/password','main'),(96,'billing',49,'','children',50,'Change Password','MyAccount/password','main'),(97,'admin',59,'','children',50,'Change Password','MyAccount/password','main'),(98,'admin',110,'','children',800,'ACL Editor','Admin/acl','main'),(100,'admin',5,'','children',4,'List Schedules','Location/schedules','main'),(101,'default',1,'','children',700,'Admin','','main'),(102,'default',101,'','children',10,'Add New Schedule','Location/edit_schedule','main'),(103,'default',101,'','children',4,'List Schedules','Location/schedules','main'),(104,'billing',1,'','children',800,'Admin','','main'),(105,'billing',104,'','children',160,'List Payers','Insurance/list','main'),(106,'billing',104,'','children',170,'Add Payers','Insurance/edit','main'),(109,'admin',1,'','children',250,'Payers','','main'),(110,'admin',1,'','children',700,'System','','main'),(111,'default',0,'','children',0,'','','main'),
('112', 'admin', '1', '', 'children', '5000', 'Practice', '', 'main'), 
('113', 'billing', '1', '', 'children', '5000', 'Practice', '', 'main'), 
('114', 'patient', '1', '', 'children', '5000', 'Practice', '', 'main'), 
('115', 'default', '1', '', 'children', '5000', 'Practice', '', 'main'),
('116', 'admin', '5', '', 'children', '300', 'List Appointment Templates', 'AppointmentTemplate/list', 'main'),
('117', 'admin', '5', '', 'children', '310', 'Add Appointment Template', 'AppointmentTemplate/add', 'main'),
('118', 'admin', '5', '', 'children', '0', 'Edit Appointment Template', 'AppointmentTemplate/edit', 'main');
CREATE TABLE `menu_form` (
  `menu_form_id` int(11) NOT NULL default '0',
  `menu_id` int(11) NOT NULL default '0',
  `form_id` int(11) NOT NULL default '0',
  `title` varchar(50) NOT NULL default '',
  `custom_action` varchar(255) default NULL,
  PRIMARY KEY  (`menu_form_id`),
  KEY `menu_id` (`menu_id`),
  KEY `form_id` (`form_id`)
) TYPE=MyISAM;
INSERT INTO `menu_form` VALUES (505001,91,1710,'Patient Vitals',NULL);
CREATE TABLE `menu_report` (
  `menu_report_id` int(11) NOT NULL default '0',
  `menu_id` int(11) NOT NULL default '0',
  `report_template_id` int(11) NOT NULL default '0',
  `title` varchar(50) NOT NULL default '',
  `custom_action` varchar(255) default NULL,
  PRIMARY KEY  (`menu_report_id`),
  KEY `menu_id` (`menu_id`),
  KEY `report_template_id` (`report_template_id`)
) TYPE=MyISAM;
