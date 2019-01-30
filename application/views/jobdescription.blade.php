<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title> <?=(isset($jobs['type']) && $jobs['type']=='editedbyagency') ? $jobs['agency_edit_job']->job_description: $jobs->title;?></title>
</head>
<body>
	<div class="container">
		
		<span><?=(isset($jobs['type']) && $jobs['type']=='editedbyagency') ? $jobs['agency_edit_job']->job_description: $jobs->description;?></span>
	</div>
</body>
</html>