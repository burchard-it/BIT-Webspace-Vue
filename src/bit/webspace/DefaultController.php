<?php

namespace App\bit\webspace;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

abstract class DefaultController extends AbstractController {

  public function __construct(protected LoggerInterface $logger) {}

}
