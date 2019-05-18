<?php

require_once __DIR__ . '/mailer/Validator.php';
require_once __DIR__ . '/mailer/ContactMailer.php';

if (!Validator::isAjax() || !Validator::isPost()) {
	echo 'Доступ запрещен!';
	exit;
}

$name = isset($_POST['name']) ? trim(strip_tags($_POST['name'])) : null;
$email = isset($_POST['email']) ? trim(strip_tags($_POST['email'])) : null;
$phone = isset($_POST['phone']) ? trim(strip_tags($_POST['phone'])) : null;
$message = isset($_POST['message']) ? trim(strip_tags($_POST['message'])) : null;

if (empty($name) || empty($email) || empty($phone) || empty($message)) {
	echo 'Веддiть даннi';
	exit;
}

if (!Validator::isValidEmail($email)) {
	echo 'не той формат';
	exit;
}

if (!Validator::isValidPhone($phone)) {
	echo 'не той формат';
	exit;
}

if (ContactMailer::send($name, $email, $phone, $message)) {
	echo htmlspecialchars($name) . ', ваше повiдомлення вiдправлено';
} else {
	echo 'Помилка';
}
exit;