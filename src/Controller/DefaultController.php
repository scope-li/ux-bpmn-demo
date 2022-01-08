<?php

namespace App\Controller;

use App\Kernel;
use Scopeli\UxBpmn\Model\Modeler;
use Scopeli\UxBpmn\Model\Viewer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    private Kernel $kernel;

    public function __construct(Kernel $kernel)
    {
        $this->kernel = $kernel;
    }

    #[Route('/', name: 'default_viewer', methods: 'GET')]
    public function index(): Response
    {
        $viewer = new Viewer(Viewer::TYPE_DEFAULT, $this->getBpmn());

        return $this->render('default/viewer.html.twig', [
            'viewer' => $viewer,
        ]);
    }

    #[Route('/navigated_viewer', name: 'navigated_viewer', methods: 'GET')]
    public function navigatedViewer(): Response
    {
        $viewer = new Viewer(Viewer::TYPE_NAVIGATED, $this->getBpmn());

        return $this->render('default/viewer.html.twig', [
            'viewer' => $viewer,
        ]);
    }

    #[Route('/default_viewer_with_flow', name: 'default_viewer_with_flow', methods: 'GET')]
    public function defaultViewerWithFlow(): Response
    {
        $viewer = new Viewer(Viewer::TYPE_DEFAULT, $this->getBpmn());
        $viewer->setConfig([
            'flow' => [
                'StartEvent_0765uhp',
                'Flow_0gljsub',
                'Activity_170vqw5',
                'Flow_1xnke4o',
                'Gateway_1tz1mjp',
                'Flow_1a4ve2i',
                'Activity_0sm72v7',
                'Flow_1nv3ae8',
                'Gateway_14vkk10',
                'Flow_14i83lm',
                'Activity_1o1p5cj',
            ],
            'flow_class' => 'highlight-flow',
            'current' => [
                'Activity_1o1p5cj',
                ],
            'current_class' => 'highlight-current',
        ]);

        return $this->render('default/viewer.html.twig', [
            'viewer' => $viewer,
        ]);
    }

    #[Route('/default_modeler', name: 'default_modeler', methods: 'GET')]
    public function defaultModeler(): Response
    {
        $modeler = new Modeler(Modeler::TYPE_DEFAULT, $this->getBpmn());
        $modeler->setConfig(['saveUrl' => $this->generateUrl('save')]);

        return $this->render('default/modeler.html.twig', [
            'modeler' => $modeler,
        ]);
    }

    #[Route('/default_modeler_without_buttons', name: 'default_modeler_without_buttons', methods: 'GET')]
    public function defaultModelerWithoutButtons(): Response
    {
        $modeler = new Modeler(Modeler::TYPE_DEFAULT, $this->getBpmn());
        $modeler->setConfig([
            'saveUrl' => null,
            'menu' => null
        ]);

        return $this->render('default/modeler.html.twig', [
            'modeler' => $modeler,
        ]);
    }

    #[Route('/camunda_modeler', name: 'camunda_modeler', methods: 'GET')]
    public function camundaModeler(): Response
    {
        $modeler = new Modeler(Modeler::TYPE_CAMUNDA, $this->getBpmn());
        $modeler->setConfig(['saveUrl' => $this->generateUrl('save')]);

        return $this->render('default/modeler.html.twig', [
            'modeler' => $modeler,
        ]);
    }

    #[Route('/save', name: 'save', methods: 'POST')]
    public function save(Request $request): Response
    {
        // Uncomment if you like to save.
        // $data = json_decode($request->getContent());
        // file_put_contents($this->getBpmnPath(), $data->xml);

        return new Response(null, Response::HTTP_NO_CONTENT);
    }

    private function getBpmn(): string
    {
        return file_get_contents($this->getBpmnPath());
    }

    private function getBpmnPath(): string
    {
        return sprintf('%s/bpmn/Example.bpmn', $this->kernel->getProjectDir());
    }
}
