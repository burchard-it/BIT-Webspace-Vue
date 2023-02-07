<?php

namespace App\bit\webspace;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends DefaultController {

  #[Route('/{path<.+>}', name: 'homepage', requirements: ['path' => '^(?!api\/|admin|.+\.xml\/?$|.+\.php).+'])]
  public function indexAction($path = ''): Response {
    $this->logger->debug('Here I am', [
      'path' => $path,
    ]);
    return $this->render('main.html.twig');
  }

  #[Route('/admin/{path<.+>}', name: 'adminArea')]
  public function admin($path = ''): Response {
    $this->logger->debug('Here I am', [
      'path' => $path,
    ]);
    return $this->render('admin.html.twig');
  }
}
