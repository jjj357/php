<?xml version="1.0" encoding="utf-8" ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd" >
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US" >
   <!--Student Name: Mingtao Li
       Instructor Name: Peter Callaghan
       Course: INT322
       Section: A
       Assignment Number: 3
       Date: August 1,2011

       I declare with the submission of this assignment that:

       - only I wrote, tested, and debugged its contents.

       - I did not, have not, and will not disclose any of its contents using any means, electronic or non-electronic, to anyone other than the instructor named above.

       - I am aware of Seneca's Academic Policy and the consequences if I do not honour the above declarations or if I otherwise violate Seneca's academic policy. -->

	<head>
	   <title>Upload files and add data into database</title>
	</head>
	<body style='font-family:arial,san-serif; color: rgb(204,000,102); background-color: rgb(204,255,204)'>
	 <?php
	    //initiate some variables 
	    $_FILES['upfile']['error'] = 0;
	    $file = TRUE;
	    echo "  <h1>Upload files and add data into database</h1>\n";
	    //show the file upload form
	    echo '           <form enctype="multipart/form-data" action="upload.php" method="post">'."\n";
	    echo '               <p>Choose upload file: <input type="file" name="upfile" /><br /><br /><br />'."\n";
	    echo '                  <input type="submit" name="Upload" />'."\n";
	    echo "               </p>\n";
	    echo "           </form>\n";              
	    $filename = $_FILES['upfile']['name'];   
	    //if user clicked "submit" button, try to upload the file     
	    if(isset($_POST["Upload"])) { 
	     // if ($_FILES['upfile']['error'] !== 0) {
	     //   echo "Error uploading your file.\n";
	     //echo "Error code is ".$_FILES['upfile']['error']."\n";
	     // }
	     // else {
		 //if file name already exists, show error message
		 //if (file_exists($_FILES['upfile']['name'])) {
		 //   echo "<h4>Error! File name ".$_FILES["upfile"]["name"] . " already exists.</h4>\n";
		 //}
		 //else {
		    //move the uploaded files from temporary folder to the expected folder
		    move_uploaded_file($_FILES['upfile']['tmp_name'],$_FILES['upfile']['name']);
		    $tablename = "CITATION";
		    //get current date               
		    $date = date("Y-m-d");
		    $counter = 0;
		    $k = 0;
		    //$filename = $_FILES['upfile']['name'];
		    //echo "file name is $filename\n";
		    //open file for read
		    $file = fopen($filename,"r");
		    if($file === FALSE) {
		       echo "<h3>Error! Could not open file.</h3>\n";
		    }
		    else {
		       $line = fgets($file);
		       $i=0;
		       While (!feof($file)) {
			  //split the line data by the first colon
			  $str = preg_split('/: +/',$line,2);
			  $upperstr = trim(strtoupper($str[0]));
			  //get the value of several key fields
			  switch($upperstr) {
			      case 'DATABASE': $data_base = trim($str[1]);if (empty($data_base)) {$counter = $counter + 1;echo "Field \"Database\" CAN NOT be empty";} break;
			      case 'TITLE':    $title = trim($str[1]);if (empty($title)) {$counter = $counter + 1;echo "Field \"Title\" CAN NOT be empty";} break;                        
			      case 'AUTHOR':   $author[$i] = trim($str[1]);if (empty($author[$i])) {$counter = $counter +  1;echo "Field \"Author\" CAN NOT be empty";} $order[$i]=1+$i;$i++;break;
			      case 'ADD.AUTHOR / EDITOR': $author[$i] = trim($str[1]);if (empty($author[$i])) {$counter = $counter + 1;echo "Field \"Author\" CAN NOT be empty";} $order[$i]=1+$i;$i++;break;
			      case 'CITATION': $citation = trim($str[1]);if (empty($citation)) {$counter =$counter + 1;echo "Field \"Citation\" CAN NOT be empty";} break;
			      case 'YEAR':     $year = trim($str[1]);if (empty($year)) {$counter = $counter + 1;echo "Field \"Year\" CAN NOT be empty"; } break; 
			  }
			  $line = fgets($file);
		       }
		       fclose($file);
		       //if some files are empty,show error message
		       if ($counter !== 0) {
			  echo "counter is $counter\n";
			  echo "<h4>Some of the fields in your file is empty. Please check your file and upload again.</h4>\n";
		       }
		       else {
			  //connect to mySQL
			  $connection = mysql_connect("db-mysql","int322_112a20","ecJT7869");
			  if(!$connection) {
			     echo "<h3>Error! Unable to connect to database.</h3>\n";
			  }
			  else {
			     //if there is ' in a string, add a \ before '
			     $title0 = $title;
			     $citation0 = $citation;
			     $data_base0 = $data_base;
			     $title = preg_replace("/(\')/","\\\'",$title);
			     $citation = preg_replace("/(\')/","\\\'",$citation);
			     $data_base = preg_replace("/(\')/","\\\'",$data_base);
			     mysql_select_db("int322_112a20",$connection);
			     //use constraint UNIQUE to avoid repeated data entry into table
			    // $create = "CREATE TABLE ".$tablename." (AUTHOR VARCHAR(50) NOT NULL,ORDER_NO TINYINT UNSIGNED NOT NULL,TITLE VARCHAR(200) NOT NULL,DATABASE_SOURCE VARCHAR(100),CITATION VARCHAR(200) NOT NULL,PUBLICATION_YEAR YEAR NOT NULL,ENTRY_DATE DATE NOT NULL,CONSTRAINT citation_uk UNIQUE(AUTHOR,ORDER_NO,TITLE,DATABASE_SOURCE,CITATION,PUBLICATION_YEAR,ENTRY_DATE))";
			    // mysql_query($create,$connection);                                                                
			     for ($j=0;$j < $i;$j++) {
				$query = "INSERT INTO ".$tablename." VALUES ('$author[$j]',$order[$j],'$title','$data_base','$citation','$year','$date')";
				$queryresult = mysql_query($query,$connection);
				//if data is already input into the table, or some other error, show error message                                                                                                                                                                                                                                                  
				if($queryresult === FALSE) {                                                        
				   echo "<h4>Error! Either the data has already been added into the database, or something wrong with your query.</h4>\n";
				   $k =1 ;
				}
			     }
			     if ($k === 0) {
				exec("clear");
				echo "           <h2>Data entered successfully.</h2>\n";
				echo "           <hr />\n";
				echo "           <h3>Here is the data you uploaded:</h3>\n";
				for ($j=0; $j < $i; $j++) {
				   $mysearch = "";                                                                                       
				   //create the "WHERE" clause in mysql SELECT statement                                
				   $mysearch .= "UPPER(AUTHOR) = UPPER('$author[$j]')";                            
				   $mysearch .= " AND UPPER(TITLE) = UPPER('$title')";                               
				   $mysearch .= " AND UPPER(CITATION) = UPPER('$citation')";                                                                                                                                                                 
				   $mysearch .= " AND UPPER(DATABASE_SOURCE) = UPPER('$data_base')";                                                                                                                                   
				   $mysearch .= " AND PUBLICATION_YEAR = '$year'";                                                                                                                                                               
				   $display = "SELECT * FROM ".$tablename." WHERE ".$mysearch;                               
				   $displayresult = mysql_query($display,$connection);
				   if (!$displayresult) {
				      echo "Failed to show table contents\n";
				   }
				   $fields_no = mysql_num_fields($displayresult);
				   //display table column names
				   if ($j === 0) {
				      echo "           <table style='border:1px solid #808080; color:rgb(000,000,000);background-color:rgb(255,255,255)' >\n";
				      echo "              <tr>\n";
				      for ($k=0; $k < $fields_no; $k++) {
					 $field = mysql_fetch_field($displayresult);
					 echo "                 <th style='border:1px solid #808080'>{$field->name}</th>\n";
				      }
				      echo "              </tr>\n";
				   }
				   //display table data rows
				   echo "              <tr>\n";
				   echo "                 <td style='border:1px solid #808080'>$author[$j]</td>\n";
				   echo "                 <td style='border:1px solid #808080'>$order[$j]</td>\n"; 
				   echo "                 <td style='border:1px solid #808080'>$title0</td>\n"; 
				   echo "                 <td style='border:1px solid #808080'>$data_base0</td>\n";
				   echo "                 <td style='border:1px solid #808080'>$citation0</td>\n";
				   echo "                 <td style='border:1px solid #808080'>$year</td>\n";
				   echo "                 <td style='border:1px solid #808080'>$date</td>\n";
				   echo "              </tr>\n";
				}
				echo "           </table>\n";
				//release memory
				mysql_free_result($displayresult);
			     }
			  }                    
			  //close the connection to mySQL
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
