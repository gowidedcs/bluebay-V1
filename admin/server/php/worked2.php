<?php

$MERCHANT_KEY = "BFC2kT2Y";
$SALT = "7Zn8KlnwVq";
$per_p ="1";
$surl = "http://localhost/GWT%20P1%20T2/server/php/success.php";
$furl = "http://localhost/GWT%20P1%20T2/server/php/failure.php";
$productinfo = "none";
// Merchant Key and Salt as provided by Payu.

$PAYU_BASE_URL = "https://sandboxsecure.payu.in";		// For Sandbox Mode
//$PAYU_BASE_URL = "https://secure.payu.in";			// For Production Mode

$action = '';
$posted = array();
if(!empty($_POST)) {
    //print_r($_POST);
  foreach($_POST as $key => $value) {    
    $posted[$key] = $value; 
  }
	$posted['amount'] = $posted['amount'] * $per_p;
}

$formError = 0;

if(empty($posted['txnid'])) {
  // Generate random transaction id
  $txnid = substr(hash('sha256', mt_rand() . microtime()), 0, 20);
} else {
  $txnid = $posted['txnid'];
}
$hash = '';
// Hash Sequence
$hashSequence = "key|txnid|amount|productinfo|firstname|email|udf1|udf2|udf3|udf4|udf5|udf6|udf7|udf8|udf9|udf10";
if(empty($posted['hash']) && sizeof($posted) > 0) {
  if(
          empty($posted['key'])
          || empty($posted['txnid'])
          || empty($posted['amount'])
          || empty($posted['firstname'])
          || empty($posted['email'])
          || empty($posted['phone'])
          || empty($posted['productinfo'])
          || empty($posted['surl'])
          || empty($posted['furl'])
		  || empty($posted['service_provider'])
  ) {
    $formError = 1;
  } else {
    //$posted['productinfo'] = json_encode(json_decode('[{"name":"tutionfee","description":"","value":"500","isRequired":"false"},{"name":"developmentfee","description":"monthly tution fee","value":"1500","isRequired":"false"}]'));
	$hashVarsSeq = explode('|', $hashSequence);
    $hash_string = '';	
	foreach($hashVarsSeq as $hash_var) {
      $hash_string .= isset($posted[$hash_var]) ? $posted[$hash_var] : '';
      $hash_string .= '|';
    }

    $hash_string .= $SALT;


    $hash = strtolower(hash('sha512', $hash_string));
    $action = $PAYU_BASE_URL . '/_payment';
  }
} elseif(!empty($posted['hash'])) {
  $hash = $posted['hash'];
  $action = $PAYU_BASE_URL . '/_payment';
}
?>

<html>
  <head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="../css/server_form.css">
  <script>
    var hash = '<?php echo $hash ?>';
    function submitPayuForm() {
      if(hash == '') {
        return;
      }
      var payuForm = document.forms.payuForm;
      payuForm.submit();
    }
  </script>
  </head>
  <body onload="submitPayuForm()">
    <center><h2>Payment Form</h2></center>
    <br/>
    <?php if($formError) { ?>
	
      <span style="color:red">Please fill all mandatory fields.</span>
      <br/>
      <br/>
    <?php } ?>
	  <div class="container">
	    <div class="jumbotron">
	<form action="<?php echo $action; ?>" method="post" name="payuForm">
      <input type="hidden" name="key" value="<?php echo $MERCHANT_KEY ?>" />
      <input type="hidden" name="hash" value="<?php echo $hash ?>"/>
      <input type="hidden" name="txnid" value="<?php echo $txnid ?>" />
	  <input type="hidden" name="surl" value="<?php echo $surl ?>" />
	  <input type="hidden" name="furl" value="<?php echo $furl ?>" />
	  <input type="hidden" name="productinfo" value="<?php echo $furl ?>" />
				  
      <table>
       <!-- <tr>
          <td><b>Mandatory Parameters</b></td>
        </tr>-->
        <tr>
          <td><b>Number of person:</b> </td>
          <td><input name="amount" class=" form-control" placeholder="Enter person in number" type = "number" value="<?php echo (empty($posted['amount'])) ? '' : $posted['amount'] ?>"/></td>
          <td><b>Name:</b> </td>
          <td><input name="firstname" placeholder="Enter Name" class=" form-control" id="firstname" value="<?php echo (empty($posted['firstname'])) ? '' : $posted['firstname']; ?>" /></td>
        </tr>
		 
        <tr>
          <td><b>Email:</b> </td>
          <td><input name="email" placeholder="Enter Email" class=" form-control" id="email" value="<?php echo (empty($posted['email'])) ? '' : $posted['email']; ?>" /></td>
          <td><b>Phone:</b> </td>
          <td><input name="phone" placeholder="Enter Phone Number" class=" form-control" value="<?php echo (empty($posted['phone'])) ? '' : $posted['phone']; ?>" /></td>
        </tr>
		 <!-- <tr>
          <td>Product Info: </td>
          <td colspan="3"><textarea name="productinfo" type="hidden" ><?php echo (empty($posted['productinfo'])) ? '' : $posted['productinfo'] ?></textarea></td>
        </tr>-->
		  <tr>
          <td colspan="3"><input type ="hidden" value="<?php echo (empty($posted['surl'])) ? '' : $posted['surl'] ?>" size="64" /></td>
        </tr>
        <tr>
          <td colspan="3"><input type="hidden" value="<?php echo (empty($posted['furl'])) ? '' : $posted['furl'] ?>" size="64" /></td>
        </tr>
        <tr>
          <td colspan="3"><input type="hidden" name="service_provider" value="payu_paisa" size="64" /></td>
        </tr>
       <tr>
          <?php if(!$hash) { ?>
            <td colspan="4"><input type="submit" class="btn btn-success" value="Submit" placeholder=""style="width: 100px; margin: 20 auto; margin-left:54%;"/></td>
          <?php } ?>
        </tr>
      </table>
				  <tr>Submitting selects our <a>terms and conditions.</a></tr><br/>
		           <button><a href="http://localhost/GWT%20P1%20T2">Home</a></button> 
    </form>
			 
		  </div>
	  </div>

  </body>	
</html>