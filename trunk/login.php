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

include ('settings.php');

// Include the functions:
include('incl/functions.php');

// Open the database connection:
include('data/mysql_connect.php');

// Check authentication:
include('auth/auth.php');

// Include the header:
include('rend/header.html');

if(isset($_POST['username']) && isset($_POST['password']) && $_POST['username'] <> "" && $_POST['password'] <> ""){
	//Check user table for correct password
	print "<div style=\"width:90%; margin: 0px auto;\"><div>\n";
	$username = mysql_real_escape_string($_POST['username']);
	$password = md5(mysql_real_escape_string($_POST['password']));
	$query = "SELECT user.id AS userid, user.login AS username, usergroup.id AS usergroupid FROM user JOIN usergroup ON usergroup.id = user.group_id WHERE user.login = \"" . $username . "\" AND user.password = \"" . $password ."\" AND user.deleted = 0 LIMIT 1";
	if($result = mysql_query($query, $dbc)){
		$row = mysql_fetch_array($result);
		if(mysql_num_rows($result) == 1) {
			// Set the auth variables
			setuserid($row['userid']);
			setgroupid($row['usergroupid']);
			setauthname($row['username']);
			setauth(true);
			// Set the session variables
			$_SESSION['authname'] = getauthname();
			$_SESSION['groupid'] = getgroupid();
			$_SESSION['userid'] = getuserid();
			// redirect to index.php after 2.5s
			print "<script>window.setTimeout(function() { window.location.href = 'index.php'; }, 0);</script>\n";
			print "<div><a href=\"index.php\">Click here if not redirected</a>\n";
			print "<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />\n";
			print "</div>\n";
		} else {
			setauth(false);
			loginpage($dbc);
			print "<div class=\"alert\">Authentication failure</div>";
		}
		print "</div></div>\n";
	}
} else {
	print "<div style=\"width:90%; margin: 0px auto;\"><div>\n";
	loginpage($dbc);
	print "</div></div>\n";
}


// Include the footer.
include('rend/footer.html'); 

function loginpage($dbc) {
	// Show login screen form
	print "<div style=\"width:350px; margin: 0px auto;\"><br /><br /><br /><div class=\"login\">\n";
	print "<form action=\"login.php\" method=\"post\">\n";
	print "<div>User Name:<br /><input type=\"text\" name=\"username\" size=\"45\" /></div>\n";
	print "<div>Password:<br /><input type=\"password\" name=\"password\" size=\"45\" /></div><br />\n";
	print "<div><input class=\"loginbutton\" type=\"submit\" name=\"submit\" value=\"Login\" /></div>\n";
	print "</form>\n";
	$query = "SELECT user.id AS userid FROM user JOIN usergroup ON usergroup.id = user.group_id WHERE user.login = \"anonymous\" AND user.password = \"" . md5("anonymous") . "\" AND user.deleted = 0 LIMIT 1";
	if($result = mysql_query($query, $dbc)){
		$row = mysql_fetch_array($result);
		if(mysql_num_rows($result) == 1) {
			print "<form action=\"login.php\" method=\"post\">\n";
			print "<input type=\"hidden\" name=\"username\" value=\"anonymous\"/>\n";
			print "<input type=\"hidden\" name=\"password\" value=\"anonymous\"/>\n";
			print "<input class=\"loginbutton\" type=\"submit\" name=\"submit\" value=\"Guest access\" />\n";
			print "</form>\n";
		}
	}
	print "</div><br /><br /><br /></div>\n";
}

?>