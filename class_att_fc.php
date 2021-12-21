<?php
session_start();
	require "php/config.php";
	require_once "php/functions.php";
	$user = new login_registration_class();
	$fid = $_SESSION['f_id'];
	$funame = $_SESSION['f_uname'];
	$fname = $_SESSION['f_name'];
	if(!$user->get_faculty_session()){
		header('Location: facultylogin.php');
		exit();
	}
?>	
<?php 
$pageTitle = "All Exam Details";
include "php/headertop.php";
?>
<div class="all_student fix">
		<h3 style="text-align:center;color:#fff;margin:0;padding:5px;background:green"> Exams Submited</h3>
		
		<table class="tab_one" style="text-align:center;">
		<tr>
				<th style="text-align:center;">#</th>
				<th style="text-align:center;">Student Name</th>
				<th style="text-align:center;">Exam Name</th>
				<th style="text-align:center;">Upload</th>
				<th style="text-align:center;">Result</th>
				<th style="text-align:center;">Plagiarism</th>
				<th style="text-align:center;">Action</th>
				
			</tr>
			
				<?php
				$i=0; 
				
				$q = "SELECT * FROM Uploads";
				$r = $conn->query($q);
				while($rows = $r->fetch_assoc()){
				$i++;

				 ?>
			<tr>
				<td><?php echo $i;?></td>
				<td><?php echo $rows['sid'];?></td>
				<td><?php echo $rows['examname'];?></td>
				<td><i class="fa fa-file-text"></i> <?php echo $rows['filename'];?></td>
				<td>Not Graded</td>
				<td><?php echo $rows['plagiarism'];?>%</td>
				<td>
					<a href="readword.php?path=<?php echo $rows['spath'];?>"><button>Check <i class="fa fa-arrow-right"></i></button></a></td>
			</tr>
		<?php }?>
	
	
		</table>

		
</div>
<?php include "php/footerbottom.php";?>
<?php ob_end_flush() ; ?>