<?php
/**
 * @copyright Copyright (c) 2019 Eduard Rudakan.
 * @author    Eduard Rudakan <rudiwork@ya.ru>
 * Project: blog
 * File: AdminController.php
 * Date: 24.05.19
 * Time: 20:16
 */
namespace App\Controller;

use App\Service\News;
use http\Exception\InvalidArgumentException as InvalidArgumentExceptionAlias;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class AdminController
 * @package App\Controller
 */
class AdminController extends AbstractController
{
    /**
     * Matches /admin
     *
     * @Route("/admin", name="admin")
     * @param Request $request
     * @param News $news
     * @return Response
     */
    public function index(Request $request, News $news)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $page = $request->query->get('page');
        $page = $page?:1;
        $news = $news->getNews(NULL, NULL, NULL, NULL, $page);
        if (empty($news)) {
            throw $this->createNotFoundException('The News does not exist');
        }
        $pagination = $news['pagination'];
        $pagination['current'] = $page;
        unset($news['pagination']);
        return $this->render('admin/index.html.twig', [
            'news'       => $news,
            'pagination' => $pagination,
            'read_more'  => true,
            'admin'      => true
        ]);
    }

    /**
     * Отображает форму добавления новости
     * Matches /admin/news/add
     *
     * @Route("/admin/news/add", name="admin_add_news", methods={"GET"})
     *
     * @param Request $request
     * @param News $news
     * @return Response
     */
    public function add(Request $request, News $news)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $themes = $news->getThemeList();
        return $this->render('admin/add.html.twig',
            [
                'themes' => $themes
            ]);
    }

    /**
     * Отображает форму редактирования новости
     * Matches /admin/news/edit/*
     *
     * @Route("/admin/news/edit/{id}", name="admin_news_edit", methods={"GET"}, requirements={"id":"\d+"})
     *
     * @param Request $request
     * @param News $news
     * @param int $id
     * @return Response
     */
    public function editNews(Request $request, News $news, int $id)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $themes = $news->getThemeList();
        $item = $news->getNews(NULL, NULL, $id, NULL, 1);
        if (!empty($item[0])) {
            return $this->render('admin/edit.html.twig',
                [
                    'themes' => $themes,
                    'news' => $item[0]
                ]);
        } else {
            throw $this->createNotFoundException('The News does not exist');
        }
    }

    /**
     * Удалить новость
     * Matches /admin/news/delete
     *
     * @Route("/admin/news/delete", methods={"POST"}, name="admin_delete_news")
     *
     * @param Request $request
     * @param News $news
     * @return JsonResponse
     */
    public function deleteNews(Request $request, News $news)
    {
        $id = $request->request->get('id');
        if (!empty($id)) {
            try {
                $this->denyAccessUnlessGranted('ROLE_ADMIN');
                if ($news->deleteNews($id)) {
                    return new JsonResponse(['success'=>1, 'id'=>$id]);
                }
            } catch (\Exception $e) {
                return new JsonResponse(['error'=>1, 'code'=>$e->getCode(), 'message'=>$e->getMessage()]);
            }
        }
        return new JsonResponse(['error'=>1, 'code'=>1001, 'message'=>'News ID is empty']);
    }

    /**
     * Добавить новость
     * Matches /admin/news/add
     *
     * @Route("/admin/news/add", name="admin_news_add", methods={"POST"})
     * @param Request $request
     * @param News $news
     * @return JsonResponse
     */
    public function addNews(Request $request, News $news)
    {
        $id = $request->request->get('id');
        $id = !empty($id)? (int)$id : 0;
        $title = $request->request->get('title');
        $text  = $request->request->get('text');
        $date  = $request->request->get('date');
        $theme = $request->request->get('theme');
        if (empty($title) || empty($text) || empty($date) || empty($theme)) {
            throw new InvalidArgumentExceptionAlias('Invalid arguments');
        }
        try {
            if ($news->addNews($id, $title, $text, $date, $theme)) {
                return new JsonResponse(['success'=>1, 'message'=>'Успешно создали новость']);
            } else {
                return new JsonResponse(['error'=>1, 'message'=>'Ошибка добавления новости.']);
            }
        } catch(\Exception $e) {
            return new JsonResponse(['error'=>1, 'message'=>$e->getMessage()]);
        }
    }


}