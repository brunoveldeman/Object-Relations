<?php // perm [Permission layer]
/* Give users access permissions */
/* Legal Stuff

	This file is part of Object relations.

    Object Relations is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    Object relations is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Object Relations.  If not, see <http://www.gnu.org/licenses/>.
*/

$query = "SELECT usergroup_permissions.value AS usergrouppermissionsvalue, permissions.name AS permissionsname FROM usergroup_permissions JOIN permissions ON permissions.id = usergroup_permissions.permission_id WHERE  usergroup_permissions.group_id = " . getgroupid();
if ($result = mysql_query($query, $dbc)) { // Run the query.
	while ($row = mysql_fetch_array($result)) {
		$perm[$row['permissionsname']] = $row['usergrouppermissionsvalue'];
	}
}

// Grouped permissions
// Object properties
if(isset($perm['editobjectproperty'])){
	if($perm['editobjectproperty']){
		$perm['updateobjectproperty'] = true;
		$perm['storeobjectproperty'] = true;
	}
}
// Reports
if(isset($perm['reports'])){
	if($perm['reports']){
		$perm['typereport'] = true;
		$perm['propertyreport'] = true;
		$perm['propertyclassreport'] = true;
	}
}
// User management
if(isset($perm['usermanagement'])){
	if($perm['usermanagement']){
		$perm['listusers'] = true;
		$perm['adduser'] = true;
		$perm['edituser'] = true;
		$perm['listgroups'] = true;
		$perm['addgroup'] = true;
		$perm['editgroup'] = true;
	}
}
// About page
if(isset($perm['access'])){
	if($perm['access']){
		$perm['aboutpage'] = true;
	}
}
// Get user permissions from permissions table
/* The permission array*/

/*$perm = array(
	"access" => true,
    "listobjects" => true,
	"viewobject" => true,
    "addobject" => true,
    "editobject" => true,
    "deleteobject" => false, // Not implemented
	"listtypes" => true,
    "viewtype" => true,
    "addtype" => true,
    "edittype" => true,
    "deletetype" => false, // Not implemented
    "listproperties" => true,
    "viewproperty" => true,
    "addproperty" => true,
    "editproperty" => true,
    "deleteproperty" => false, // Not implemented
    "listrelations" => true,
    "viewrelation" => true,
    "addrelation" => true,
    "editrelation" => true,
    "deleterelation" => false, // Not implemented
    "viewobjecttype" => true,
    "editobjecttype" => true,
    "viewobjectproperty" => true,
    "addobjectproperty" => true,
    "editobjectproperty" => true,
    "deleteobjectproperty" => false,
    "viewobjectrelation" => true,
    "addobjectrelation" => true,
    "editobjectrelation" => true,
    "deleteobjectrelation" => false,
    "reports" => true,
    "usermanagement" => true,
    "listusers" => true,
    "edituser" => true,
    "adduser" => true,
    "listgroups" => true,
    "editgroup" => true,
    "addgroup" => true,
    "search" => true,
);*/

function getperm($permname) {
	if ($permname <> "") {
		global $perm;
		return($perm[$permname]);
	} else {
		return(true);
	}
}

/*
if ( $perm['deleteobject'] ) {
	print "OK";
} else {
	print "NOT OK";
}
*/

?>