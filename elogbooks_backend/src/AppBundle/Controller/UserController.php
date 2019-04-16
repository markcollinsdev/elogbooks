<?php
/**
 * Created by PhpStorm.
 * User: octoplod
 * Date: 13/04/2019
 * Time: 20:41
 */

namespace AppBundle\Controller;


use AppBundle\Entity\User;
use AppBundle\Form\FilterType\Model\UserFilter;
use AppBundle\Form\FilterType\Model\JobFilter;
use AppBundle\Form\Type\UserType;
use Knp\Component\Pager\Pagination\AbstractPagination;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Job;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/user")
 */
class UserController extends AbstractApiController
{
    /**
     * @param User $user
     * @Route("/{id}")
     * @Method("GET")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getAction(User $user)
    {
        return $this->returnViewResponse($user);
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
        $filter = new UserFilter();

        $form = $this->createForm('AppBundle\Form\FilterType\UserFilterType', $filter, ['method' => 'GET']);
        $form->handleRequest($request);

        if ($this->getErrors($form)) {
            return $this->returnViewResponse($this->getErrors($form), Response::HTTP_BAD_REQUEST, $filter->getSerialisationGroups());
        }

        /** @var AbstractPagination $pagination */
        $pagination = $this->get('knp_paginator')->paginate(
            $this
                ->getDoctrine()
                ->getRepository(User::class)
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
        $user = new User();

        $form = $this->createForm(UserType::class, $user, ['method' => 'POST']);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->returnViewResponse($user, Response::HTTP_CREATED);
        }

        return $this->returnViewResponse($this->getErrors($form), Response::HTTP_BAD_REQUEST);
    }

    /**
     * @param Request $request
     * @param User $user
     *
     * @return Response
     *
     * @Route("/{id}")
     * @Method("PUT")
     */
    public function putAction(Request $request, User $user)
    {
        $user->setName($request->get('name'));
        $user->setEmail($request->get('email'));

        $em = $this->getDoctrine()->getManager();
        $em->flush();

        return $this->returnViewResponse($user, Response::HTTP_CREATED);

    }
}