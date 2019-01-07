<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Entity\SegUsuarios;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;

class SegUsuarioControllerTest extends TestCase 
{
	public function testSegUsuario()
	{
		$usuario = new SegUsuarios();
		$usuario->setTitle("titulo_prueba");
		$usuario->setBody("body_prueba");
		//$correo = 
		$usuario = $this->container->get('usuario.api.handler')->findOneByCorreo($correo);
		$usuarioRepository = $this->createMock(ObjectRepository::class);

		$usuarioRepository->expects($this->any())
            ->method('find')
			->willReturn($usuario);
			
		$objectManager = $this->createMock(ObjectManager::class);

		$objectManager->expects($this->any())
            ->method('getRepository')
			->willReturn($usuarioRepository);
			
		$this->assertEquals("soporte@inmobilia.com", $article->getTitle());
	}
}