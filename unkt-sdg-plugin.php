<?php
/**
 * Plugin Name: SDGs & targets
 * Plugin URI: http://opendatakosovo.org
 * Description:
 * Version: 1.0.0
 * Author: Open Data Kosovo
 * Author URI: http://opendatakosovo.org
 * License: GPL2
 */

define('SDGS__PLUGIN_URL', plugin_dir_url(__FILE__));
define('SDGS__PLUGIN_DIR', plugin_dir_path(__FILE__));
require_once(SDGS__PLUGIN_DIR . 'class.unkt.php');
$SDGPlugin = Unkt::init();

// Add the template option so when we create a page we can make it an SDG Template page.
require_once(SDGS__PLUGIN_DIR . 'sdg-page.php');

// Register activation and deactivation hooks

register_activation_hook(__FILE__, 'activate');
register_deactivation_hook(__FILE__, 'deactivate');
function activate()
{
    global $wpdb;
    // Register On activation actions.
    // Everything inside the on_activate function is executed
    // once, when the plugin is activated.
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

    $create_sdg_table_query = "
            CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}sdg` (
              id bigint(20)  NOT NULL  AUTO_INCREMENT,
              s_number bigint(20) NOT NULL,
              short_name text NOT NULL,
              long_name text NOT NULL,
              s_text text NOT NULL,
              UNIQUE KEY (s_number),
              PRIMARY KEY  (id)
            ) engine=InnoDB CHARSET=utf8;
        ";

    dbDelta($create_sdg_table_query);

    $create_targets_table_query = "
            CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}targets` (
              id bigint(20)  NOT NULL AUTO_INCREMENT,
              sdg_id bigint(20)  NOT NULL,
              title text NOT NULL,
              description text NOT NULL,
              updated_date text NOT NULL,
              PRIMARY KEY (`id`),
              CONSTRAINT fk_sdg FOREIGN KEY (sdg_id) REFERENCES {$wpdb->prefix}sdg(s_number)
              ON DELETE CASCADE
              ON UPDATE CASCADE
            ) ENGINE=INNODB CHARSET=utf8;
        ";
    dbDelta($create_targets_table_query);

    $create_indicators_table_query = "
           CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}indicators` (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            sdg_id bigint(20) NOT NULL,
            target_id bigint(20) NOT NULL,
            title text NOT NULL,
            description text NOT NULL,
            source text NOT NULL,
            PRIMARY KEY (id),
            CONSTRAINT fk_sdg_number FOREIGN KEY (sdg_id) REFERENCES {$wpdb -> prefix}sdg(s_number) ON DELETE CASCADE ON UPDATE CASCADE,
            CONSTRAINT fk_targets_number FOREIGN KEY (target_id) REFERENCES {$wpdb -> prefix}targets(id) ON DELETE CASCADE ON UPDATE CASCADE
        ) ENGINE = INNODB CHARSET = utf8;
        ";
    dbDelta($create_indicators_table_query);

    $create_charts_table_query = "
           CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}charts` (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            sdg_id bigint(20) NOT NULL,
            target_id bigint(20) NOT NULL,
            indicator_id BIGINT(20) NOT NULL,
            title text NOT NULL,
            target_unit text NOT NULL,
            target_year text NOT NULL,
            target_value text NOT NULL,
            chart_unit text NOT NULL,
            chart_data text NOT NULL,
            label text NOT NULL,
            description text NOT NULL,
            updated_date text NOT NULL,
            PRIMARY KEY (id),
            CONSTRAINT fk_sdg_number FOREIGN KEY (sdg_id) REFERENCES {$wpdb -> prefix}sdg(s_number) ON DELETE CASCADE ON UPDATE CASCADE,
            CONSTRAINT fk_targets_number FOREIGN KEY (target_id) REFERENCES {$wpdb -> prefix}targets(id) ON DELETE CASCADE ON UPDATE CASCADE,
            CONSTRAINT fk_indicators_number FOREIGN KEY (indicator_id) REFERENCES {$wpdb -> prefix}indicators(id) ON DELETE CASCADE ON UPDATE CASCADE
        ) ENGINE = INNODB CHARSET = utf8;
        ";

    dbDelta($create_charts_table_query);
    // Insert SDG's
    $insert_sdgs = "
            INSERT INTO `{$wpdb->prefix}sdg`( s_number, short_name, long_name, s_text )
            VALUES(1,'poverty','End poverty in all its forms everywhere','Extreme poverty rates have been cut by more than half since 1990. While this is a remarkable achievement, one in five people in developing regions still live on less than $1.25 a day, and there are millions more who make little more than this daily amount, plus many people risk slipping back into poverty.Poverty is more than the lack of income and resources to ensure a sustainable livelihood. Its manifestations include hunger and malnutrition, limited access to education and other basic services, social discrimination and exclusion as well as the lack of participation in decision-making. Economic growth must be inclusive to provide sustainable jobs and promote equality.'),
            (2,'zero-hunger','End hunger, achieve food security and improved nutrition and promote sustainable agriculture','It is time to rethink how we grow, share and consume our food.If done right, agriculture, forestry and fisheries can provide nutritious food for all and generate decent incomes, while supporting people-centred rural development and protecting the environment.Right now, our soils, freshwater, oceans, forests and biodiversity are being rapidly degraded. Climate change is putting even more pressure on the resources we depend on, increasing risks associated with disasters such as droughts and floods. Many rural women and men can no longer make ends meet on their land, forcing them to migrate to cities in search of opportunities.A profound change of the global food and agriculture system is needed if we are to nourish today’s 795 million hungry and the additional 2 billion people expected by 2050.The food and agriculture sector offers key solutions for development, and is central for hunger and poverty eradication.'),
            (3,'good-health-and-well-being','Ensure healthy lives and promote well-being for all at all ages','Ensuring healthy lives and promoting the well-being for all at all ages is essential to sustainable development. Significant strides have been made in increasing life expectancy and reducing some of the common killers associated with child and maternal mortality. Major progress has been made on increasing access to clean water and sanitation, reducing malaria, tuberculosis, polio and the spread of HIV/AIDS. However, many more efforts are needed to fully eradicate a wide range of diseases and address many different persistent and emerging health issues.'),
            (4,'quality-education','Ensure inclusive and quality education for all and promote lifelong learning','Obtaining a quality education is the foundation to improving people’s lives and sustainable development. Major progress has been made towards increasing access to education at all levels and increasing enrolment rates in schools particularly for women and girls. Basic literacy skills have improved tremendously, yet bolder efforts are needed to make even greater strides for achieving universal education goals. For example, the world has achieved equality in primary education between girls and boys, but few countries have achieved that target at all levels of education.'),
            (5,'gender-equality','Achieve gender equality and empower all women and girls','While the world has achieved progress towards gender equality  and women’s empowerment under the Millennium Development Goals (including equal access to primary education between girls and boys), women and girls continue to suffer discrimination and violence in every part of the world.Gender equality is not only a fundamental human right, but a necessary foundation for a peaceful, prosperous and sustainable world.Providing women and girls with equal access to education, health care, decent work, and representation in political and economic decision-making processes will fuel sustainable economies and benefit societies and humanity at large.'),
            (6,'clean-water-and-sanitation','Ensure access to water and sanitation for all','Clean, accessible water for all is an essential part of the world we want to live in. There is sufficient fresh water on the planet to achieve this. But due to bad economics or poor infrastructure, every year millions of people, most of them children, die from diseases associated with inadequate water supply, sanitation and hygiene. Water scarcity, poor water quality and inadequate sanitation negatively impact food security, livelihood choices and educational opportunities for poor families across the world. Drought afflicts some of the world’s poorest countries, worsening hunger and malnutrition.\nBy 2050, at least one in four people is likely to live in a country affected by chronic or recurring shortages of fresh water.'),
            (7,'affordable-and-clean-energy','Ensure access to affordable, reliable, sustainable and modern energy for all','Energy is central to nearly every major challenge and opportunity the world faces today. Be it for jobs, security, climate change, food production or increasing incomes, access to energy for all is essential.\nSustainable energy is opportunity – it transforms lives, economies and the planet.\nUN Secretary-General Ban Ki-moon is leading a Sustainable Energy for All initiative to ensure universal access to modern energy services, improve efficiency and increase use of renewable sources.'),
            (8,'decent-work-and-economic-growth','Promote inclusive and sustainable economic growth, employment and decent work for all','Roughly half the world’s population still lives on the equivalent of about US$2 a day. And in too many places, having a job doesn’t guarantee the ability to escape from poverty. This slow and uneven progress requires us to rethink and retool our economic and social policies aimed at eradicating poverty.\nA continued lack of decent work opportunities, insufficient investments and under-consumption lead to an erosion of the basic social contract underlying democratic societies: that all must share in progress. . The creation of quality jobs will remain a major challenge for almost all economies well beyond 2015.\nSustainable economic growth will require societies to create the conditions that allow people to have quality jobs that stimulate the economy while not harming the environment. Job opportunities and decent working conditions are also required for the whole working age population.'),
            (9,'industry-innovation-and-infrastructure','Build resilient infrastructure, promote sustainable industrialization and foster innovation','Investments in infrastructure – transport, irrigation, energy and information and communication technology – are crucial to achieving sustainable development and empowering communities in many countries. It has long been recognized that growth in productivity and incomes, and improvements in health and education outcomes require investment in infrastructure.\nInclusive and sustainable industrial development is the primary source of income generation, allows for rapid and sustained increases in living standards for all people, and provides the technological solutions to environmentally sound industrialization.\nTechnological progress is the foundation of efforts to achieve environmental objectives, such as increased resource and energy-efficiency. Without technology and innovation, industrialization will not happen, and without industrialization, development will not happen.'),
            (10,'reduced-inequalities','Reduce inequality within and among countries','The international community has made significant strides towards lifting people out of poverty.  The most vulnerable nations – the least developed countries, the landlocked developing countries and the small island developing states – continue to make inroads into poverty reduction.  However, inequality still persists and large disparities remain in access to health and education services and other assets.\nAdditionally, while income inequality between countries may have been reduced, inequality within countries has risen. There is growing consensus that economic growth is not sufﬁcient to reduce poverty if it is not inclusive and if it does not involve the three dimensions of sustainable development – economic, social and environmental.\nTo reduce inequality, policies should be universal in principle paying attention to the needs of disadvantaged and marginalized populations.'),
            (11,'sustainable-cities-and-communities','Make cities inclusive, safe, resilient and sustainable','Cities are hubs for ideas, commerce, culture, science, productivity, social development and much more. At their best, cities have enabled people to advance socially and economically.\nHowever, many challenges exist to maintaining cities in a way that continues to create jobs and prosperity while not straining land and resources. Common urban challenges include congestion, lack of funds to provide basic services, a shortage of adequate housing and declining infrastructure.\nThe challenges cities face can be overcome in ways that allow them to continue to thrive and grow, while improving resource use and reducing pollution and poverty. The future we want includes cities of opportunities for all, with access to basic services, energy, housing, transportation and more.'),
            (12,'responsible-consumption-and-production','Ensure sustainable consumption and production patterns','Sustainable consumption and production is about promoting resource and energy efficiency, sustainable infrastructure, and providing access to basic services, green and decent jobs and a better quality of life for all. Its implementation helps to achieve overall development plans, reduce future economic, environmental and social costs, strengthen economic competitiveness and reduce poverty.\nSustainable consumption and production  aims at “doing more and better with less,” increasing net welfare gains from economic activities by reducing resource use, degradation and pollution along the whole lifecycle, while increasing quality of life. It involves different stakeholders, including business, consumers, policy makers, researchers, scientists, retailers, media, and development cooperation agencies, among others.\nIt also requires a systemic approach and cooperation among actors operating in the supply chain, from producer to final consumer. It involves engaging consumers through awareness-raising and education on sustainable consumption and lifestyles, providing consumers with adequate information through standards and labels and engaging in sustainable public procurement, among others.'),
            (13,'climate-action','Take urgent action to combat climate change and its impacts','Climate change is now affecting every country on every continent. It is disrupting national economies and affecting lives, costing people, communities and countries dearly today and even more tomorrow.\nPeople are experiencing the significant impacts of climate change, which include changing weather patterns, rising sea level, and more extreme weather events. The greenhouse gas emissions from human activities are driving climate change and continue to rise. They are now at their highest levels in history. Without action, the world’s average surface temperature is projected to rise over the 21st century and is likely to surpass 3 degrees Celsius this century—with some areas of the world expected to warm even more. The poorest and most vulnerable people are being affected the most.'),
            (14,'life-below-water','Conserve and sustainably use the oceans, seas and marine resources','The world’s oceans – their temperature, chemistry, currents and life – drive global systems that make the Earth habitable for humankind.\nOur rainwater, drinking water, weather, climate, coastlines, much of our food, and even the oxygen in the air we breathe, are all ultimately provided and regulated by the sea. Throughout history, oceans and seas have been vital conduits for trade and transportation.\nCareful management of this essential global resource is a key feature of a sustainable future.'),
            (15,'life-on-land','Sustainably manage forests, combat desertification, halt and reverse land degradation, halt biodiversity loss','Forests cover 30 per cent of the Earth’s surface and in addition to providing food security and shelter, forests are key to combating climate change, protecting biodiversity and the homes of the indigenous population.  Thirteen million hectares of forests are being lost every year while the persistent degradation of drylands has led to the desertification of 3.6 billion hectares.\nDeforestation and desertification – caused by human activities and climate change – pose major challenges to sustainable development and have affected the lives and livelihoods of millions of people in the fight against poverty. Efforts are being made to manage forests and combat desertification.'),
            (16,'peace-justice-and-strong-institutions','Promote just, peaceful and inclusive societies','Goal 16 of the Sustainable Development Goals is dedicated to the promotion of peaceful and inclusive societies for sustainable development, the provision of access to justice for all, and building effective, accountable institutions at all levels.'),
            (17,'partnerships-for-the-goal','Revitalize the global partnership for sustainable development','A successful sustainable development agenda requires partnerships between governments, the private sector and civil society. These inclusive partnerships built upon principles and values, a shared vision, and shared goals that place people and the planet at the centre, are needed at the global, regional, national and local level.\nUrgent action is needed to mobilize, redirect and unlock the transformative power of trillions of dollars of private resources to deliver on sustainable development objectives. Long-term investments, including foreign direct investment, are needed in critical sectors, especially in developing countries. These include sustainable energy, infrastructure and transport, as well as information and communications technologies. The public sector will need to set a clear direction. Review and monitoring frameworks, regulations and incentive structures that enable such investments must be retooled to attract investments and reinforce sustainable development. National oversight mechanisms such as supreme audit institutions and oversight functions by legislatures should be strengthened.')
            ON DUPLICATE KEY UPDATE `s_number` = `s_number`;
        ";
    dbDelta($insert_sdgs);
}

function deactivate()
{
    global $wpdb;
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta("DROP TABLE IF EXISTS `{$wpdb->prefix}charts`");
    dbDelta("DROP TABLE IF EXISTS `{$wpdb->prefix}indicators`");
    dbDelta("DROP TABLE IF EXISTS `{$wpdb->prefix}targets`");
    dbDelta("DROP TABLE IF EXISTS `{$wpdb->prefix}sdg`");
}
