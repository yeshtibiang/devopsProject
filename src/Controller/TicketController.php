<?php

namespace App\Controller;

use App\Entity\Ticket;
use App\Form\Ticket1Type;
use App\Form\TicketType;
use App\Repository\TicketRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/ticket')]
class TicketController extends AbstractController
{
    #[Route('/', name: 'app_ticket_index', methods: ['GET'])]
    public function index(TicketRepository $ticketRepository): Response
    {
        $ticket = null;
        // if user is admin, show all tickets
        if ($this->isGranted('ROLE_ADMIN') or $this->isGranted('ROLE_AGENT')) {
            // find all tickets that are not closed

            $ticket = $ticketRepository->findAll();
        }
        else if ($this->isGranted('ROLE_USER')) {
            $ticket = $ticketRepository->findBy(['createdBy' => $this->getUser()]);
        }
        else if ($this->isGranted('ROLE_AGENT')){
            $ticket = $ticketRepository->findBy(['status' => [
                'Reçu',
                'En cours',
                'En attente',
                'Ne pas traiter',
                'Terminé',

            ]]);
        }
        // if user is simple user show his own tickets


        return $this->render('ticket/index.html.twig', [
            'tickets' => $ticket,
        ]);
    }

    #[Route('/new', name: 'app_ticket_new', methods: ['GET', 'POST'])]
    public function new(Request $request, TicketRepository $ticketRepository): Response
    {
        $ticket = new Ticket();
        $form = $this->createForm(TicketType::class, $ticket, [
            'user' => $this->getUser(),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ticket->setNumber($this->generateTicketNumber());
            $ticket->setCreatedAt(new \DateTimeImmutable());
            // mettre à jour le statut du ticket
            $ticket->setStatus('Reçu');
            $ticketRepository->save($ticket, true);

            return $this->redirectToRoute('app_ticket_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ticket/new.html.twig', [
            'ticket' => $ticket,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_ticket_show', methods: ['GET'])]
    public function show(Ticket $ticket): Response
    {
        return $this->render('ticket/show.html.twig', [
            'ticket' => $ticket,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_ticket_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Ticket $ticket, TicketRepository $ticketRepository): Response
    {
        $form = $this->createForm(Ticket1Type::class, $ticket, [
            'user' => $this->getUser(),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ticketRepository->save($ticket, true);

            return $this->redirectToRoute('app_ticket_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ticket/edit.html.twig', [
            'ticket' => $ticket,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_ticket_delete', methods: ['POST'])]
    public function delete(Request $request, Ticket $ticket, TicketRepository $ticketRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ticket->getId(), $request->request->get('_token'))) {
            $ticketRepository->remove($ticket, true);
        }

        return $this->redirectToRoute('app_ticket_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/assign', name: 'app_ticket_assign', methods: ['GET'])]
    public function assignedToSelf(Ticket $ticket, TicketRepository $ticketRepository): Response
    {
        $ticket->setAssignedTo($this->getUser());
        $ticketRepository->save($ticket, true);

        return $this->redirectToRoute('app_ticket_index', [], Response::HTTP_SEE_OTHER);
    }


    private function generateTicketNumber() : string
    {
        $ticketNumber = 'TICKET-';
        $ticketNumber .= date('Y');
        $ticketNumber .= date('m');
        $ticketNumber .= date('d');
        $ticketNumber .= '-';
        $ticketNumber .= date('H');
        $ticketNumber .= date('i');
        $ticketNumber .= date('s');
        $ticketNumber .= '-';
        $ticketNumber .= rand(100, 999);

        return $ticketNumber;
    }
}
