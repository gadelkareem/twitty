<?php
declare(strict_types=1);

namespace WonderKind\Controller;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\HttpFoundation\Response;
use WonderKind\Exception\TwitterException;
use WonderKind\Service\ReportingService;

/**
 * @package WonderKind\Controller
 */
class ReportingController extends AbstractController
{
    /**
     * @var ReportingService
     */
    private $reportService;


    /**
     * ReportingController constructor.
     * @param ReportingService $reportService
     */
    public function __construct(ReportingService $reportService)
    {
        $this->reportService = $reportService;
    }


    /**
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function index(Request $request): Response
    {
        $flashBag = $request->getSession()->getFlashBag();
        $form = $this->createFormBuilder()
            ->add('url', UrlType::class, ["label" => 'Tweet URL', "attr" => ["size" => 200]])
            ->add('submit', SubmitType::class)
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $url = $form->get('url')->getData();
            try {
                $totalFollowers = $this->reportService->CountReTweeterFollowers($url);
                $flashBag->add('success', "Found total of {$totalFollowers} followers to users who retweeted {$url}");
            } catch (TwitterException $e) {
                $flashBag->add("error", "Error communicating with Twitter, please try again after Trump stops.");
            }
        }

        return $this->render('index.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}