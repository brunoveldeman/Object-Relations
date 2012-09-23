SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `or`
--

--
-- Dumping data for table `issuestatus`
--

INSERT INTO `issuestatus` (`id`, `name`, `deleted`, `timestamp`) VALUES(1, 'New', 0, '0000-00-00 00:00:00');
INSERT INTO `issuestatus` (`id`, `name`, `deleted`, `timestamp`) VALUES(2, 'Open', 0, '0000-00-00 00:00:00');
INSERT INTO `issuestatus` (`id`, `name`, `deleted`, `timestamp`) VALUES(3, 'Closed', 0, '0000-00-00 00:00:00');
INSERT INTO `issuestatus` (`id`, `name`, `deleted`, `timestamp`) VALUES(4, 'Stuck', 0, '0000-00-00 00:00:00');

--
-- Dumping data for table `issuetype`
--

INSERT INTO `issuetype` (`id`, `name`, `deleted`, `timestamp`) VALUES(1, 'Quality issue', 0, '0000-00-00 00:00:00');
INSERT INTO `issuetype` (`id`, `name`, `deleted`, `timestamp`) VALUES(2, 'Technical issue', 0, '0000-00-00 00:00:00');
INSERT INTO `issuetype` (`id`, `name`, `deleted`, `timestamp`) VALUES(3, 'Packaging issue', 0, '0000-00-00 00:00:00');

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `description`, `displayorder`) VALUES(1, 'listobjects', 'Permission to list objects', 3);
INSERT INTO `permissions` (`id`, `name`, `description`, `displayorder`) VALUES(2, 'viewobject', 'Permission to view the object detail', 4);
INSERT INTO `permissions` (`id`, `name`, `description`, `displayorder`) VALUES(3, 'addobject', 'Permission to add objects', 5);
INSERT INTO `permissions` (`id`, `name`, `description`, `displayorder`) VALUES(4, 'editobject', 'Permission to edit objects', 6);
INSERT INTO `permissions` (`id`, `name`, `description`, `displayorder`) VALUES(5, 'deleteobject', 'Permission to delete objects', 7);
INSERT INTO `permissions` (`id`, `name`, `description`, `displayorder`) VALUES(6, 'listtypes', 'Permission to list types', 8);
INSERT INTO `permissions` (`id`, `name`, `description`, `displayorder`) VALUES(7, 'viewtype', 'Permission to view type', 9);
INSERT INTO `permissions` (`id`, `name`, `description`, `displayorder`) VALUES(8, 'addtype', 'Permission to add types', 10);
INSERT INTO `permissions` (`id`, `name`, `description`, `displayorder`) VALUES(9, 'edittype', 'Permission to edit types', 11);
INSERT INTO `permissions` (`id`, `name`, `description`, `displayorder`) VALUES(10, 'deletetype', 'Permission to delete types', 12);
INSERT INTO `permissions` (`id`, `name`, `description`, `displayorder`) VALUES(11, 'listproperties', 'Permission to list properties', 13);
INSERT INTO `permissions` (`id`, `name`, `description`, `displayorder`) VALUES(12, 'viewproperty', 'Permission to view properties', 14);
INSERT INTO `permissions` (`id`, `name`, `description`, `displayorder`) VALUES(13, 'addproperty', 'Permission to add properties', 15);
INSERT INTO `permissions` (`id`, `name`, `description`, `displayorder`) VALUES(14, 'editproperty', 'Permission to edit properties', 16);
INSERT INTO `permissions` (`id`, `name`, `description`, `displayorder`) VALUES(15, 'deleteproperty', 'Permission to delete properties', 17);
INSERT INTO `permissions` (`id`, `name`, `description`, `displayorder`) VALUES(16, 'listrelations', 'Permission to list relations', 18);
INSERT INTO `permissions` (`id`, `name`, `description`, `displayorder`) VALUES(17, 'viewrelation', 'Permission to view relations', 19);
INSERT INTO `permissions` (`id`, `name`, `description`, `displayorder`) VALUES(18, 'addrelation', 'Permission to add relations', 20);
INSERT INTO `permissions` (`id`, `name`, `description`, `displayorder`) VALUES(19, 'editrelation', 'Permission to edit relations', 21);
INSERT INTO `permissions` (`id`, `name`, `description`, `displayorder`) VALUES(20, 'deleterelation', 'Permission to delete relations', 22);
INSERT INTO `permissions` (`id`, `name`, `description`, `displayorder`) VALUES(21, 'viewobjecttype', 'Permission to view object types', 23);
INSERT INTO `permissions` (`id`, `name`, `description`, `displayorder`) VALUES(22, 'editobjecttype', 'Permission to edit object types', 24);
INSERT INTO `permissions` (`id`, `name`, `description`, `displayorder`) VALUES(23, 'viewobjectproperty', 'Permision to view object properties', 25);
INSERT INTO `permissions` (`id`, `name`, `description`, `displayorder`) VALUES(24, 'addobjectproperty', 'Permission to add object properties', 26);
INSERT INTO `permissions` (`id`, `name`, `description`, `displayorder`) VALUES(25, 'editobjectproperty', 'Permission to edit object properties', 27);
INSERT INTO `permissions` (`id`, `name`, `description`, `displayorder`) VALUES(26, 'deleteobjectproperty', 'Permission to delete object properties', 28);
INSERT INTO `permissions` (`id`, `name`, `description`, `displayorder`) VALUES(27, 'viewobjectrelation', 'Permission to view object relations', 29);
INSERT INTO `permissions` (`id`, `name`, `description`, `displayorder`) VALUES(28, 'addobjectrelation', 'Permission to add object relations', 30);
INSERT INTO `permissions` (`id`, `name`, `description`, `displayorder`) VALUES(29, 'editobjectrelation', 'Permission to edit object relations', 31);
INSERT INTO `permissions` (`id`, `name`, `description`, `displayorder`) VALUES(30, 'deleteobjectrelation', 'Permission to delete object relations', 32);
INSERT INTO `permissions` (`id`, `name`, `description`, `displayorder`) VALUES(31, 'reports', 'Permission to create reports', 33);
INSERT INTO `permissions` (`id`, `name`, `description`, `displayorder`) VALUES(34, 'access', 'Permission to access the database application.', 1);
INSERT INTO `permissions` (`id`, `name`, `description`, `displayorder`) VALUES(35, 'usermanagement', 'Permission to create, edit and delete users, groups and group permissions. Should be reserved to administrators only.', 100);
INSERT INTO `permissions` (`id`, `name`, `description`, `displayorder`) VALUES(41, 'search', 'Permission to use the search function', 2);
INSERT INTO `permissions` (`id`, `name`, `description`, `displayorder`) VALUES(42, 'listissues', 'Permission to list issues', 40);
INSERT INTO `permissions` (`id`, `name`, `description`, `displayorder`) VALUES(43, 'viewissue', 'Permission to view issue detail', 41);
INSERT INTO `permissions` (`id`, `name`, `description`, `displayorder`) VALUES(44, 'addissue', 'Permission to add issued', 42);
INSERT INTO `permissions` (`id`, `name`, `description`, `displayorder`) VALUES(45, 'editissue', 'Permission to edit issues', 44);
INSERT INTO `permissions` (`id`, `name`, `description`, `displayorder`) VALUES(46, 'deleteissue', 'Permission to delete issues', 45);
INSERT INTO `permissions` (`id`, `name`, `description`, `displayorder`) VALUES(49, 'addissuemsg', 'Permission to update issues', 43);

