<?php
namespace MailPoetVendor\Twig\TokenParser;
if (!defined('ABSPATH')) exit;
use MailPoetVendor\Twig\Error\SyntaxError;
use MailPoetVendor\Twig\Node\Node;
use MailPoetVendor\Twig\Parser;
use MailPoetVendor\Twig\Token;
interface TokenParserInterface
{
 public function setParser(Parser $parser);
 public function parse(Token $token);
 public function getTag();
}
\class_alias('MailPoetVendor\\Twig\\TokenParser\\TokenParserInterface', 'MailPoetVendor\\Twig_TokenParserInterface');
// Ensure that the aliased name is loaded to keep BC for classes implementing the typehint with the old aliased name.
\class_exists('MailPoetVendor\\Twig\\Token');
\class_exists('MailPoetVendor\\Twig\\Parser');
