<?php
namespace MailPoetVendor\Egulias\EmailValidator\Warning;
if (!defined('ABSPATH')) exit;
class CFWSNearAt extends Warning
{
 const CODE = 49;
 public function __construct()
 {
 $this->message = "Deprecated folding white space near @";
 }
}
