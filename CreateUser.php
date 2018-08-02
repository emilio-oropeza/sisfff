<?php
include_once(dirname(__FILE__)."/config.inc.php");
include_once(dirname(__FILE__)."/libraries/phpmailer/class.phpmailer.php");
include_once(dirname(__FILE__)."/libraries/phpmailer/class.smtp.php");
/*include_once("./config.inc.php");
include_once("./libraries/phpmailer/class.phpmailer.php");
include_once("./libraries/phpmailer/class.smtp.php");*/

//$mail = "emilio.oropeza.palacios@gmail.com";
$mail = "innovationawards@fff.fr";

$json = file_get_contents('php://input');
$respond = json_decode($json);

$company = $respond->company;
$year = $respond->year;
$website = $respond->website;
$name = $respond->name;
$position = $respond->position;


$phpmail = new PHPMailer(true);
$phpmail->isSendmail();

if(!empty($_smtpHost) && !empty($_smtpPort) && !empty($_smtpUser) && !empty($_smtpPass) && !empty($_smtpSecure)){
    $phpmail->IsSMTP();
    $phpmail->Host = $_smtpHost; 
    $phpmail->Port = $_smtpPort;
    $phpmail->Username = $_smtpUser;
    $phpmail->Password = $_smtpPass;
    $phpmail->SMTPAuth = $_smtpAuth;
    $phpmail->SMTPSecure = $_smtpSecure;
}
try {
    $phpmail->setFrom($_mailNoReplay, $_nameNoReplay);
    $phpmail->addAddress($mail, $name." ".$company);

    $phpmail->Subject = "Company ".$company;

    $html = preg_replace('/[\\\\]/i', "", file_get_contents(dirname(__FILE__)."/template/register.html"));
    $html = str_replace(
        array("{company}", "{year}", "{website}", "{name}", "{position}"),
        array($company, $year, $website, $name, $position),
        $html
    );

    $phpmail->isHTML(true); 
    //$phpmail->CharSet = 'UTF-8';
    $phpmail->msgHTML($html);
    $phpmail->AltBody = strip_tags($html);

    if(!@$phpmail->send()){
        echo 'Error '.$phpmail->ErrorInfo;
    }else{
        echo 'Send';
    }
} catch (Exception $e) {
    echo $e->getMessage();
}
?>