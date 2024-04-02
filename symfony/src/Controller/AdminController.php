<?php

namespace App\Controller;

use App\Entity\Module;
use App\Entity\Webinar;
use App\Entity\Panelist;
use App\Form\WebinarType;
use App\Service\ZoomTokenService;
use App\Repository\WebinarRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    #[Route('/admin/webinar-list', name: 'webinar-list')]
    public function webinarList(WebinarRepository $webinars): Response
    {
        return $this->render('admin/webinar-list.html.twig', [
            'webinars' => $webinars->findAll(),
        ]);
    }

    #[Route('/admin/add', name: 'add')]
    public function add(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(WebinarType::class, new Webinar());

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newWebinar = $form->getData();
            $bannerFile = $form->get('banner')->getData();
            if ($bannerFile) {
                $originalFileName = pathinfo(
                    $bannerFile->getClientOriginalName(),
                    PATHINFO_FILENAME
                );
                $safeFileName = $slugger->slug($originalFileName);
                $newFileName = $safeFileName . '-' . uniqid() . '.' . $bannerFile->guessExtension();
            }

            if ($bannerFile) {
                try {
                    $bannerFile->move(
                        $this->getParameter('banners_directory'),
                        $newFileName
                    );
                    $newWebinar->setBanner($newFileName);
                } catch (FileException $e) {
                }
            }

            $entityManager->persist($newWebinar);
            $entityManager->flush();

            $this->addFlash('success', 'Webinar has been added to the database.');
            return $this->redirectToRoute('admin');
        }

        return $this->render('admin/add.html.twig', [
            'form' => $form,
            'title' => 'Add Webinar'
        ]);
    }


    #[Route('/admin/{webinar}/edit', name: 'edit')]
    public function edit(Webinar $webinar, Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(WebinarType::class, $webinar);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bannerFile = $form->get('banner')->getData();
            if ($bannerFile) {
                $originalFileName = pathinfo($bannerFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFileName = $slugger->slug($originalFileName);
                $newFileName = $safeFileName . '-' . uniqid() . '.' . $bannerFile->guessExtension();

                try {
                    $bannerFile->move($this->getParameter('banners_directory'), $newFileName);
                    $webinar->setBanner($newFileName); // Update the banner filename in the webinar entity
                } catch (FileException $e) {
                }
            }

            $entityManager->flush();

            $this->addFlash('success', 'Webinar has been updated successfully.');
            return $this->redirectToRoute('webinar-list'); 
        }

        return $this->render('admin/edit.html.twig', [
            'form' => $form,
            'title' => 'Edit Webinar'
        ]);
    }

    
    #[Route('/admin/generate-webinar{id}', name: 'generate-webinar', methods: ['POST'])]
    public function generateWebinar(int $id, WebinarRepository $webinarRepo, Request $request, HttpClientInterface $httpClient, ZoomTokenService $zoomTokenService, EntityManagerInterface $entityManager): Response
    {
        $webinar = $webinarRepo->find($id);

        if (!$webinar) {
            $this->addFlash('error', 'Webinar not found.');
            return $this->redirectToRoute('webinar-list');
        }

        // Define the Redis connection and fetch the access token
        $accessToken = $zoomTokenService->getValidAccessToken();

        if (!$accessToken) {
            return new Response('Failed to retrieve access token.', 500);
        }

        $panelistsNames = $webinar->getPanelists()->map(function ($panelist) {
            return $panelist->getName();
        })->toArray();

        $panelistsString = implode("\n", $panelistsNames);

        $moduleIds = array_map(function ($module) {
            return $module->getId();
        }, $webinar->getModules()->toArray());
        if (in_array(1, $moduleIds)) {
            $templateId = 'Cl1UF39YRD6ot6dlwIV9Zg';
        } elseif (in_array(2, $moduleIds)) {
            $templateId = 'ZhPJDRKGSrOj_gW-0FRdBQ';
        } else {
            $templateId = null;
        }


        $requestBody = [
            "agenda" =>
            $webinar->getOrganization() . "\n" . $panelistsString,
            "duration" => $webinar->getDuration() ? $webinar->getDuration() : 45, //condition ? value_if_true : value_if_false;
            "settings" => [
                "allow_multiple_devices" => true,
                "approval_type" => 0,
                "attendees_and_panelists_reminder_email_notification" => [
                    "enable" => true,
                    "type" => 3
                ],
                "audio" => "voip",
                "auto_recording" => "cloud",
                "contact_email" => "prezentace@poznejpovolani.cz",
                "contact_name" => "PoznejPovolání, z. s.",
                "email_language" => "en-US",
                "follow_up_absentees_email_notification" => [
                    "enable" => true,
                    "type" => 1
                ],
                "follow_up_attendees_email_notification" => [
                    "enable" => true,
                    "type" => 1
                ],
                "host_video" => true,
                "panelist_authentication" => false,
                "meeting_authentication" => false,
                "add_watermark" => false,
                "on_demand" => true,
                "panelists_invitation_email_notification" => true,
                "panelists_video" => true,
                "practice_session" => true,
                "question_and_answer" => [
                    "allow_submit_questions" => true,
                    "allow_anonymous_questions" => true,
                    "answer_questions" => "all",
                    "attendees_can_comment" => true,
                    "attendees_can_upvote" => true,
                    "enable" => true
                ],
                "registrants_email_notification" => false,
                "registrants_restrict_number" => 0,
                "show_share_button" => false
            ],
            "start_time" => $webinar->getDate()->format('Y-m-d') . "T" . $webinar->getTime()->format('H:i:s'),
            "timezone" => "Europe/Prague",
            "template_id" => $templateId,
            "topic" => $webinar->getTopic(),
            "type" => 5
        ];

        $webinarId = null;

        // Make the request to the Zoom API to create a webinar
        try {
            $response = $httpClient->request('POST', 'https://api.zoom.us/v2/users/me/webinars', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type' => 'application/json',
                ],
                'body' => json_encode($requestBody),
                'timeout' => 60, // Increase timeout to 60 seconds
            ]);

            $data = $response->toArray();
            $webinarId = $data['id'];
            $webinar->setZoomId($webinarId);
            $entityManager->persist($webinar);
            $entityManager->flush();
        } catch (\Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface $e) {
            // HttpClientException includes ClientExceptionInterface for 4xx responses
            $errorContent = $e->getResponse()->getContent(false); // Getting the content without throwing an exception
            $errorMessage = json_decode($errorContent, true)['message'] ?? 'An error occurred';

            return new Response('Failed to create webinar: ' . $errorMessage, 500);
        } catch (\Exception $e) {
            // Handle other exceptions
            return new Response('Failed to create webinar: ' . $e->getMessage(), 500);
        }

        if ($webinarId !== null) {

            $this->addFlash('success', 'Webinar successfully created.');

            //adding registrants:

            $registrants = $webinar->getRegistrants();

            foreach ($registrants as $registrant) {
                $registrantRequestBody = [
                    "email" => $registrant->getEmail(),
                    "first_name" => $registrant->getFirstName(),
                    "last_name" => $registrant->getLastName()
                ];

                try {
                    $registrantResponse = $httpClient->request('POST', "https://api.zoom.us/v2/webinars/$webinarId/registrants", [
                        'headers' => [
                            'Authorization' => 'Bearer ' . $accessToken,
                            'Content-Type' => 'application/json',
                        ],
                        'body' => json_encode($registrantRequestBody),
                    ]);

                    $registrantData = $registrantResponse->toArray();
                } catch (\Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface $e) {
                    $errorContent = $e->getResponse()->getContent(false); // Getting the content without throwing an exception
                    $errorMessage = json_decode($errorContent, true)['message'] ?? 'An error occurred';

                    return new Response('Failed to add registrant - ' . $registrant->getFirstName() . ' ' . $registrant->getLastName() . $registrant->getEmail() . ' : ' . $errorMessage, 500);
                } catch (\Exception $e) {
                    // Handle other exceptions
                    return new Response('Failed to add registrant - ' . $registrant->getFirstName() . ' ' . $registrant->getLastName() . $registrant->getEmail() . ' : ' . $e->getMessage(), 500);
                }
            }

            $this->addFlash('success', 'Registrants succesfully added.');

            //adding panelists:

            $panelists = $webinar->getPanelists();

            $panelistsRequestBody = [
                "panelists" => []
            ];

            foreach ($panelists as $panelist) {
                $panelistsRequestBody["panelists"][] = [
                    "email" => $panelist->getEmail(),
                    "name" => $panelist->getName(),
                ];
            }

            if (!empty($panelistsRequestBody["panelists"])) {

                try {
                    $httpClient->request('POST', "https://api.zoom.us/v2/webinars/$webinarId/panelists", [
                        'headers' => [
                            'Authorization' => 'Bearer ' . $accessToken,
                            'Content-Type' => 'application/json',
                        ],
                        'body' => json_encode($panelistsRequestBody),
                    ]);

                    $this->addFlash('success', 'Panelists succesfully added.');
                } catch (\Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface $e) {
                    $errorContent = $e->getResponse()->getContent(false); // Getting the content without throwing an exception
                    $errorMessage = json_decode($errorContent, true)['message'] ?? 'An error occurred';

                    return new Response('Failed to add panelist - ' . $panelist->getName() . ' ' . $panelist->getEmail() . ' : ' . $errorMessage, 500);
                } catch (\Exception $e) {
                    // Handle other exceptions
                    return new Response('Failed to add panelist - ' . $panelist->getName() . ' ' . $panelist->getEmail() . ' : ' . $e->getMessage(), 500);
                }
            } else {
                $this->addFlash('info', 'No panelists to add.');
            }

            //flash for zoom webinar page 
            $webinarPageUrl = "https://zoom.us/webinar/$webinarId";
            $this->addFlash('webinarUrl', $webinarPageUrl);
        } else {
            $this->addFlash('error', 'Failed to create webinar.');
        }

        return $this->redirectToRoute('webinar-list');
    }
}
