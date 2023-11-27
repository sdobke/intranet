<?PHP

define('TEMPLATE_FILE', 'template.html');

// initalize
$pollHeader = "This is poll heading";
$question = "Which team will win World Cup Soccer 2006 ?";
$options  = array("Brazil", "England", "France", "Germany");


require_once('AjaxPoll.inc.php');

$ajaxPoll = new AjaxPoll(TEMPLATE_FILE);

$ajaxPoll->tag('header', $pollHeader);
$ajaxPoll->tag('question', $question);
$ajaxPoll->tag('options', $options);

echo $ajaxPoll->write();
?>