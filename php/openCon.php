<?php

//Open connections to DB.
        $con = mysql_connect("localhost","root","");
        if (!$con)
        {
            die('Could not connect: ' . mysql_error());
            echo'<h1>Não ligou</h1>';
        }
        mysql_select_db("calika", $con);
                
        mysql_query("SET NAMES 'utf8'");
        mysql_query('SET character_set_connection=utf8');
        mysql_query('SET character_set_client=utf8');
        mysql_query('SET character_set_results=utf8');
        
?>
