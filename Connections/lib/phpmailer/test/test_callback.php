<?php 
/**
 * 123PHPSHOP
 * ============================================================================
 * 版权所有 2015~2019 上海序程信息科技有限公司，并保留所有权利。
 * 网站地址: http://www.123PHPSHOP.com；
 * ----------------------------------------------------------------------------
 * 这是一个免费的软件。您可以在商业目的和非商业目的地前提下对程序除本声明之外的
 * 代码进行修改和使用；您可以对程序代码进行再发布，但一定请保留
 * 本声明和上海序程信息科技有限公司的联系方式！本软件中使用到的第三方代码版权属
 * 于原公司所有。上海序程信息科技有限公司拥有对本声明和123PHPSHOP软件使用的最终
 * 解释权！
 * ============================================================================
 *  作者:	123PHPSHOP团队
 *  手机:	13391334121
 *  邮箱:	service@123phpshop.com
 */
 ?><html>
<head>
<title>PHPMailer Lite - DKIM and Callback Function test</title>
</head>
<body>

<?php
/* This is a sample callback function for PHPMailer Lite.
 * This callback function will echo the results of PHPMailer processing.
 */

/* Callback (action) function
 *   bool    $result        result of the send action
 *   string  $to            email address of the recipient
 *   string  $cc            cc email addresses
 *   string  $bcc           bcc email addresses
 *   string  $subject       the subject
 *   string  $body          the email body
 * @return boolean
 */
function callbackAction ($result, $to, $cc, $bcc, $subject, $body) {
  /*
  this callback example echos the results to the screen - implement to
  post to databases, build CSV log files, etc., with minor changes
  */
  $to  = cleanEmails($to,'to');
  $cc  = cleanEmails($cc[0],'cc');
  $bcc = cleanEmails($bcc[0],'cc');
  echo $result . "\tTo: "  . $to['Name'] . "\tTo: "  . $to['Email'] . "\tCc: "  . $cc['Name'] . "\tCc: "  . $cc['Email'] . "\tBcc: "  . $bcc['Name'] . "\tBcc: "  . $bcc['Email'] . "\t"  . $subject . "<br />\n";
  return true;
}

$testLite = false;

if ($testLite) {
  require_once '../class.phpmailer-lite.php';
  $mail = new PHPMailerLite();
} else {
  require_once '../class.phpmailer.php';
  $mail = new PHPMailer();
}

try {
  $mail->IsMail(); // telling the class to use SMTP
  $mail->SetFrom('you@yourdomain.com', 'Your Name');
  $mail->AddAddress('another@yourdomain.com', 'John Doe');
  $mail->Subject = 'PHPMailer Lite Test Subject via Mail()';
  $mail->AltBody = 'To view the message, please use an HTML compatible email viewer!'; // optional - MsgHTML will create an alternate automatically
  $mail->MsgHTML(file_get_contents('contents.html'));
  $mail->AddAttachment('images/phpmailer.gif');      // attachment
  $mail->AddAttachment('images/phpmailer_mini.gif'); // attachment
  $mail->action_function = 'callbackAction';
  $mail->Send();
  echo "Message Sent OK</p>\n";
} catch (phpmailerException $e) {
  echo $e->errorMessage(); //Pretty error messages from PHPMailer
} catch (Exception $e) {
  echo $e->getMessage(); //Boring error messages from anything else!
}

function cleanEmails($str,$type) {
  if ($type == 'cc') {
    $addy['Email'] = $str[0];
    $addy['Name']  = $str[1];
    return $addy;
  }
  if (!strstr($str, ' <')) {
    $addy['Name']  = '';
    $addy['Email'] = $addy;
    return $addy;
  }
  $addyArr = explode(' <', $str);
  if (substr($addyArr[1],-1) == '>') {
    $addyArr[1] = substr($addyArr[1],0,-1);
  }
  $addy['Name']  = $addyArr[0];
  $addy['Email'] = $addyArr[1];
  $addy['Email'] = str_replace('@', '&#64;', $addy['Email']);
  return $addy;
}

?>
</body>
</html>