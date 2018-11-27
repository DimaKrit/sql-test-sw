<?php

$hostname = "localhost";
$username = "root";
$password = "";
$dbname = "swivl";

$mysqli = new mysqli($hostname, $username, $password, $dbname);

if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

/* a. Return the name of the company and its phone */

$queryOneA = "SELECT 
				f.name , COALESCE(p.phone, ' ') AS `phone`  
	  		FROM 
	  			firms AS f 
	  		LEFT JOIN 
	  			phones AS p 
  			ON 
  				f.id = p.firm_id 
			GROUP BY 
				f.name";

$resultOneA = $mysqli->query($queryOneA);

$rowOneA = $resultOneA->fetch_all();

print_r($rowOneA);

/* b. Return all firms that do not have phones. */

$queryOneB = "SELECT 
				f.name, p.phone 
			FROM 
				firms AS f 
			LEFT JOIN 
				phones AS p 
			ON 
				f.id = p.firm_id 
			WHERE 
				p.phone IS NULL 
			GROUP BY 
				f.name";

$resultOneB = $mysqli->query($queryOneB);

$rowOneB = $resultOneB->fetch_all();

print_r($rowOneB);


/* c. Return all firms with at least 2 phones. */

$queryOneC = "SELECT 
				f.name, p.phone 
			FROM 
				firms AS f 
			INNER JOIN 
				phones AS p 
			ON 
				f.id = p.firm_id 
			GROUP BY 
				f.name 
			HAVING 
				COUNT(p.phone) > 1";

$resultOneC = $mysqli->query($queryOneC);

$rowOneC = $resultOneC->fetch_all();

print_r($rowOneC);

/* d. Return all firms with less than 2 phones. */

$queryOneD = "SELECT 
				f.name, p.phone 
			FROM 
				firms AS f 
			INNER JOIN 
				phones AS p ON f.id = p.firm_id 
			GROUP BY 
				f.name 
			HAVING 
				COUNT(p.phone) < 2";

$resultOneD = $mysqli->query($queryOneD);

$rowOneD = $resultOneD->fetch_all();

print_r($rowOneD);

/* e. Return the company with the maximum number of phones. */

$queryOnE = "SELECT 
				f.name, p.phone  
			FROM 
				firms AS f 
			INNER JOIN 
				phones AS p 
			ON 
				f.id = p.firm_id 
			GROUP BY 
				p.firm_id 
			HAVING 
				COUNT(p.firm_id) 
			LIMIT 1";

$resultOnE = $mysqli->query($queryOnE);

$rowOnE = $resultOnE->fetch_all();

print_r($rowOnE);

/* a. Print the total supply of each product for each firm, indicating dates of last delivery */

$queryTwoA = "SELECT 
				c.name, g.name, SUM(s.quantity) AS `quantity`, MAX(s.shipdate) AS `shipdate`
			FROM 
				company AS c 
			LEFT JOIN 
				shipment AS s 
			ON 
				c.compid = s.compid
			LEFT JOIN 
				goods AS g 
			ON 
				g.goodid = s.goodid
			WHERE 
				s.quantity IS NOT NULL
			GROUP BY 
				c.name, g.name";

$resultTwoA = $mysqli->query($queryTwoA);

$rowTwoA = $resultTwoA->fetch_all();

print_r($rowTwoA);

/* b. Similar to the previous paragraph, but in the last 50 days. If supplies
      any of the goods for the company in this period were absent, withdraw in
      volume column 'No data' */

$queryTwoB = "SELECT 
				c.name, g.name, IFNULL(SUM(s.quantity),'No data') AS `quantity`, MAX(s.shipdate) AS `shipdate`
			FROM 
				company AS c 
			LEFT JOIN 
				shipment AS s 
			ON 
				c.compid = s.compid
			LEFT JOIN 
				goods AS g 
			ON 
				g.goodid = s.goodid
			WHERE 
				s.shipdate > DATE_SUB(CURDATE(), INTERVAL 50 DAY)
			GROUP BY 
				c.name, g.name";

$resultTwoB = $mysqli->query($queryTwoB);

$rowTwoB = $resultTwoB->fetch_all();

print_r($rowTwoB);




