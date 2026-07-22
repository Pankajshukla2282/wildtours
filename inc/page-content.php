<?php
/**
 * Page content helper for production-grade Panna Wild Tours copy.
 *
 * @package wildtours
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! function_exists( 'wildtours_default_page_content' ) ) {
    function wildtours_default_page_content( $page_title ) {
        switch ( strtolower( $page_title ) ) {
            case 'about us':
                return array(
                    'headline' => 'Discover Panna Wild Tours',
                    'subtitle' => 'Your trusted local partner for wildlife safaris, jungle adventures, and immersive cultural experiences in Panna Tiger Reserve.',
                    'content' => array(
                        "Panna Wild Tours has been guiding nature lovers through the scenic landscapes of Panna Tiger Reserve for more than a decade.",
                        "We specialize in tiger safaris, bird watching, heritage sightseeing, and tailor-made itineraries for families, photographers, and wildlife enthusiasts.",
                        "Our local team handles permits, vehicle bookings, lodging recommendations, and end-to-end travel support so you can focus on the adventure.",
                    ),
                    'features' => array(
                        'Guided jungle safaris with certified naturalist guides',
                        'Personalized itineraries for every traveler',
                        'Pickup, accommodation, and local logistics support',
                        'Latest wildlife updates and seasonal tour plans',
                    ),
                    'team' => array(
                        array(
                            'name' => 'Lakhan Lal',
                            'role' => 'Lead Safari Guide',
                            'bio' => 'Expert in wildlife tracking, tiger reserve routes, and personalized jungle experiences.',
                        ),
                        array(
                            'name' => 'Rajeshwari',
                            'role' => 'Guest Services Manager',
                            'bio' => 'Responsible for bookings, guest support, and local hospitality arrangements.',
                        ),
                    ),
                );
            case 'contact us':
                return array(
                    'headline' => 'Contact Panna Wild Tours',
                    'subtitle' => 'We’re ready to help you plan your next wildlife adventure in Panna.',
                    'content' => array(
                        "Send us a message with your travel dates, preferred safari type, and group size.",
                        "We will respond quickly with the best itinerary, vehicle availability, and estimated pricing.",
                        "You can also book directly through our booking page or email us for custom packages.",
                    ),
                    'details' => array(
                        'Email' => 'Support@pannawildtour.com',
                        'Phone' => '+91 992184....',
                        'Address' => 'Panna Wild Tour, Madla Gate, Madla, Panna, Madhya Pradesh, India',
                    ),
                );
            case 'booking':
                return array(
                    'headline' => 'Book Your Panna Safari',
                    'subtitle' => 'Reserve your tour, safari, or guided experience with confidence.',
                    'content' => array(
                        "Choose from full-day safaris, morning excursions, birding trips, and cultural tours.",
                        "We handle all local permits, transport, and on-site coordination.",
                        "Your booking confirmation includes a detailed itinerary, contact support, and arrival instructions.",
                    ),
                    'steps' => array(
                        'Select your preferred package and travel dates',
                        'Provide guest details and any special requests',
                        'Confirm your booking and receive waypoint directions',
                        'Meet your guide at the Panna checkpost and start the adventure',
                    ),
                );
            default:
                return array(
                    'headline' => $page_title,
                    'subtitle' => 'Experience the best of Panna Tiger Reserve with expert guides and local support.',
                    'content' => array(
                        "Panna Wild Tours offers customized wildlife tours, nature walks, and cultural visits around the Panna reserve.",
                        "Our local knowledge helps you discover hidden viewpoints, pristine trails, and safe safari routes.",
                        "Contact us to design a tour that matches your interests and schedule.",
                    ),
                );
        }
    }
}
