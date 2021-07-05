<?php 

	/**
	 * 
	 */
	class ControlsClass 
	{
		public function getControlId($seqid)
		{
			include('config/dbconn.php');

			$controlid='';
			$sequence='';
			$query = "SELECT * FROM numbersequence where sequenceid = '".$seqid."'";
			$result = $conn->query($query);
			$row = $result->fetch_assoc();
			$prefix = $row["prefix"];
			$first = $row["first"];
			$last = $row["last"];
			$format = $row["format"];
			$next = $row["next"];
			$suffix = $row["suffix"];
			 if($last >= $next)
			 {
			 	$sequence = $prefix.substr($format,0,strlen($next)*-1).$next.$suffix;
			 }
			 else if ($last < $next)
			 {
			 	$sequence = $prefix.$next.$suffix;
			 }
			$increment=$next+1;
			 $sql = "UPDATE numbersequence SET
						next = '$increment'
						where sequenceid = '".$seqid."'
						";
				if(mysqli_query($conn,$sql))
				{
					$controlid .= $sequence;
				}
				else
				{
					$controlid .= "error".$sql."<br>".$conn->error;
				}

			return $controlid;
		}
		
	}

?>