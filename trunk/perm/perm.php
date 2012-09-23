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

// Get user permissions from permissions table

$query = "SELECT usergroup_permissions.value AS usergrouppermissionsvalue, permissions.name AS permissionsname FROM usergroup_permissions JOIN permissions ON permissions.id = usergroup_permissions.permission_id WHERE  usergroup_permissions.group_id = " . getgroupid();
if ($result = mysqli_query( $dbc, $query )) { // Run the query.
	while ($row = mysqli_fetch_assoc($result)) {
		$perm[$row['permissionsname']] = $row['usergrouppermissionsvalue'];
	}
}

// Grouped permissions
// Object properties
if(isset($perm['editobjectproperty'])){
	if($perm['editobjectproperty']){
		$perm['updateobjectproperty'] = true;
		$perm['storeobjectproperty'] = true;
		$perm['deleteobjectproperty'] = true;
	}
}
// Objects
if(isset($perm['addobject'])){
	if($perm['addobject']){
		$perm['addmobject'] = true;
	}
}

// Reports
if(isset($perm['reports'])){
	if($perm['reports']){
		$perm['typereport'] = true;
		$perm['propertyreport'] = true;
		$perm['propertyclassreport'] = true;
		$perm['issuetypereport'] = true;
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
// Issues
if(isset($perm['editissue'])){
	if($perm['editissue']){
		$perm['addissuemsg'] = true;
		$perm['editissuemsg'] = true;
		$perm['addobjectissue'] = true;
		$perm['deleteobjectissue'] = true;
	}
}
// Issue updates
if(isset($perm['addissuemsg'])){
	if($perm['addissuemsg']){
		$perm['addobjectissue'] = true;
		$perm['deleteobjectissue'] = true;
	}
}


function getperm($permname) {
	if ($permname <> "") {
		global $perm;
		if(isset($perm[$permname])) {
			return($perm[$permname]);
		} else {
			return(false);
		}
	} else {
		return(true);
	}
}


?>