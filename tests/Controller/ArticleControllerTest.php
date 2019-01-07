<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Entity\Article;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;

class ArticleControllerTest extends TestCase 
{
	public function testArticleShow()
	{
		$article = new Article();
		$article->setTitle("titulo_prueba");
		$article->setBody("body_prueba");
		$articleRepository = $this->createMock(ObjectRepository::class);

		$articleRepository->expects($this->any())
            ->method('find')
			->willReturn($article);
			
		$objectManager = $this->createMock(ObjectManager::class);

		$objectManager->expects($this->any())
            ->method('getRepository')
			->willReturn($articleRepository);
			
		$this->assertEquals("titulo_prueba", $article->getTitle());
	}
}