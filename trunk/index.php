<?php

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

// Get command and parameters from URI:
$command = getcommand();


// Show the menu
showmenu($dbc);
		
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
	default:
		showsplash();
		break;
}

//	print "\n<hr \>**************** BEGIN TEST AREA ****************<br \><br \>\n";
	
	
//	print "\n<br \><br \>***************** END TEST AREA *****************<br \>\n";
	


mysql_close($dbc); // Close the connection.

include('rend/footer.html'); // Include the footer.
?>