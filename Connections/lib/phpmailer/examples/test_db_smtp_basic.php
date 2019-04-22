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
<title>PHPMailer - MySQL Database - SMTP basic test with authentication</title>
</head>
<body>

<?php

//error_reporting(E_ALL);
error_reporting(E_STRICT);

date_default_timezone_set('America/Toronto');

require_once('../class.phpmailer.php');
//include("class.smtp.php"); // optional, gets called from within class.phpmailer.php if not already loaded

$mail                = new PHPMailer();

$body                = file_get_contents('contents.html');
$body                = eregi_replace("[\]",'',$body);

$mail->IsSMTP(); // telling the class to use SMTP
$mail->Host          = "smtp1.site.com;smtp2.site.com";
$mail->SMTPAuth      = true;                  // enable SMTP authentication
$mail->SMTPKeepAlive = true;                  // SMTP connection will not close after each email sent
$mail->Host          = "mail.yourdomain.com"; // sets the SMTP server
$mail->Port          = 26;                    // set the SMTP port for the GMAIL server
$mail->Username      = "yourname@yourdomain"; // SMTP account username
$mail->Password      = "yourpassword";        // SMTP account password
$mail->SetFrom('list@mydomain.com', 'List manager');
$mail->AddReplyTo('list@mydomain.com', 'List manager');

$mail->Subject       = "PHPMailer Test Subject via smtp, basic with authentication";

@MYSQL_CONNECT("localhost","root","password");
@mysql_select_db("my_company");
$query  = "SELECT full_name, email, photo FROM employee WHERE id=$id";
$result = @MYSQL_QUERY($query);

while ($row = mysql_fetch_array ($result)) {
  $mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
  $mail->MsgHTML($body);
  $mail->AddAddress($row["email"], $row["full_name"]);
  $mail->AddStringAttachment($row["photo"], "YourPhoto.jpg");

  if(!$mail->Send()) {
    echo "Mailer Error (" . str_replace("@", "&#64;", $row["email"]) . ') ' . $mail->ErrorInfo . '<br />';
  } else {
    echo "Message sent to :" . $row["full_name"] . ' (' . str_replace("@", "&#64;", $row["email"]) . ')<br />';
  }
  // Clear all addresses and attachments for next loop
  $mail->ClearAddresses();
  $mail->ClearAttachments();
}
?>

</body>
</html>