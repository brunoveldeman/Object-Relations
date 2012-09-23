<?php // Auth [Authentication layer]
/* Make sure the user is correctly authenticated */
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

session_name('OR');
session_start();

/* Initialize variables */
$auth = false;
$authname = "";
$groupid = "";
$userid = "";
$username = "";
$sessiontimeout = 1800; // Timeout: 30*60 seconds
$sessionregen = 180; // Session ID regenerate time

/* Check if user has already loggen in */
if(isset($_SESSION['authname']) && isset($_SESSION['groupid']) && isset($_SESSION['userid']))
{
	// Get session data
	setauthname($_SESSION['authname']);
	// Get userid and usergroup from user and usergroup table
	$query = "SELECT user.id AS userid, user.login AS userlogin, user.name AS username, usergroup.id as usergroupid FROM user JOIN usergroup ON usergroup.id = user.group_id WHERE user.login = \"" . getauthname() . "\" AND user.deleted = 0 LIMIT 1";
	if($result = mysqli_query($dbc, $query))
	{
		$row = mysqli_fetch_assoc($result);
		if($row['usergroupid'] == $_SESSION['groupid'] && $row['userid'] == $_SESSION['userid']) 
		{
			setgroupid($row['usergroupid']);
			setuserid($row['userid']);
			setusername($row['username']);
			setauth(true);
			// Check for session timeout
			if(isset($_SESSION['start']) && (time() - $_SESSION['start'] > $sessiontimeout))
			{
    			session_destroy(); 
    			session_unset();
			}
			// Regenerate session ID every ... seconds
			if(isset($_SESSION['start']) && time() - $_SESSION['start'] > $sessionregen)
			{
    			session_regenerate_id(true);
    			$_SESSION['start'] = time();
			}
		} else
		{
			// Set user to anonymous and find a groupid and userid if it exists
			$query = "SELECT user.id AS userid, user.login AS userlogin, user.name AS username, usergroup.id as usergroupid FROM user JOIN usergroup ON usergroup.id = user.group_id WHERE user.login = \"anonymous\" AND user.deleted = 0 LIMIT 1";
			if($result = mysqli_query($dbc, $query))
			{
				$row = mysqli_fetch_assoc($result);
				setauthname("anonymous");
				setusername($row['username']);
				setgroupid($row['usergroupid']);
				setuserid($row['userid']);
				setauth(true);
			} else 
			{
				setauth(false);
			}
		}
	}
}

function setauth($bool) 
{
	global $auth;
	$auth = $bool;
}

function getauth() 
{
	global $auth;
	return($auth);
}

function setauthname($name) 
{
	global $authname;
	$authname = $name;
}

function getauthname() 
{
	global $authname;
	return($authname);
}

function setusername($name) 
{
	global $username;
	$username = $name;
}

function getusername() 
{
	global $username;
	return($username);
}

function setgroupid($id) 
{
	global $groupid;
	$groupid = $id;
}

function getgroupid() 
{
	global $groupid;
	return($groupid);
}

function setuserid($id) 
{
	global $userid;
	$userid = $id;
}

function getuserid() 
{
	global $userid;
	return($userid);
}
?>