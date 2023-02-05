<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends DefaultController {

  #[Route('/{path<.+>}', name: 'homepage',
    requirements: ['path' => '^(?!api\/|.+\.png\/?$|.+\.ico\/?$|.+\.xml\/?$|.+\.php).+'])]
  public function indexAction($path = ''): Response {
    $this->logger->debug('Here I am', [
      'path' => $path,
    ]);
    return $this->render('base.html.twig');
  }
}
