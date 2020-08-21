<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/article")
 */
class ArticleController extends AbstractController
{
    /**
     * @Route("/create", name="article_create")
     */
    public function create(Request $request, ParameterBagInterface $parameterBag, EntityManagerInterface $entityManager)
    {
        $article = new Article();
        $articleDTO = $article->createDTO($parameterBag);

        $form = $this->createForm(ArticleType::class, $articleDTO);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $article->updateFromDTO($articleDTO);
            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('article_index', ['_locale' => 'en']);

        }

        return $this->render('article/create_or_update.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{_locale<%app.supported_locales%>}/{slug}/update", name="article_update")
     */
    public function update(string $slug, ArticleRepository $repository, Request $request, ParameterBagInterface $parameterBag, EntityManagerInterface $entityManager)
    {
        $article = $repository->findOneByTranslatedSlug($slug);
        $articleDTO = $article->createDTO($parameterBag);

        $form = $this->createForm(ArticleType::class, $articleDTO);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $article->updateFromDTO($articleDTO);
            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('article_show', ['_locale' => 'en', 'slug' => $article->getSlug()]);
        }

        return $this->render('article/create_or_update.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{_locale<%app.supported_locales%>}", name="article_index")
     */
    public function index(ArticleRepository $repository)
    {
        $articles = $repository->findBy(['deletedAt' => null], ['updatedAt' => 'DESC']);

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
