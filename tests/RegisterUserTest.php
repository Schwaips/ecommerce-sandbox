<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegisterUserTest extends WebTestCase
{
    public function testSomething(): void
    {

      
      # Créer un faux client qui va simuler un navigateur & # Pointe vers une URL
      $client = static::createClient();
      $client->request('GET', '/inscription');
      
      # Remplir les champs du formulaire
      // register_user[email]
      // register_user[plainPassword][first]
      // register_user[plainPassword][second]
      // register_user[firstName]
      // register_user[lastName]
      $client->submitForm("S'inscrire", [
        'register_user[email]' => 'hello@world.com', 
        'register_user[plainPassword][first]' => 'password123',
        'register_user[plainPassword][second]' => 'password123',
        'register_user[firstName]' => 'Hello',
        'register_user[lastName]' => 'World',
      ]);
      
      $this->assertResponseRedirects("/compte"); // Vérifie si la redirection vers la page de compte a réussi
      $client->followRedirect();

      $this->assertSelectorExists('div:contains("Inscription réussie !")'); // Vérifie si le message d'alerte est présent dans la page
    }
}
