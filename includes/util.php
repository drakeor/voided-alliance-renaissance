<?php
/*
This file and all others are protected by
a GPL.  Please read the licence before
editing these files.

-Voided Alliance, Copyright Auburnflame Games
*/

/*
 * Utility routines for MySQL.
 */

class MySQL_class {
    var $db, $id, $result, $rows, $data, $a_rows;
    var $user, $pass, $host;

    /* Make sure you change the USERNAME and PASSWORD to your name and
     * password for the DB
     */

    function Setup ($user, $pass, $host, $db) {
        $this->user = $user;
        $this->pass = $pass;
        $this->host = $host;
        $this->db   = $db;
    }

    function Create ($db) {
        if (!$this->user) {
            # Set this to your default username
            $this->user = "USERNAME";
        }
        if (!$this->pass) {
            # Set this to your default password
            $this->pass = "PASSWORD";
        }
        if (!$this->host) {
            # Set this to your default database host
            $this->host = "localhost";
        }
        if (!$this->db && !$db) {
            # Set this to your default database
            $this->db = "DATABASE";
        } else {
            $this->db = $db;
	}

        $this->id = @mysql_pconnect($this->host, $this->user, $this->pass) or
            MySQL_ErrorMsg("Unable to connect to MySQL server: $this->host : '$SERVER_NAME'");
        $this->selectdb($this->db);
    }

    function SelectDB ($db) {
        @mysql_select_db($db, $this->id) or
            MySQL_ErrorMsg ("Unable to select database: $db");
    }

    # Use this function is the query will return multiple rows.  Use the Fetch
    # routine to loop through those rows.
    function Query ($query) {
        $this->result = @mysql_query($query, $this->id) or
            MySQL_ErrorMsg ("Unable to perform query: $query");
        $this->rows = @mysql_num_rows($this->result);
        $this->a_rows = @mysql_affected_rows($this->id);
    }

    # Use this function if the query will only return a
    # single data element.
    function QueryItem ($query) {
        $this->result = @mysql_query($query, $this->id) or
            MySQL_ErrorMsg ("Unable to perform query: $query");
        $this->rows = @mysql_num_rows($this->result);
        $this->a_rows = @mysql_affected_rows($this->id);
        $this->data = @mysql_fetch_array($this->result) or
            MySQL_ErrorMsg ("Unable to fetch data from query: $query");
        return($this->data[0]);
    }

    # This function is useful if the query will only return a
    # single row.
    function QueryRow ($query) {
        $this->result = @mysql_query($query, $this->id) or
            MySQL_ErrorMsg ("Unable to perform query: $query");
        $this->rows = @mysql_num_rows($this->result);
        $this->a_rows = @mysql_affected_rows($this->id);
        $this->data = @mysql_fetch_array($this->result) or
            MySQL_ErrorMsg ("Unable to fetch data from query: $query");
        return($this->data);
    }

    function Fetch ($row) {
        @mysql_data_seek($this->result, $row) or
            MySQL_ErrorMsg ("Unable to seek data row: $row");
        $this->data = @mysql_fetch_array($this->result) or
            MySQL_ErrorMsg ("Unable to fetch row: $row");
    }

    function Insert ($query) {
        $this->result = @mysql_query($query, $this->id) or
            MySQL_ErrorMsg ("Unable to perform insert: $query");
        $this->a_rows = @mysql_affected_rows($this->id);
    }

    function InsertID () {
        return mysql_insert_id();
    }

    function Update ($query) {
        $this->result = @mysql_query($query, $this->id) or
            MySQL_ErrorMsg ("Unable to perform update: $query");
        $this->a_rows = @mysql_affected_rows($this->id);
    }

    function Delete ($query) {
        $this->result = @mysql_query($query, $this->id) or
            MySQL_ErrorMsg ("Unable to perform Delete: $query");
        $this->a_rows = @mysql_affected_rows($this->id);
    }
}

/* ********************************************************************
 * MySQL_ErrorMsg
 *
 * Print out an MySQL error message
 *
 */

function MySQL_ErrorMsg ($msg) {
    # Close out a bunch of HTML constructs which might prevent
    # the HTML page from displaying the error text.
    echo("</ul></dl></ol>\n");
    echo("</table></script>\n");

    # Display the error message
    $text  = "<font color=\"#ff0000\" size=+2><p>Error: $msg :";
    $text .= mysql_error();
    $text .= "</font>\n";
    die($text);
}
?>