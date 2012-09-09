<?php
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

// Include the settings
include ('settings.php');

// Include the functions:
include('incl/functions.php');

// Open the database connection:
include('data/mysql_connect.php');

// Check authentication:
include('auth/auth.php');

// Check permissions:
include('perm/perm.php');

// Include the header:
include('rend/header.html');

if(getperm('access')){
	// Get command and parameters from URI:
	$command = getcommand();
	
	if($command == "logout") {
		$_SESSION = array();
		session_destroy();
		print "<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />\n";
		print "<script>window.setTimeout(function() { window.location.href = 'index.php'; }, 0);</script>\n";
		setauth(false);
		mysql_close($dbc); // Close the connection.
		include('rend/footer.html'); // Include the footer.
		exit();
	}

	// Show the menu
	showmenu($dbc);

	if(getperm($command)) {
		switch ($command)
		{
			// Object related
			case 'listobjects':
				// Show object table
				listobjects($dbc);
				break;
			case 'viewobject':
				// Show object details
				viewobject($dbc);
				break;
			case 'addobject':
				// Add object
				addobject($dbc);
				break;
			case 'addmobject':
				// Add object
				addmobject($dbc);
				break;
			case 'editobject':
				// Edit object
				editobject($dbc);
				break;
			case 'deleteobjectproperty':
				// Delete object property
				deleteobjectproperty($dbc);
				break;
			case 'addobjectproperty':
				// Add object property
				addobjectproperty($dbc);
				break;
			case 'editobjectproperty':
				// Edit object property
				editobjectproperty($dbc);
				break;
			case 'storeobjectproperty':
				// Storee object property
				storeobjectproperty($dbc);
				break;
			case 'updateobjectproperty':
				// Update object property
				updateobjectproperty($dbc);
				break;
			case 'addobjectrelation':
				// Add object relation
				addobjectrelation($dbc);
				break;
			case 'deleteobjectrelation':
				// Delete object relation
				deleteobjectrelation($dbc);
				break;
			// Type related
			case 'listtypes':
				listtypes($dbc);
				break;
			case 'viewtype':
				viewtype($dbc);
				break;
			case 'edittype':
				edittype($dbc);
				break;
			case 'addtype':
				addtype($dbc);
				break;
			// Property related
			case 'listproperties':
				listproperties($dbc);
				break;
			case 'viewproperty':
				viewproperty($dbc);
				break;
			case 'editproperty':
				editproperty($dbc);
				break;
			case 'addproperty':
				addproperty($dbc);
				break;
			// Relation related
			case 'listrelations':
				listrelations($dbc);
				break;
			case 'viewrelation':
				viewrelation($dbc);
				break;
			case 'editrelation':
				editrelation($dbc);
				break;
			case 'addrelation':
				addrelation($dbc);
				break;
			// Reporting
			case 'reports':
				reports($dbc);
				break;
			case 'typereport':
				typereport($dbc);
				break;
			case 'propertyreport':
				propertyreport($dbc);
				break;
			case 'propertyallreport':
				propertyallreport($dbc);
				break;
			case 'propertyclassreport':
				propertyclassreport($dbc);
				break;
			case 'listusers':
				listusers($dbc);
				break;
			case 'adduser':
				adduser($dbc);
				break;
			case 'edituser':
				edituser($dbc);
				break;
			case 'listgroups':
				listgroups($dbc);
				break;
			case 'addgroup':
				addgroup($dbc);
				break;
			case 'editgroup':
				editgroup($dbc);
				break;
			case 'aboutpage':
				showabout();
				break;
			default:
				showsplash();
				break;
		}
	} else {
		showsplash();
		print "<p class=\"error\">Access denied</p>";
	}
} else {
	print "<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />\n";
	print "<script>window.setTimeout(function() { window.location.href = 'login.php'; }, 0);</script>\n";
}

//	print "\n<hr \>**************** BEGIN TEST AREA ****************<br \><br \>\n";
	
	
//	print "\n<br \><br \>***************** END TEST AREA *****************<br \>\n";
	


mysql_close($dbc); // Close the connection.

include('rend/footer.html'); // Include the footer.
?>