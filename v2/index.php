<?php
 
//visit www.corehackers.com to test it!!
// Address error handling.
 
ini_set('display_errors', 1);
error_reporting(E_ALL ^ E_NOTICE);
 
// Obtain POST data.
 
$trace_ip_addr = $_POST['trace_ip_addr']; // input
 
// Remove any slashes if Magic Quotes GPC is enabled.
 
if (get_magic_quotes_gpc())
    {
    $trace_ip_addr = stripslashes($trace_ip_addr);
    }
 
/******************************************************************************/
 
?>
 
 
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
 
<head>
<title>Trace</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta name="author" content="dr0n3" />
<style type="text/css">
 
body
    {
    margin: 0;
    padding: 10px;
    background-color: #ffffff;
    }
 
div.output
    {
    margin: 0;
    padding: 10px;
    background-color: #eeeeee;
    border-style: solid;
    border-width: 1px;
    border-color: #000000;
    }
 
</style>
</head>
 
<body>
<h1>Trace</h1>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<p><label for="trace_ip_addr">IP address:</label><br />
<input name="trace_ip_addr" id="trace_ip_addr" type="text" value="<?php echo $_POST['submit'] == 'Trace' ? htmlentities($trace_ip_addr, ENT_QUOTES) : '127.0.0.1'; ?>" size="40" maxlength="15" /></p>
<p><input type="submit" name="submit" value="Trace" /></p>
</form>
<p>Trace may take a while, please be patient.</p>
<?php
 
if ($_POST['submit'] == 'Trace') // Form has been submitted.
    {
    echo '<div class="output">' . "\n";
 
    if (strlen($trace_ip_addr) <= 15) // Form submission was not spoofed.
        {
        if (ereg('^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$', $trace_ip_addr)) // Acquired data contains no problems.
            {
            // Display result.
 
            echo '<pre>' . "\n" .
                 'traceroute ' . $trace_ip_addr . "\n\n";
 
            system('traceroute ' . $trace_ip_addr); // Trace IP address.
 
            echo '</pre>' . "\n" .
                 '<p>Trace complete.</p>' . "\n";
            }
        else // Acquired data contains problems!
            {
            echo '<p>Please enter a valid IP address.</p>' . "\n";
            }
        }
    else // Form submission was spoofed!
        {
        echo '<p>An illegal operation was encountered.</p>' . "\n";
        }
 
    echo '</div>' . "\n";
    }
 
?>
<!--http:/www.corehackers.com/ -->
</body>
 
</html>