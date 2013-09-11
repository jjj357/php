<?xml version="1.0" encoding="utf-8" ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd" >
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US" >

	<head>
	   <title>Database Search</title>
	</head>
	<body style='font-family:arial,san-serif; color: #003300;background-color: #FFFFCC'>
	   <?php   
		   //if user didn't click "submit" button,show the database search form
		   if(!isset($_POST["Submit"])) {
		      //display a form for user to input 
		      echo "<h1>Database Search</h1>\n";
		      echo '           <form action="cite.php"  method="post" >'."\n";
		      echo "              <p>\n";
		      echo '                 Author (Last Name, First Name)(For example:Thompson, Laura A.): <input type="text" name="Author" maxlength="50" /><br /><br />' . "\n";
		      echo '                 Title: <input type="text" name="Title" maxlength="200" /><br /><br />' . "\n";
		      echo '                 Citation: <input type="text" name="Citation" maxlength="200" /><br /><br />' . "\n";
		      echo '                 Database: <input type="text" name="Data_base" maxlength="100" /><br /><br />' . "\n";
		      echo '                 Year: <input type="text" name="Year" maxlength="4" /><br /><br />' . "\n";
		      echo '                 <input type="submit" name="Submit" /><br /><br />' . "\n";
		      echo '                 <input type="reset" name="Reset" /><br /><br />' . "\n";
		      echo "              </p>\n";
		      echo "           </form>\n";
		   }
		   //if user click "submit" button,parse the input info and show it in the page
		   else {
		      exec("clear");
		      echo "<h1>Here is the search result:</h1>\n";
		      $mysearch = "";
		      $author = $_POST['Author'];
		      $title = $_POST['Title'];
		      $citation = $_POST['Citation'];
		      $data_base = $_POST['Data_base'];
		      $year = $_POST['Year'];
		      //create the "WHERE" clause in mysql SELECT statement
		      if( !empty($author)) {
			 $mysearch .= "upper(AUTHOR) = upper('$author')";
		      }
		      if( !empty($title)) {
			 if (!empty($mysearch)) {
			    $mysearch .= " AND ";
			 }
			 $mysearch .= "upper(TITLE) = upper('$title')";
		      }
		      if( !empty($citation)) {
			 if (!empty($mysearch)) {
			    $mysearch .= " AND ";
			 }
			 $mysearch .= "upper(CITATION) = upper('$citation')";
		      }
		      if( !empty($data_base)) {
			 if (!empty($mysearch)) {
			    $mysearch .= " AND ";
			 }
			 $mysearch .= "upper(DATABASE_SOURCE) = upper('$data_base')";
		      }
		      if( !empty($year)) {
			 if (!empty($mysearch)) {
			    $mysearch .= " AND ";
			 }
			 $mysearch .= "PUBLICATION_YEAR = '$year'";
		      }
		      $connection = mysql_connect("db-mysql","int322_112a20","ecJT7869");
		      if(!$connection) {
			 echo "<h3>unable to connect to database</h3>\n";
		      }
		      else {
			 mysql_select_db("int322_112a20");
			 //if user didn't specify search conditions,show all the data in the table,otherwise show the specified data
			 if ($mysearch === "") {
			    $query = "SELECT *  from CITATION ORDER BY ENTRY_DATE,DATABASE_SOURCE,CITATION,TITLE,ORDER_NO";
			 }
			 else {
			    $query = "SELECT *  from CITATION WHERE ".$mysearch." ORDER BY ENTRY_DATE,DATABASE_SOURCE,CITATION,TITLE,ORDER_NO";
			 }
			 
			 $result = mysql_query($query,$connection);
			 if($result === FALSE) {
			    echo "<h3>unable to run query: $query</h3>\n";
			 }
			 else {
			    $numRows = mysql_num_rows($result);
			    if($numRows === 0) {
			       echo "<p>No records match your query.</p>\n";
			    }
			    else {
			       //show search result in a table
			       echo "           <table style='color: #003300;background-color: #FFFFFF;border:1px solid #808080'>\n";
			       echo "              <tr>\n";
			       echo "                 <th style='border:1px solid #808080'>Author</th>\n";
			       echo "                 <th style='border:1px solid #808080'>Order_No</th>\n";
			       echo "                 <th style='border:1px solid #808080'>Title</th>\n";
			       echo "                 <th style='border:1px solid #808080'>Database_Source</th>\n";
			       echo "                 <th style='border:1px solid #808080'>Citation</th>\n";
			       echo "                 <th style='border:1px solid #808080'>Publication_Year</th>\n";
			       echo "                 <th style='border:1px solid #808080'>Entry_Date</th>\n";
			       echo "              </tr>\n";
			       while(($row = mysql_fetch_row($result))) {
				  echo "              <tr>\n";
				  foreach ($row as $cell) {
				     echo "                 <td style='border: 1px solid grey'>$cell</td>\n";
				  }
				  echo "              </tr>\n";
			       }
			       echo "           </table>\n";
			    }
			    mysql_close($connection);
			 }
		      }
		  }
		?>
	   <p>
	      <a href="http://validator.w3.org/check?uri=referer">
		 <img src="http://www.w3.org/Icons/valid-xhtml10" alt="Valid XHTML 1.0 Strict" height="31" width="88" />
	      </a>
	      <a href="http://jigsaw.w3.org/css-validator/check/referer">
		 <img style="border:0;width:88px;height:31px"
		 src="http://jigsaw.w3.org/css-validator/images/vcss-blue" alt="Valid CSS!" />
	      </a>
	   </p>
	</body>
</html>
