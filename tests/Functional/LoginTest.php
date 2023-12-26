<?php

namespace App\Tests\Functional;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LoginTest extends WebTestCase
{
    public function testIfLoginIsSuccessful(): void
    {
        $client = static::createClient();

        /** @var UrlGeneratorInterface $urlGenerator */
        $urlGenerator = $client->getContainer()->get("router");

        $crawler = $client->request('GET', $urlGenerator->generate('app_login'));

        $form = $crawler->filter("form[name=login]")->form([
            "_username" => "admin@primeur.com",
            "_password" => "admin"
        ]);

        $client->submit($form);

        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);

        $client->followRedirect();

        $this->assertRouteSame('app_home');
    }

    public function testIfLoginFailedWhenPasswordIsWrong(): void
    {
            $client = static::createClient();
    
            /** @var UrlGeneratorInterface $urlGenerator */
            $urlGenerator = $client->getContainer()->get("router");
    
            $crawler = $client->request('GET', $urlGenerator->generate('app_login'));
    
            $form = $crawler->filter("form[name=login]")->form([
                "_username" => "admin@primeur.com",
                "_password" => "password_"
            ]);
    
            $client->submit($form);
    
            $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
    
            $client->followRedirect();
    
            $this->assertRouteSame('app_login');
    
            $this->assertSelectorTextContains("div.alert-danger", "Invalid credentials.");
    

    }
    
}
