<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="de" lang="de">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="DC.contributor" content="WebKit Freiburg: Michael Wild">
	<title>PHP-Output</title>
</head>
<body>
	<p><?php echo htmlspecialchars($HTTP_RAW_POST_DATA); ?></p>
	<br/>
	<br/>
	<br/>
	<p>Nicht html-enkodiert:</p>
	<p><?php echo $HTTP_RAW_POST_DATA; ?></p>
</body>
</html>