<?php
/**
 * @copyright Copyright (c) 2019 Eduard Rudakan.
 * @author    Eduard Rudakan <rudiwork@ya.ru>
 * Project: blog
 * File: DefaultController.php
 * Date: 24.05.19
 * Time: 21:54
 */
namespace App\Controller;

use App\Service\Pdo;
use App\Service\News;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * Matches "/"
     *
     * @Route("/", name="index")
     * @param News $news
     * @return Response
     * @throws \Exception
     */
    public function index(News $news)
    {
        $themes = $news->getThemeCount();
        $year_month = $news->getYearMonthCount();
        return $this->render('index.html.twig', ['themes'=>$themes,'years_data'=>$year_month]);
    }

    /**
     * Matches /news/*
     *
     * @Route("/news/{year}/{month}/{id}", name="news_list", methods={"GET"},
     *     requirements={"year":"\d+", "month":"\d+", "id":"\d+"},
     *     defaults={"year":null, "month":null, "id":null})
     * @param Request $request
     * @param News $news
     * @param int|null $year - год
     * @param int|null $month - месяц
     * @param int|null $id - ID новости для детального отображения
     * @return Response
     * @throws \Exception
     */
    public function news(Request $request, News $news, ?int $year, ?int $month, ?int $id)
    {
        $page = $request->query->get('page');
        $page = $page?:1;
        $year_month = $news->getYearMonthCount();
        $themes = $news->getThemeCount();
        $news = $news->getNews($year, $month, $id, NULL, $page);
        if (empty($news)) {
            throw $this->createNotFoundException('The News does not exist');
        }
        $pagination = $news['pagination'];
        $pagination['current'] = $page;
        unset($news['pagination']);
        return $this->render('news.html.twig', [
            'years_data' => $year_month,
            'themes'     => $themes,
            'news'       => $news,
            'pagination' => $pagination,
            'read_more'  => NULL === $id
        ]);
    }

    /**
     * Matches /theme/*
     *
     * @Route("/theme/{id}", name="theme_list", methods={"GET"}, requirements={"id":"\d+"}, defaults={"id":null})
     * @param Request $request
     * @param News $news
     * @param int|null $id - ID темы, при фильрации по теме
     * @return Response
     * @throws \Exception
     */
    public function theme(Request $request, News $news, ?int $id)
    {
        $page = $request->query->get('page');
        $page = $page?:1;
        $year_month = $news->getYearMonthCount();
        $themes = $news->getThemeCount();
        $news = $news->getNews(NULL, NULL, NULL, $id, $page);
        if (empty($news)) {
            throw $this->createNotFoundException('The News does not exist');
        }
        $pagination = $news['pagination'];
        $pagination['current'] = $page;
        unset($news['pagination']);
        return $this->render('news.html.twig', [
            'years_data' => $year_month,
            'themes'     => $themes,
            'news'       => $news,
            'pagination' => $pagination,
            'read_more'  => true
        ]);
    }

}