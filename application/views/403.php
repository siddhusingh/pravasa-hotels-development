<!DOCTYPE html>
<html>
<head>
	<title>403 Forbidden - Access Restricted</title>
</head>
<body>


</body>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript">
	Swal.fire({
  icon: "error",
  title: "403 Forbidden - Access Restricted",
  text: "You don’t have the necessary permissions to access this page. If you believe you should have access, please contact your administrator.",
  footer: '<a href="<?php echo base_url("/") ?>">Go to Home page?</a>'
});
</script>

</html>