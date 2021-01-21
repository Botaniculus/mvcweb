<?php
class EmailSender
{
    public function send($target, $subject, $message, $sender){
      $header = "From: $sender";
      $header .= "\nMIME-Version: 1.0\n";
      $header .= "Content-Type: text/html; charset=\"utf-8\"\n";

      return mb_send_mail($target, $subject, $message, $header);
    }
}
