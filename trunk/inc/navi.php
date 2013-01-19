<div id='header'>
<?php
$http_host = getenv('HTTP_HOST');
print "<a href=\"http://$http_host/index.php\">Home</a>";
if (!checkLogin())
{
print "<a href=\"http://$http_host/register/login.php\">Login</a>";
}
else
{
print "<a href=\"http://$http_host/register/logout.php\">Logout</a> <a href=\"http://$http_host/register/index.php\">Status</a>";
}
print "<a href=\"https://$http_host/products.php\">Products</a>";
print "<a href=\"http://$http_host/help.php\">Help</a>";
?>
</div>
