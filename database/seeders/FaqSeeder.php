<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Qoraiche\Peak\Models\Faq;

class FaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Faq::create([
            'order_column' => 1,
            'question' => [
                'en' => 'How do I create an account?',
                'fr' => 'Comment créer un compte ?',
            ],
            'answer' => [
                'en' => 'Click on the "Sign Up" button and fill in the required information.',
                'fr' => 'Cliquez sur le bouton "S\'inscrire" et remplissez les informations requises.',
            ],
        ]);

        Faq::create([
            'order_column' => 2,
            'question' => [
                'en' => 'How can I reset my password?',
                'fr' => 'Comment réinitialiser mon mot de passe ?',
            ],
            'answer' => [
                'en' => 'Go to the login page and click on "Forgot Password?" to receive a reset link.',
                'fr' => 'Allez sur la page de connexion et cliquez sur "Mot de passe oublié ?" pour recevoir un lien de réinitialisation.',
            ],
        ]);

        Faq::create([
            'order_column' => 3,
            'question' => [
                'en' => 'Is there a mobile app?',
                'fr' => 'Existe-t-il une application mobile ?',
            ],
            'answer' => [
                'en' => 'Yes, you can download our app from the App Store and Google Play.',
                'fr' => 'Oui, vous pouvez télécharger notre application sur l\'App Store et Google Play.',
            ],
        ]);
    }
}
