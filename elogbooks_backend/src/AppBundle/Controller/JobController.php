<?php

namespace AppBundle\Controller;

use AppBundle\Form\FilterType\Model\ListFilter;
use AppBundle\Form\FilterType\Model\JobFilter;
use Knp\Component\Pager\Pagination\AbstractPagination;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Job;
use AppBundle\Entity\User;

use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/job")
 */
class JobController extends AbstractApiController
{
    /**
     * @param Job $job
     * @Route("/{id}")
     * @Method("GET")
     *
     * @return Job
     */
    public function getAction(Job $job)
    {
        return $this->returnViewResponse($job);
    }

    /**
     * @param Request $request
     *
     * @return Response
     *
     * @Route("")
     * @Method("GET")
     */
    public function cgetAction(Request $request)
    {
        $filter = new JobFilter();

        $form = $this->createForm('AppBundle\Form\FilterType\JobFilterType', $filter, ['method' => 'GET']);
        $form->handleRequest($request);

        if ($this->getErrors($form)) {
            return $this->returnViewResponse($this->getErrors($form), Response::HTTP_BAD_REQUEST, $filter->getSerialisationGroups());
        }

        /** @var AbstractPagination $pagination */
        $pagination = $this->get('knp_paginator')->paginate(
            $this
                ->getDoctrine()
                ->getRepository(Job::class)
                ->filterAndReturnQuery($filter),
            $filter->getPage(),
            $filter->getLimit()
        );

        return $this->returnCollectionViewResponse(
            $pagination,
            Response::HTTP_OK,
            $filter->getSerialisationGroups()
        );
    }

    /**
     * @param Request $request
     *
     * @return Response
     *
     * @Route("")
     * @Method("POST")
     */
    public function postAction(Request $request)
    {
        $job = new Job();
        $form = $this->createForm('AppBundle\Form\Type\JobType', $job, ['method' => 'POST']);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($job);
            $em->flush();

            return $this->returnViewResponse($job, Response::HTTP_CREATED);
        }

        return $this->returnViewResponse($this->getErrors($form), Response::HTTP_BAD_REQUEST);
    }

    /**
     * @param Request $request
     * @param Job $job
     *
     * @return Response
     *
     * @Route("/{id}")
     * @Method("PUT")
     */
    public function putAction(Request $request, Job $job)
    {

        $job->setDescription($request->get('description'));
        $job->setStatus($request->get('status'));

        if($request->get('user')) {
            $user = $this->getDoctrine()
                ->getRepository(User::class)
                ->find($request->get('user'));
            $job->setUser($user);
        }

        $em = $this->getDoctrine()->getManager();
        $em->flush();

        return $this->returnViewResponse($job, Response::HTTP_CREATED);

    }
}
