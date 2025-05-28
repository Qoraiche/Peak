<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Qoraiche\Peak\Models\SubscriptionPlan;

class SubscriptionPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SubscriptionPlan::query()->create([
            'name' => [
                'en' => 'Basic Plan',
                'fr' => 'Plan de Base',
            ],
            'description' => [
                'en' => 'A basic plan with limited features.',
                'fr' => 'Un plan de base avec des fonctionnalités limitées.',
            ],
            'primary_heading' => [
                'en' => 'Start with the basic plan.',
                'fr' => 'Commencez avec le plan de base.',
            ],
            'features' => [
                'en' => [
                    'Access to limited content',
                    'Basic usage per month',
                    'Support via email'
                ],
                'fr' => [
                    'Accès à un contenu limité',
                    'Utilisation de base par mois',
                    'Assistance par e-mail'
                ],
            ],
            'featured' => false,
            'monthly_incentive' => [
                'en' => 'Get started now!',
                'fr' => 'Commencez maintenant!',
            ],
            'yearly_incentive' => [
                'en' => 'Save 10% when you switch to yearly!',
                'fr' => 'Économisez 10% lorsque vous passez à l\'annuel!',
            ],
            'per_seat' => false,
            'seat_name' => null,
            'seat_name_label' => null,
            'max_seats' => null,
            'order' => 0,
            'trial_days' => 0,
            'status' => 'active',
        ]);
        SubscriptionPlan::query()->create([
            'name' => [
                'en' => 'Premium Plan',
                'fr' => 'Plan Premium',
            ],
            'description' => [
                'en' => 'Unlock more features with the Premium plan.',
                'fr' => 'Déverrouillez plus de fonctionnalités avec le plan Premium.',
            ],
            'primary_heading' => [
                'en' => 'Upgrade to Premium for enhanced features.',
                'fr' => 'Passez à Premium pour des fonctionnalités améliorées.',
            ],
            'features' => [
                'en' => [
                    'Access to all content',
                    'Priority support',
                    'Increased monthly usage',
                ],
                'fr' => [
                    'Accès à tout le contenu',
                    'Support prioritaire',
                    'Utilisation mensuelle accrue',
                ],
            ],
            'featured' => true,
            'monthly_incentive' => [
                'en' => 'Enjoy additional perks!',
                'fr' => 'Profitez d\'avantages supplémentaires!',
            ],
            'yearly_incentive' => [
                'en' => 'Get 15% off when you choose yearly!',
                'fr' => 'Obtenez 15% de réduction lorsque vous choisissez l\'annuel!',
            ],
            'per_seat' => false,
            'seat_name' => null,
            'seat_name_label' => null,
            'max_seats' => null,
            'order' => 1,
            'trial_days' => 7,
            'status' => 'active',
        ]);
        SubscriptionPlan::query()->create([
            'name' => [
                'en' => 'Pro Plan',
                'fr' => 'Plan Pro',
            ],
            'description' => [
                'en' => 'Access to all features and premium support.',
                'fr' => 'Accédez à toutes les fonctionnalités et au support premium.',
            ],
            'primary_heading' => [
                'en' => 'Go Pro for unlimited access.',
                'fr' => 'Passez à Pro pour un accès illimité.',
            ],
            'features' => [
                'en' => [
                    'Unlimited access to all content',
                    'Premium 24/7 support',
                    'Advanced features and analytics'
                ],
                'fr' => [
                    'Accès illimité à tout le contenu',
                    'Support premium 24h/24 et 7j/7',
                    'Fonctionnalités avancées et analyses'
                ],
            ],
            'featured' => false,
            'monthly_incentive' => [
                'en' => 'Exclusive offers for Pro members!',
                'fr' => 'Offres exclusives pour les membres Pro!',
            ],
            'yearly_incentive' => [
                'en' => 'Save 20% when you choose yearly!',
                'fr' => 'Économisez 20% lorsque vous choisissez l\'annuel!',
            ],
            'per_seat' => true,
            'seat_name' => 'Team',
            'seat_name_label' => [
                'en' => 'Team',
                'fr' => 'Team',
            ],
            'max_seats' => 10,
            'order' => 2,
            'trial_days' => 14,
            'status' => 'active',
        ]);
    }
}