--
-- Dumping data for table `property_class`
--

INSERT INTO `property_class` (`id`, `name`, `description`, `timestamp`, `deleted`) VALUES(1, 'General', 'General properties', '2012-09-01 06:47:43', 0);
INSERT INTO `property_class` (`id`, `name`, `description`, `timestamp`, `deleted`) VALUES(2, 'Dimensions', 'Related to dimensions of the product, box or carton', '0000-00-00 00:00:00', 0);

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `login`, `name`, `password`, `group_id`, `deleted`, `timestamp`) VALUES(1, 'admin', 'Administrative user', '21232f297a57a5a743894a0e4a801fc3', 1, 0, '0000-00-00 00:00:00');

--
-- Dumping data for table `usergroup`
--

INSERT INTO `usergroup` (`id`, `name`, `description`, `deleted`, `timestamp`) VALUES(1, 'Administrators', 'Administrator group', 0, '0000-00-00 00:00:00');

--
-- Dumping data for table `usergroup_permissions`
--

INSERT INTO `usergroup_permissions` (`id`, `group_id`, `permission_id`, `value`) VALUES(1, 1, 34, 1);
INSERT INTO `usergroup_permissions` (`id`, `group_id`, `permission_id`, `value`) VALUES(2, 1, 41, 1);
INSERT INTO `usergroup_permissions` (`id`, `group_id`, `permission_id`, `value`) VALUES(3, 1, 1, 1);
INSERT INTO `usergroup_permissions` (`id`, `group_id`, `permission_id`, `value`) VALUES(4, 1, 2, 1);
INSERT INTO `usergroup_permissions` (`id`, `group_id`, `permission_id`, `value`) VALUES(5, 1, 3, 1);
INSERT INTO `usergroup_permissions` (`id`, `group_id`, `permission_id`, `value`) VALUES(6, 1, 4, 1);
INSERT INTO `usergroup_permissions` (`id`, `group_id`, `permission_id`, `value`) VALUES(7, 1, 5, 0);
INSERT INTO `usergroup_permissions` (`id`, `group_id`, `permission_id`, `value`) VALUES(8, 1, 6, 1);
INSERT INTO `usergroup_permissions` (`id`, `group_id`, `permission_id`, `value`) VALUES(9, 1, 7, 1);
INSERT INTO `usergroup_permissions` (`id`, `group_id`, `permission_id`, `value`) VALUES(10, 1, 8, 1);
INSERT INTO `usergroup_permissions` (`id`, `group_id`, `permission_id`, `value`) VALUES(11, 1, 9, 1);
INSERT INTO `usergroup_permissions` (`id`, `group_id`, `permission_id`, `value`) VALUES(12, 1, 10, 0);
INSERT INTO `usergroup_permissions` (`id`, `group_id`, `permission_id`, `value`) VALUES(13, 1, 11, 1);
INSERT INTO `usergroup_permissions` (`id`, `group_id`, `permission_id`, `value`) VALUES(14, 1, 12, 1);
INSERT INTO `usergroup_permissions` (`id`, `group_id`, `permission_id`, `value`) VALUES(15, 1, 13, 1);
INSERT INTO `usergroup_permissions` (`id`, `group_id`, `permission_id`, `value`) VALUES(16, 1, 14, 1);
INSERT INTO `usergroup_permissions` (`id`, `group_id`, `permission_id`, `value`) VALUES(17, 1, 15, 0);
INSERT INTO `usergroup_permissions` (`id`, `group_id`, `permission_id`, `value`) VALUES(18, 1, 16, 1);
INSERT INTO `usergroup_permissions` (`id`, `group_id`, `permission_id`, `value`) VALUES(19, 1, 17, 1);
INSERT INTO `usergroup_permissions` (`id`, `group_id`, `permission_id`, `value`) VALUES(20, 1, 18, 1);
INSERT INTO `usergroup_permissions` (`id`, `group_id`, `permission_id`, `value`) VALUES(21, 1, 19, 1);
INSERT INTO `usergroup_permissions` (`id`, `group_id`, `permission_id`, `value`) VALUES(22, 1, 20, 0);
INSERT INTO `usergroup_permissions` (`id`, `group_id`, `permission_id`, `value`) VALUES(23, 1, 21, 1);
INSERT INTO `usergroup_permissions` (`id`, `group_id`, `permission_id`, `value`) VALUES(24, 1, 22, 1);
INSERT INTO `usergroup_permissions` (`id`, `group_id`, `permission_id`, `value`) VALUES(25, 1, 23, 1);
INSERT INTO `usergroup_permissions` (`id`, `group_id`, `permission_id`, `value`) VALUES(26, 1, 24, 1);
INSERT INTO `usergroup_permissions` (`id`, `group_id`, `permission_id`, `value`) VALUES(27, 1, 25, 1);
INSERT INTO `usergroup_permissions` (`id`, `group_id`, `permission_id`, `value`) VALUES(28, 1, 26, 0);
INSERT INTO `usergroup_permissions` (`id`, `group_id`, `permission_id`, `value`) VALUES(29, 1, 27, 1);
INSERT INTO `usergroup_permissions` (`id`, `group_id`, `permission_id`, `value`) VALUES(30, 1, 28, 1);
INSERT INTO `usergroup_permissions` (`id`, `group_id`, `permission_id`, `value`) VALUES(31, 1, 29, 1);
INSERT INTO `usergroup_permissions` (`id`, `group_id`, `permission_id`, `value`) VALUES(32, 1, 30, 0);
INSERT INTO `usergroup_permissions` (`id`, `group_id`, `permission_id`, `value`) VALUES(33, 1, 31, 1);
INSERT INTO `usergroup_permissions` (`id`, `group_id`, `permission_id`, `value`) VALUES(34, 1, 35, 1);
INSERT INTO `usergroup_permissions` (`id`, `group_id`, `permission_id`, `value`) VALUES(36, 1, 43, 1);
INSERT INTO `usergroup_permissions` (`id`, `group_id`, `permission_id`, `value`) VALUES(37, 1, 46, 0);
INSERT INTO `usergroup_permissions` (`id`, `group_id`, `permission_id`, `value`) VALUES(38, 1, 47, 1);
INSERT INTO `usergroup_permissions` (`id`, `group_id`, `permission_id`, `value`) VALUES(39, 1, 48, 1);
INSERT INTO `usergroup_permissions` (`id`, `group_id`, `permission_id`, `value`) VALUES(40, 1, 49, 1);
INSERT INTO `usergroup_permissions` (`id`, `group_id`, `permission_id`, `value`) VALUES(41, 1, 42, 1);
INSERT INTO `usergroup_permissions` (`id`, `group_id`, `permission_id`, `value`) VALUES(42, 1, 44, 1);
INSERT INTO `usergroup_permissions` (`id`, `group_id`, `permission_id`, `value`) VALUES(43, 1, 45, 1);