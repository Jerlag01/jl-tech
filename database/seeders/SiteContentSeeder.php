<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\Project;
use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

/**
 * Starter content for the website. Everything here can be edited or removed in the admin.
 */
class SiteContentSeeder extends Seeder
{
    public function run(): void
    {
        Artisan::call('content:sync');

        $this->seedServices();
        $this->seedProjects();
        $this->seedItems();
    }

    protected function seedServices(): void
    {
        $services = [
            [
                'slug' => 'public-lighting',
                'icon' => 'streetlight',
                'title' => ['nl' => 'Openbare verlichting & tunnels', 'en' => 'Public lighting & tunnels'],
                'subtitle' => ['nl' => 'Veilig verlichten', 'en' => 'Lighting safely'],
                'description' => [
                    'nl' => 'Ontwerp, installatie en onderhoud van openbare verlichting en tunneluitrusting. Wij zorgen voor de veiligheid en het visueel comfort van alle weggebruikers.',
                    'en' => 'Design, installation and maintenance of public lighting and road tunnel equipment. We ensure the safety and visual comfort of all road users.',
                ],
                'features' => [
                    'nl' => "Slimme en geconnecteerde LED-verlichting\nTunneluitrusting en signalisatie\nPreventief en correctief onderhoud 24/7\nTelebeheer en centrale supervisie\nConformiteit van installaties",
                    'en' => "Smart, connected LED lighting\nRoad tunnel equipment and signalling\nPreventive and corrective maintenance 24/7\nRemote management and central supervision\nInstallation compliance upgrades",
                ],
                'stats' => [['value' => '150+', 'label' => ['nl' => 'Projecten', 'en' => 'Projects']]],
            ],
            [
                'slug' => 'private-lighting',
                'icon' => 'stadium',
                'title' => ['nl' => 'Private verlichting & sport', 'en' => 'Private & sports lighting'],
                'subtitle' => ['nl' => 'Uw ruimtes in de kijker', 'en' => 'Showcasing your spaces'],
                'description' => [
                    'nl' => 'Verlichtingsprojecten voor private, commerciële en historische gebouwen en sportinfrastructuur. Wij creëren unieke lichtsferen die uw ruimtes tot hun recht laten komen.',
                    'en' => 'Lighting projects for private, commercial and heritage buildings and sports facilities. We create unique lighting atmospheres that bring out the best in your spaces.',
                ],
                'features' => [
                    'nl' => "Gevel- en gebouwverlichting\nAanlichten van monumenten\nVerlichting van sportterreinen\nLED-oplossingen op maat\nSlimme lichtsturing",
                    'en' => "Facade and building lighting\nFloodlighting of monuments\nSports ground lighting\nTailor-made LED solutions\nSmart lighting control",
                ],
                'stats' => [['value' => '50+', 'label' => ['nl' => 'Projecten', 'en' => 'Projects']]],
            ],
            [
                'slug' => 'energy-networks',
                'icon' => 'bolt',
                'title' => ['nl' => 'Energie & LS/MS-netten', 'en' => 'Energy & LV/MV networks'],
                'subtitle' => ['nl' => 'Vermogen onder controle', 'en' => 'Power under control'],
                'description' => [
                    'nl' => 'Werken aan laagspanningsnetten, elektrische cabines en distributie-infrastructuur. Onze expertise dekt de volledige elektrische keten.',
                    'en' => 'Work on low voltage networks, electrical substations and distribution infrastructure. Our expertise covers the entire electrical chain.',
                ],
                'features' => [
                    'nl' => "Bouw en renovatie van LS/MS-cabines\nLeggen van ondergrondse en bovengrondse kabels\nIndustriële aansluitingen\nDringende interventies",
                    'en' => "Construction and renovation of LV/MV substations\nUnderground and overhead cable laying\nIndustrial connections\nEmergency call-outs",
                ],
                'stats' => [['value' => '100+', 'label' => ['nl' => 'Projecten', 'en' => 'Projects']]],
            ],
            [
                'slug' => 'telecom-fibre',
                'icon' => 'fiber',
                'title' => ['nl' => 'Telecom & glasvezel', 'en' => 'Telecom & fibre optics'],
                'subtitle' => ['nl' => 'Altijd verbonden', 'en' => 'Always connected'],
                'description' => [
                    'nl' => 'Aanleg en aansluiting van glasvezel- en telecomnetwerken voor operatoren, overheden en bedrijven.',
                    'en' => 'Installation and connection of fibre optic and telecom networks for operators, public authorities and businesses.',
                ],
                'features' => [
                    'nl' => "Aanleg van glasvezelnetwerken\nLassen en meten van glasvezel\nAansluitingen tot bij de klant\nTelecominfrastructuur",
                    'en' => "Fibre network roll-out\nFibre splicing and testing\nConnections to the premises\nTelecom infrastructure",
                ],
                'stats' => [],
            ],
            [
                'slug' => 'civil-engineering',
                'icon' => 'construction',
                'title' => ['nl' => 'Burgerlijke bouwkunde', 'en' => 'Civil engineering'],
                'subtitle' => ['nl' => 'Stevige fundamenten', 'en' => 'Solid foundations'],
                'description' => [
                    'nl' => 'Grondwerken, sleuven en funderingen voor al onze technische installaties, uitgevoerd met eigen materieel en personeel.',
                    'en' => 'Earthworks, trenches and foundations for all our technical installations, carried out with our own equipment and people.',
                ],
                'features' => [
                    'nl' => "Sleuven en grondwerken\nFunderingen voor masten en cabines\nGestuurde boringen\nHerstel van wegdek en voetpaden",
                    'en' => "Trenching and earthworks\nFoundations for poles and substations\nDirectional drilling\nRoad and pavement reinstatement",
                ],
                'stats' => [],
            ],
            [
                'slug' => 'traffic-lights',
                'icon' => 'traffic',
                'title' => ['nl' => 'Verkeerslichten', 'en' => 'Traffic lights'],
                'subtitle' => ['nl' => 'Vlot en veilig verkeer', 'en' => 'Smooth, safe traffic'],
                'description' => [
                    'nl' => 'Installatie, aanpassing en onderhoud van verkeerslichtinstallaties en dynamische signalisatie.',
                    'en' => 'Installation, modification and maintenance of traffic light installations and dynamic signage.',
                ],
                'features' => [
                    'nl' => "Plaatsen van verkeerslichten\nVerkeersregelaars en detectie\nDynamische signalisatie\nOnderhoud en herstellingen",
                    'en' => "Traffic light installation\nTraffic controllers and detection\nDynamic signage\nMaintenance and repairs",
                ],
                'stats' => [],
            ],
        ];

        foreach ($services as $index => $service) {
            Service::query()->updateOrCreate(['slug' => $service['slug']], [
                ...$service,
                'summary' => $service['subtitle'],
                'sort' => $index + 1,
                'active' => true,
                'show_on_home' => true,
            ]);
        }
    }

