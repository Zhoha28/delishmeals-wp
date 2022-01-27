<?php
namespace MailPoetVendor;
if (!defined('ABSPATH')) exit;
class Swift_Mime_ContentEncoder_NullContentEncoder implements Swift_Mime_ContentEncoder
{
 private $name;
 public function __construct($name)
 {
 $this->name = $name;
 }
 public function encodeString($string, $firstLineOffset = 0, $maxLineLength = 0)
 {
 return $string;
 }
 public function encodeByteStream(Swift_OutputByteStream $os, Swift_InputByteStream $is, $firstLineOffset = 0, $maxLineLength = 0)
 {
 while (\false !== ($bytes = $os->read(8192))) {
 $is->write($bytes);
 }
 }
 public function getName()
 {
 return $this->name;
 }
 public function charsetChanged($charset)
 {
 }
}
