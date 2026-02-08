<?php
/**
 * Service: Sample Data Management
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Jobs_Sample_Data_Service {

    public static function reset_and_load_samples() {
        self::delete_all_jobs();
        self::insert_sample_jobs();
    }

    private static function delete_all_jobs() {
        $jobs = get_posts( array( 'post_type' => 'job', 'posts_per_page' => -1, 'post_status' => 'any' ) );
        foreach ( $jobs as $job ) {
            wp_delete_post( $job->ID, true );
        }
    }

    private static function insert_sample_jobs() {
        $samples = array(
            array(
                'title' => 'Senior Frontend Developer',
                'company' => 'TechVision Inc.',
                'description' => 'We are looking for a passionate Senior Frontend Developer to join our team...',
                'qualifications' => "Bachelor's in Computer Science or related.\n5+ years of React experience.",
                'responsibilities' => "Develop core UI components.\nLead frontend architectural decisions.",
                'country' => 'uae', 'city' => 'dubai', 'state' => 'Dubai',
                'salary' => '12000 - 15000', 'currency' => 'AED',
                'type' => 'Full-time', 'level' => 'Senior', 'category' => 'Software Development',
                'deadline' => '2024-12-31', 'vacancies' => 2,
                'benefits' => "Health Insurance\nAnnual Flights\nRemote Work Options",
                'contact' => 'careers@techvision.ae',
                'setting' => 'Hybrid', 'skills' => 'React, TypeScript, CSS3, Redux'
            ),
            array(
                'title' => 'Marketing Manager',
                'company' => 'GrowthLabs',
                'description' => 'Join our dynamic marketing team and drive growth for global brands...',
                'qualifications' => "MBA in Marketing.\nProven track record in digital campaigns.",
                'responsibilities' => "Manage social media strategy.\nOversee performance marketing budgets.",
                'country' => 'saudi-arabia', 'city' => 'riyadh', 'state' => 'Riyadh',
                'salary' => '18000 - 22000', 'currency' => 'SAR',
                'type' => 'Full-time', 'level' => 'Manager', 'category' => 'Digital Marketing',
                'deadline' => '2024-11-15', 'vacancies' => 1,
                'benefits' => "Performance Bonus\nFlexible Hours",
                'contact' => 'hr@growthlabs.sa',
                'setting' => 'On-site', 'skills' => 'SEO, SEM, Analytics, Content Strategy'
            ),
            array(
                'title' => 'UI/UX Designer',
                'company' => 'Creative Flow',
                'description' => 'Designing the future of mobile apps. We need a creative soul...',
                'qualifications' => "Strong portfolio of mobile apps.\nProficiency in Figma.",
                'responsibilities' => "Create wireframes and prototypes.\nConduct user testing sessions.",
                'country' => 'egypt', 'city' => 'cairo', 'state' => 'Cairo',
                'salary' => '25000 - 35000', 'currency' => 'EGP',
                'type' => 'Contract', 'level' => 'Intermediate', 'category' => 'UI/UX Design',
                'deadline' => '2024-10-20', 'vacancies' => 3,
                'benefits' => "Project-based bonuses\nCreative freedom",
                'contact' => 'design@creativeflow.eg',
                'setting' => 'Remote', 'skills' => 'Figma, Adobe XD, User Research'
            ),
            array(
                'title' => 'Data Scientist',
                'company' => 'DataPulse',
                'description' => 'Unlocking insights from complex data sets using AI and ML...',
                'qualifications' => "Master's or PhD in Math or CS.\nExperience with Python and TensorFlow.",
                'responsibilities' => "Build predictive models.\nVisualize data patterns for stakeholders.",
                'country' => 'qatar', 'city' => 'doha', 'state' => 'Doha',
                'salary' => '15000 - 20000', 'currency' => 'QAR',
                'type' => 'Full-time', 'level' => 'Senior', 'category' => 'Data Science',
                'deadline' => '2024-12-01', 'vacancies' => 1,
                'benefits' => "Relocation package\nTax-free salary",
                'contact' => 'jobs@datapulse.qa',
                'setting' => 'On-site', 'skills' => 'Python, R, Machine Learning, SQL'
            ),
            array(
                'title' => 'Customer Success Specialist',
                'company' => 'SaaS Global',
                'description' => 'Help our customers achieve their goals using our platform...',
                'qualifications' => "Excellent communication skills.\nPrevious SaaS experience preferred.",
                'responsibilities' => "Onboard new clients.\nReduce churn and increase upsells.",
                'country' => 'kuwait', 'city' => 'kuwait-city', 'state' => 'Al Asimah',
                'salary' => '800 - 1200', 'currency' => 'KWD',
                'type' => 'Full-time', 'level' => 'Junior', 'category' => 'Customer Support',
                'deadline' => '2024-11-30', 'vacancies' => 5,
                'benefits' => "Generous leave\nTraining budget",
                'contact' => 'support-careers@saasglobal.kw',
                'setting' => 'Remote', 'skills' => 'Zendesk, Communication, Problem Solving'
            ),
            array(
                'title' => 'Civil Engineer',
                'company' => 'Oman Construct',
                'description' => 'Leading infrastructure projects in the heart of Muscat...',
                'qualifications' => "Degree in Civil Engineering.\nPE license required.",
                'responsibilities' => "Supervise site operations.\nEnsure compliance with safety standards.",
                'country' => 'oman', 'city' => 'muscat', 'state' => 'Muscat',
                'salary' => '1200 - 1800', 'currency' => 'OMR',
                'type' => 'Full-time', 'level' => 'Intermediate', 'category' => 'Civil Engineering',
                'deadline' => '2025-01-15', 'vacancies' => 2,
                'benefits' => "Housing allowance\nCompany car",
                'contact' => 'eng@omanconstruct.om',
                'setting' => 'On-site', 'skills' => 'AutoCAD, Project Management, Structural Analysis'
            ),
            array(
                'title' => 'HR Coordinator',
                'company' => 'People First',
                'description' => 'Managing the heartbeat of our organization. Recruitment focus...',
                'qualifications' => "Degree in HR or Psychology.\n1-2 years experience.",
                'responsibilities' => "Coordinate interviews.\nMaintain employee records.",
                'country' => 'bahrain', 'city' => 'manama', 'state' => 'Manama',
                'salary' => '700 - 950', 'currency' => 'BHD',
                'type' => 'Full-time', 'level' => 'Junior', 'category' => 'Human Resources',
                'deadline' => '2024-10-30', 'vacancies' => 1,
                'benefits' => "Social Insurance\nCareer path",
                'contact' => 'hello@peoplefirst.bh',
                'setting' => 'Hybrid', 'skills' => 'Recruitment, HRIS, Employee Relations'
            ),
            array(
                'title' => 'Content Writer',
                'company' => 'MediaHub',
                'description' => 'Creating engaging stories for the modern web. SEO driven...',
                'qualifications' => "Native level English.\nPortfolio of published articles.",
                'responsibilities' => "Write 3-4 articles weekly.\nOptimize content for search engines.",
                'country' => 'jordan', 'city' => 'amman', 'state' => 'Amman',
                'salary' => '600 - 900', 'currency' => 'JOD',
                'type' => 'Part-time', 'level' => 'Intermediate', 'category' => 'Content Writing',
                'deadline' => '2024-11-10', 'vacancies' => 2,
                'benefits' => "Flexible schedule\nPaid per article",
                'contact' => 'write@mediahub.jo',
                'setting' => 'Remote', 'skills' => 'Copywriting, SEO, Research, Blogging'
            ),
            array(
                'title' => 'IT Support Technician',
                'company' => 'Solutions Tech',
                'description' => 'Providing hardware and software support to our internal team...',
                'qualifications' => "CompTIA A+ certification.\nStrong troubleshooting skills.",
                'responsibilities' => "Setup workstations.\nResolve network issues.",
                'country' => 'lebanon', 'city' => 'beirut', 'state' => 'Beirut',
                'salary' => '1500 - 2000', 'currency' => 'USD',
                'type' => 'Full-time', 'level' => 'Junior', 'category' => 'IT Support',
                'deadline' => '2024-12-15', 'vacancies' => 1,
                'benefits' => "Training opportunities\nFriendly environment",
                'contact' => 'it@solutionstech.lb',
                'setting' => 'On-site', 'skills' => 'Networking, Windows/Mac, Hardware Repair'
            ),
            array(
                'title' => 'Financial Analyst',
                'company' => 'Capital Invest',
                'description' => 'Analyzing market trends and providing investment advice...',
                'qualifications' => "CFA Level 1 or higher.\nAdvanced Excel skills.",
                'responsibilities' => "Prepare financial reports.\nEvaluate investment risks.",
                'country' => 'uae', 'city' => 'abu-dhabi', 'state' => 'Abu Dhabi',
                'salary' => '10000 - 14000', 'currency' => 'AED',
                'type' => 'Full-time', 'level' => 'Intermediate', 'category' => 'Financial Accounting',
                'deadline' => '2024-11-20', 'vacancies' => 1,
                'benefits' => "Health insurance\nBonus scheme",
                'contact' => 'finance@capitalinvest.ae',
                'setting' => 'Hybrid', 'skills' => 'Financial Modeling, Excel, Risk Management'
            ),
            array(
                'title' => 'Software Architect',
                'company' => 'CloudScale',
                'description' => 'Designing large scale cloud native applications...',
                'qualifications' => "10+ years in software development.\nAWS/Azure Solution Architect cert.",
                'responsibilities' => "Design microservices architecture.\nGuide development teams on best practices.",
                'country' => 'saudi-arabia', 'city' => 'jeddah', 'state' => 'Makkah',
                'salary' => '25000 - 35000', 'currency' => 'SAR',
                'type' => 'Contract', 'level' => 'Executive', 'category' => 'Cloud Computing',
                'deadline' => '2025-02-01', 'vacancies' => 1,
                'benefits' => "High daily rate\nRemote flexibility",
                'contact' => 'architects@cloudscale.sa',
                'setting' => 'Remote', 'skills' => 'Docker, Kubernetes, AWS, Go, Java'
            ),
            array(
                'title' => 'Graphic Designer',
                'company' => 'BrandMasters',
                'description' => 'Crafting visual identities for premium brands...',
                'qualifications' => "Degree in Graphic Design.\nMastery of Adobe Creative Suite.",
                'responsibilities' => "Design logos and brand books.\nCreate social media assets.",
                'country' => 'qatar', 'city' => 'doha', 'state' => 'Doha',
                'salary' => '9000 - 13000', 'currency' => 'QAR',
                'type' => 'Full-time', 'level' => 'Intermediate', 'category' => 'Graphic Design',
                'deadline' => '2024-11-05', 'vacancies' => 2,
                'benefits' => "Creative workshops\nModern office",
                'contact' => 'creative@brandmasters.qa',
                'setting' => 'On-site', 'skills' => 'Photoshop, Illustrator, InDesign, Branding'
            ),
        );

        foreach ( $samples as $data ) {
            $post_id = wp_insert_post( array(
                'post_title'   => $data['title'],
                'post_content' => $data['description'],
                'post_status'  => 'publish',
                'post_type'    => 'job',
            ) );

            if ( $post_id ) {
                update_post_meta( $post_id, '_company_name', $data['company'] );
                update_post_meta( $post_id, '_job_salary', $data['salary'] );
                update_post_meta( $post_id, '_job_currency', $data['currency'] );
                update_post_meta( $post_id, '_location_country', $data['country'] );
                update_post_meta( $post_id, '_location_city', $data['city'] );
                update_post_meta( $post_id, '_location_state', $data['state'] );
                update_post_meta( $post_id, '_job_qualifications', $data['qualifications'] );
                update_post_meta( $post_id, '_job_responsibilities', $data['responsibilities'] );
                update_post_meta( $post_id, '_job_deadline', $data['deadline'] );
                update_post_meta( $post_id, '_job_vacancies', $data['vacancies'] );
                update_post_meta( $post_id, '_job_benefits', $data['benefits'] );
                update_post_meta( $post_id, '_job_contact_info', $data['contact'] );
                update_post_meta( $post_id, '_job_work_setting', $data['setting'] );
                update_post_meta( $post_id, '_job_experience_level', $data['level'] );
                update_post_meta( $post_id, '_job_employment_type', $data['type'] );
                update_post_meta( $post_id, '_job_skills', $data['skills'] );

                // Assign Taxonomies
                wp_set_object_terms( $post_id, $data['category'], 'job_category' );
                wp_set_object_terms( $post_id, $data['category'], 'specialization' );
                wp_set_object_terms( $post_id, $data['country'], 'country' );
                wp_set_object_terms( $post_id, $data['city'], 'city' );

                // Add a dummy logo URL
                update_post_meta( $post_id, '_company_logo', 'https://ui-avatars.com/api/?name=' . urlencode($data['company']) . '&size=128&background=random' );
            }
        }
    }
}