    protected function seedProjects(): void
    {
        if (Project::query()->exists()) {
            return;
        }

        $serviceId = fn (string $slug): ?int => Service::query()->where('slug', $slug)->value('id');

        $projects = [
            [
                'service_id' => $serviceId('public-lighting'),
                'title' => ['nl' => 'Modernisering van de straatverlichting', 'en' => 'Street lighting modernisation'],
                'category' => ['nl' => 'Openbare verlichting', 'en' => 'Public lighting'],
                'badge' => ['nl' => 'Uitgelicht', 'en' => 'Featured'],
                'description' => [
                    'nl' => 'Volledige vervanging van de straatverlichting door telebeheerde LED-armaturen. Een voorbeeldproject dat energie-efficiëntie en verkeersveiligheid combineert.',
                    'en' => 'Complete replacement of the street lighting with remotely managed LED luminaires. An example project combining energy efficiency and road safety.',
                ],
                'stats' => [
                    ['value' => '2 500', 'label' => ['nl' => 'lichtpunten', 'en' => 'light points']],
                    ['value' => '70%', 'label' => ['nl' => 'energiebesparing', 'en' => 'energy saved']],
                    ['value' => 'LED', 'label' => ['nl' => 'technologie', 'en' => 'technology']],
                ],
                'featured' => true,
                'highlight' => true,
                'show_on_home' => false,
            ],
            [
                'service_id' => $serviceId('public-lighting'),
                'title' => ['nl' => 'Tunnelverlichting', 'en' => 'Tunnel lighting'],
                'category' => ['nl' => 'Tunnels', 'en' => 'Tunnels'],
                'badge' => ['nl' => 'Veiligheid', 'en' => 'Safety'],
                'description' => [
                    'nl' => 'Installatie van verlichting, noodverlichting en veiligheidssystemen in een wegtunnel.',
                    'en' => 'Installation of lighting, emergency lighting and safety systems in a road tunnel.',
                ],
                'stats' => [
                    ['value' => '24/7', 'label' => ['nl' => 'supervisie', 'en' => 'supervision']],
                    ['value' => 'LED', 'label' => ['nl' => 'bebakening', 'en' => 'guidance lights']],
                ],
                'highlight' => true,
                'show_on_home' => true,
            ],
            [
                'service_id' => $serviceId('private-lighting'),
                'title' => ['nl' => 'Verlichting van een sportcomplex', 'en' => 'Sports complex lighting'],
                'category' => ['nl' => 'Sportverlichting', 'en' => 'Sports lighting'],
                'description' => [
                    'nl' => 'LED-verlichting voor voetbalvelden en tennisterreinen, aangestuurd per zone.',
                    'en' => 'LED lighting for football pitches and tennis courts, controlled per zone.',
                ],
                'stats' => [['value' => '4', 'label' => ['nl' => 'terreinen', 'en' => 'pitches']]],
                'highlight' => true,
                'show_on_home' => true,
            ],
            [
                'service_id' => $serviceId('telecom-fibre'),
                'title' => ['nl' => 'Uitrol van glasvezel', 'en' => 'Fibre optic roll-out'],
                'category' => ['nl' => 'Telecom', 'en' => 'Telecom'],
                'badge' => ['nl' => 'Telecom', 'en' => 'Telecom'],
                'description' => [
                    'nl' => 'Aanleg en aansluiting van glasvezelnetwerken voor telecomoperatoren.',
                    'en' => 'Installation and connection of fibre networks for telecom operators.',
                ],
                'stats' => [['value' => '100+ km', 'label' => ['nl' => 'aangelegd', 'en' => 'installed']]],
                'show_on_home' => true,
            ],
            [
                'service_id' => $serviceId('energy-networks'),
                'title' => ['nl' => 'Elektrische cabines', 'en' => 'Electrical substations'],
                'category' => ['nl' => 'LS/MS-netten', 'en' => 'LV/MV networks'],
                'badge' => ['nl' => 'Energie', 'en' => 'Energy'],
                'description' => [
                    'nl' => 'Bouw en renovatie van LS/MS-cabines voor het distributienet.',
                    'en' => 'Construction and renovation of LV/MV substations for the distribution grid.',
                ],
                'stats' => [],
            ],
            [
                'service_id' => $serviceId('traffic-lights'),
                'title' => ['nl' => 'Vernieuwing van kruispunten', 'en' => 'Junction upgrades'],
                'category' => ['nl' => 'Verkeerslichten', 'en' => 'Traffic lights'],
                'badge' => ['nl' => 'Mobiliteit', 'en' => 'Mobility'],
                'description' => [
                    'nl' => 'Vervanging van verkeerslichten en regelaars op verschillende kruispunten.',
                    'en' => 'Replacement of traffic lights and controllers at several junctions.',
                ],
                'stats' => [],
            ],
        ];

        foreach ($projects as $index => $project) {
            Project::query()->create([...$project, 'sort' => $index + 1, 'active' => true]);
        }
    }

