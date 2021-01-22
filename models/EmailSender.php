<?php
class EmailSender
{
    public function send($target, $subject, $message, $sender){
      $header = "From: $sender";
      $header .= "\nMIME-Version: 1.0\n";
      $header .= "Content-Type: text/html; charset=\"utf-8\"\n";

      if(!mb_send_mail($target, $subject, $message, $header))
        throw new UserException("Email se nepodařilo odeslat.");
    }
    public function sendWithAntispam($year, $target, $subject, $message, $sender){
      if($year != date('Y'))
        throw new UserException("Špatně vyplněný antispam.");
      $this->send($target, $subject, $message, $sender);
    }
}
