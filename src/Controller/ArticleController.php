<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/article")
 */
class ArticleController extends AbstractController
{
    /**
     * @Route("/{_locale<%app.supported_locales%>}", name="article_index")
     */
    public function index(ArticleRepository $repository)
    {
        $articles = $repository->findBy(['deletedAt' => null]);

        return $this->render('article/index.html.twig', [
            'articles' => $articles,
        ]);
    }

    /**
     * @Route("/{_locale<%app.supported_locales%>}/{slug}", name="article_show")
     */
    public function show(string $slug, ArticleRepository $repository)
    {
        $article = $repository->findOneByTranslatedSlug($slug);

        if (!$article) {
            throw new NotFoundHttpException(sprintf('No article found with slug "%s"', $slug));
        }

        return $this->render('article/show.html.twig', [
            'article' => $article,
        ]);
    }

    /**
     * @Route("/{slug}/soft_delete", name="article_soft_delete")
     */
    public function softDelete(Article $article, EntityManagerInterface $entityManager)
    {
        $entityManager->remove($article);

        $entityManager->flush();

        return new Response(sprintf('Article with slug %s was soft deleted at %s', $article->getSlug(), $article->getCreatedAt()->format('Y-m-d H:i:s')));
    }
}