    protected function seedItems(): void
    {
        if (Item::query()->exists()) {
            return;
        }

        $groups = [
            'timeline' => [
                ['value' => '2015', 'title' => ['nl' => 'De start', 'en' => 'The beginning'], 'body' => ['nl' => 'Oprichting van JL-Tech. Vervang deze tekst door uw eigen verhaal.', 'en' => 'JL-Tech is founded. Replace this text with your own story.']],
                ['value' => '2018', 'title' => ['nl' => 'Groei', 'en' => 'Growth'], 'body' => ['nl' => 'Uitbreiding van het team en de eerste grote projecten.', 'en' => 'The team grows and the first major projects follow.']],
                ['value' => '2022', 'title' => ['nl' => 'Nieuwe expertises', 'en' => 'New expertise'], 'body' => ['nl' => 'Start van de afdelingen telecom en verkeerslichten.', 'en' => 'Telecom and traffic light departments are launched.']],
                ['value' => '2026', 'title' => ['nl' => 'Vandaag', 'en' => 'Today'], 'body' => ['nl' => 'Een ervaren team en een modern wagenpark, klaar voor de toekomst.', 'en' => 'An experienced team and a modern fleet, ready for the future.']],
            ],
            'milestones' => [
                ['value' => '10+', 'title' => ['nl' => 'Jaar', 'en' => 'Years'], 'body' => ['nl' => 'Ervaring en vakmanschap', 'en' => 'Of experience and know-how']],
                ['value' => '300+', 'title' => ['nl' => 'Projecten', 'en' => 'Projects'], 'body' => ['nl' => 'Succesvol afgerond', 'en' => 'Successfully completed']],
                ['value' => '6', 'title' => ['nl' => 'Expertises', 'en' => 'Areas of expertise'], 'body' => ['nl' => 'Onder één dak', 'en' => 'Under one roof']],
                ['value' => '24/7', 'title' => ['nl' => 'Beschikbaar', 'en' => 'Available'], 'body' => ['nl' => 'Voor dringende interventies', 'en' => 'For emergency call-outs']],
            ],
            'leaders' => [
                ['icon' => 'user', 'title' => ['nl' => 'Zaakvoerder', 'en' => 'Managing director'], 'subtitle' => ['nl' => 'Directie', 'en' => 'Management'], 'body' => ['nl' => 'Vervang deze tekst door de naam en een korte voorstelling.', 'en' => 'Replace this text with a name and a short introduction.']],
                ['icon' => 'users', 'title' => ['nl' => 'Technisch team', 'en' => 'Technical team'], 'subtitle' => ['nl' => 'Projectleiding', 'en' => 'Project management'], 'body' => ['nl' => 'Ervaren projectleiders die elk project van begin tot einde opvolgen.', 'en' => 'Experienced project managers who follow up every project from start to finish.']],
                ['icon' => 'hardhat', 'title' => ['nl' => 'Onze vakmensen', 'en' => 'Our technicians'], 'subtitle' => ['nl' => 'Op het terrein', 'en' => 'In the field'], 'body' => ['nl' => 'Gekwalificeerde technici die elke dag het verschil maken.', 'en' => 'Qualified technicians who make the difference every day.']],
            ],
            'qualifications' => [
                ['icon' => 'bolt', 'title' => ['nl' => 'Elektrische bevoegdheden', 'en' => 'Electrical authorisations'], 'body' => ['nl' => 'BA4/BA5-bevoegdheden voor werken aan laag- en middenspanningsnetten.', 'en' => 'BA4/BA5 authorisations for work on low and medium voltage networks.']],
                ['icon' => 'crane', 'title' => ['nl' => 'Speciale attesten', 'en' => 'Special licences'], 'body' => ['nl' => 'Attesten voor kraan, hoogwerker en werfmachines.', 'en' => 'Licences for cranes, aerial platforms and site machinery.']],
                ['icon' => 'shield', 'title' => ['nl' => 'VCA-certificering', 'en' => 'VCA certification'], 'body' => ['nl' => 'Veiligheidscertificaat dat hoge veiligheidsnormen garandeert.', 'en' => 'Safety certification guaranteeing high safety standards.']],
                ['icon' => 'ladder', 'title' => ['nl' => 'Werken op hoogte', 'en' => 'Working at height'], 'body' => ['nl' => 'Specifieke opleiding voor werken op hoogte en in besloten ruimtes.', 'en' => 'Specific training for working at height and in confined spaces.']],
                ['icon' => 'graduation', 'title' => ['nl' => 'Permanente opleiding', 'en' => 'Continuous training'], 'body' => ['nl' => 'Regelmatige bijscholing van technische en wettelijke kennis.', 'en' => 'Regular updates of technical and regulatory knowledge.']],
                ['icon' => 'hardhat', 'title' => ['nl' => 'Werfveiligheid', 'en' => 'Site safety'], 'body' => ['nl' => 'Opleidingen in veiligheidsprocedures en risicobeheer op de werf.', 'en' => 'Training in safety procedures and on-site risk management.']],
            ],
            'fleet' => [
                ['icon' => 'truck', 'title' => ['nl' => 'Interventievoertuigen', 'en' => 'Service vehicles'], 'body' => ['nl' => 'Bestelwagens en ploegwagens voor snelle interventies.', 'en' => 'Vans and crew vehicles for fast call-outs.']],
                ['icon' => 'crane', 'title' => ['nl' => 'Hefmaterieel', 'en' => 'Lifting equipment'], 'body' => ['nl' => 'Hoogwerkers en kraanwagens voor werken op hoogte.', 'en' => 'Aerial platforms and crane trucks for work at height.']],
                ['icon' => 'excavator', 'title' => ['nl' => 'Grondverzet', 'en' => 'Earthmoving'], 'body' => ['nl' => 'Minigravers, laders en trilplaten voor grondwerken.', 'en' => 'Mini excavators, loaders and compactors for earthworks.']],
                ['icon' => 'tools', 'title' => ['nl' => 'Gespecialiseerd materiaal', 'en' => 'Specialised equipment'], 'body' => ['nl' => 'Kabelhaspels, compressoren en meetapparatuur.', 'en' => 'Cable drum trailers, compressors and measuring equipment.']],
            ],
            'esg_pillars' => [
                ['icon' => 'leaf', 'title' => ['nl' => 'Milieu', 'en' => 'Environment'], 'body' => ['nl' => 'Wij beperken onze impact op het milieu met duurzame praktijken op onze werven en in onze werking.', 'en' => 'We minimise our environmental impact through sustainable practices on our sites and in our operations.'], 'list' => ['nl' => "Overstap naar energiezuinige LED-verlichting\nVerantwoord beheer van werfafval\nOptimalisatie van verplaatsingen en logistiek\nGeleidelijke vernieuwing van ons wagenpark", 'en' => "Transition to low-energy LED lighting\nResponsible site waste management\nOptimised travel and logistics\nGradual renewal of our fleet"]],
                ['icon' => 'heart', 'title' => ['nl' => 'Sociaal', 'en' => 'Social'], 'body' => ['nl' => 'Onze medewerkers zijn onze kracht. Wij investeren in hun welzijn, veiligheid en ontwikkeling.', 'en' => 'Our people are our strength. We invest in their well-being, safety and development.'], 'list' => ['nl' => "Veiligheid op het werk: absolute prioriteit\nPermanente opleiding\nLokale tewerkstelling\nSociale dialoog en billijke arbeidsvoorwaarden", 'en' => "Safety at work: our top priority\nContinuous training\nLocal employment\nSocial dialogue and fair working conditions"]],
                ['icon' => 'scale', 'title' => ['nl' => 'Bestuur', 'en' => 'Governance'], 'body' => ['nl' => 'Ethisch en transparant beheer stuurt al onze beslissingen.', 'en' => 'Ethical, transparent management guides all our decisions.'], 'list' => ['nl' => "Zakelijke ethiek en compliance\nTransparantie in onze werking\nDuurzame relaties met partners\nVoorzichtig en verantwoord beheer", 'en' => "Business ethics and compliance\nTransparency in how we work\nLasting relationships with partners\nPrudent, responsible management"]],
            ],
            'esg_actions' => [
                ['icon' => 'lightbulb', 'title' => ['nl' => 'LED-verlichting', 'en' => 'LED lighting'], 'body' => ['nl' => 'Systematisch LED-oplossingen om het energieverbruik tot 70% te verlagen.', 'en' => 'Systematic use of LED solutions to cut energy consumption by up to 70%.']],
                ['icon' => 'graduation', 'title' => ['nl' => 'Permanente opleiding', 'en' => 'Continuous training'], 'body' => ['nl' => 'Investeren in de opleiding van onze teams in nieuwe technologieën en veiligheidsnormen.', 'en' => 'Investing in training our teams in new technologies and safety standards.']],
                ['icon' => 'shield', 'title' => ['nl' => 'Extra veiligheid', 'en' => 'Enhanced safety'], 'body' => ['nl' => 'Strikte procedures en beschermingsmiddelen op elke werf.', 'en' => 'Strict procedures and protective equipment on every site.']],
                ['icon' => 'recycle', 'title' => ['nl' => 'Afvalbeheer', 'en' => 'Waste management'], 'body' => ['nl' => 'Sorteren en recycleren van werfmateriaal, met opvolging van gevaarlijk afval.', 'en' => 'Sorting and recycling of site materials, with tracking of hazardous waste.']],
            ],
        ];

        foreach ($groups as $group => $items) {
            foreach ($items as $index => $item) {
                Item::query()->create([...$item, 'group' => $group, 'sort' => $index + 1, 'active' => true]);
            }
        }
    }
}
