<?php

namespace App\Tests;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserTest extends WebTestCase
{
    private static $container = Container::class;


    /*public function testSomething(): void
    {

        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Hello World');
    }*/

    // test Login with bad credentials
    /*public function testLoginWithBadCredentials(){
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form();
        $form['email'] = 'fake@esp.sn';
        $form['password'] = 'fakepassword';
        $client->submit($form);
        $this->assertResponseRedirects('/login');
        $client->followRedirect();
        $this->assertSelectorExists('.alert.alert-danger');
    }*/

    // test home page is accessible, ignore redirection to login

    public function testLogin()
    {
        $client = static::createClient();
        $client->request('GET', '/login');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    // test register
    public function testRegister()
    {
        $client = static::createClient();
        $client->request('GET', '/register');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

}
