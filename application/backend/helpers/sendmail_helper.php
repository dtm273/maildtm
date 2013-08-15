<?php
$ci =& get_instance();
$ci->load->helper('log');

if (!function_exists('alotaxi_send')) {

    function alotaxi_send($subject, $message) {
        $to = SUPPORT_MAIL_TO;
        $from = SUPPORT_MAIL_FROM;
        $header = "From: " . $from . "\nContent-Type: text/plain; charset=\"utf-8\"\nContent-Transfer-Encoding: 8bit";

        $result = mail($to, $subject, $message, $headers);
        
        if ($result) {
            write_log('MAIL_LOG', 'Gui mail toi ' . SUPPORT_MAIL_TO . ' thanh cong ');
        } else {
            write_log('MAIL_LOG', 'Co loi gui mail!');
        }
    }
}