<html>
<head>
	<style type="text/css">
		table, th, td {
		    border: 1px solid black;
		}
		table {
		    border-collapse: collapse;
		}
	</style>
</head>
<body>
<h3><b>A spam activity has been detected from username "</b><?php echo $viewData['username'] ?>"</h3>
<table cellpadding="2">
	<tr>
		<td width="150"></td>
		<td></td>
	</tr>
	<tr>
		<td>Message Content</td>
		<td><?php echo $viewData['post'] ?></td>
	</tr>
	<tr>
		<td>Filtered Link</td>
		<td><?php echo $viewData['filtered'][0] ?></td>
	</tr>
	<tr>
		<td>User Ip Address</td>
		<td><?php echo $viewData['ipAddress'] ?></td>
	</tr>
	<tr>
		<td>Date-time of Incident</td>
		<td><?php echo $viewData['incidentDate'] ?></td>
	</tr>
</table>
<h3><b>Username "</b><?php echo $viewData['username'] ?>" has also been disabled.</h3>

</body>
</html>