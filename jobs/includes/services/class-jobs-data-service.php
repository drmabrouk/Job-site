<?php
/**
 * Service: Jobs Data Service
 * Handles specializations, professions, and location data.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Jobs_Data_Service {

    public static function get_specializations() {
        return array(
            'Software Development' => array(
                'Frontend Developer', 'Backend Developer', 'Fullstack Developer', 'Mobile App Developer (iOS)', 'Mobile App Developer (Android)',
                'React Developer', 'Vue Developer', 'Angular Developer', 'Node.js Developer', 'Python Developer',
                'Java Developer', 'C#/.NET Developer', 'PHP Developer', 'Laravel Developer', 'WordPress Developer',
                'Ruby on Rails Developer', 'Swift Developer', 'Kotlin Developer', 'Flutter Developer', 'DevOps Engineer',
                'QA Automation Engineer', 'Game Developer (Unity)', 'Embedded Systems Developer', 'Cloud Architect', 'Software Architect',
                'Systems Programmer', 'Database Developer', 'API Engineer', 'Shopify Developer', 'Blockchain Developer',
                'Software Engineer in Test', 'Site Reliability Engineer', 'Systems Administrator', 'Middleware Engineer', 'Firmware Engineer'
            ),
            'Data Science & AI' => array(
                'Data Scientist', 'Data Analyst', 'Machine Learning Engineer', 'Deep Learning Specialist', 'AI Researcher',
                'Data Engineer', 'Business Intelligence Analyst', 'Big Data Engineer', 'NLP Specialist', 'Computer Vision Engineer',
                'Statistician', 'Quantitative Analyst', 'Data Visualization Expert', 'Data Warehouse Architect', 'MLOps Engineer',
                'ETL Developer', 'Database Administrator', 'Insight Analyst', 'Risk Analyst', 'Predictive Modeler',
                'Scientific Programmer', 'Data Consultant', 'GIS Analyst', 'Big Data Architect', 'Algorithm Developer',
                'AI Ethicist', 'Decision Scientist', 'Optimization Engineer', 'Knowledge Engineer', 'Analytics Manager'
            ),
            'UI/UX & Visual Design' => array(
                'UI Designer', 'UX Designer', 'Product Designer', 'Interaction Designer', 'Visual Designer',
                'UX Researcher', 'UX Writer', 'Information Architect', 'Design Systems Specialist', 'Web Designer',
                'Mobile App Designer', 'Graphic Designer', 'Motion Designer', '3D Artist', 'Game UI Designer',
                'Prototype Developer', 'Brand Identity Designer', 'Illustrator', 'Art Director', 'Creative Director',
                'Experience Architect', 'User Researcher', 'Service Designer', 'Accessibility Specialist', 'Digital Product Designer',
                'Design Strategist', 'Typography Specialist', 'Brand Manager', 'Exhibition Designer', 'Packaging Designer'
            ),
            'Cyber Security' => array(
                'Security Analyst', 'Penetration Tester', 'Ethical Hacker', 'Information Security Manager', 'Cyber Security Consultant',
                'SOC Analyst', 'Security Engineer', 'Incident Responder', 'Forensic Analyst', 'Security Architect',
                'Cloud Security Specialist', 'Network Security Engineer', 'Compliance Officer', 'Security Auditor', 'Threat Intelligence Analyst',
                'Vulnerability Researcher', 'Malware Analyst', 'Identity Access Manager', 'AppSec Engineer', 'Cryptographer',
                'Security Operations Lead', 'Privacy Engineer', 'CISO', 'Security Awareness Trainer', 'Business Continuity Planner'
            ),
            'Digital Marketing' => array(
                'SEO Specialist', 'SEM Specialist', 'Social Media Manager', 'Content Marketer', 'Email Marketing Manager',
                'Performance Marketer', 'Growth Hacker', 'Digital Marketing Strategist', 'PPC Specialist', 'Affiliate Marketer',
                'E-commerce Manager', 'Conversion Rate Optimizer', 'Digital PR Specialist', 'Influencer Marketing Manager', 'Brand Manager',
                'Marketing Analytics Specialist', 'Online Reputation Manager', 'CRM Manager', 'Copywriter', 'Creative Strategist',
                'Digital Content Producer', 'Marketing Automation Specialist', 'Paid Social Specialist', 'Search Analyst', 'Marketing Technologist',
                'Video Marketer', 'Podcast Producer', 'Media Buyer', 'Campaign Manager', 'Web Analytics Manager'
            ),
            'Human Resources' => array(
                'HR Manager', 'Recruiter', 'Talent Acquisition Specialist', 'HR Business Partner', 'Compensation & Benefits Manager',
                'Training & Development Specialist', 'Employee Relations Manager', 'HR Generalist', 'Technical Recruiter', 'HR Coordinator',
                'Payroll Specialist', 'Learning & Development Manager', 'People Operations Manager', 'HR Analyst', 'Chief People Officer',
                'Onboarding Specialist', 'Diversity & Inclusion Manager', 'Executive Recruiter', 'Labor Relations Specialist', 'Employee Experience Lead',
                'HR Systems Administrator', 'Workforce Planner', 'Policy Analyst', 'Internal Auditor', 'Culture Ambassador',
                'Talent Management Lead', 'Recruitment Coordinator', 'HR Consultant', 'Benefits Administrator', 'Compensation Analyst'
            ),
            'Sales & Business Development' => array(
                'Sales Manager', 'Business Development Manager', 'Account Executive', 'Inside Sales Representative', 'Account Manager',
                'Sales Engineer', 'Channel Sales Manager', 'Sales Operations Manager', 'Enterprise Account Manager', 'Sales Coordinator',
                'SDR (Sales Development Rep)', 'BDR (Business Development Rep)', 'Key Account Manager', 'Territory Manager', 'Partnership Manager',
                'Pre-sales Consultant', 'Customer Success Manager', 'Sales Strategist', 'Regional Sales Director', 'Solutions Architect (Sales)',
                'Retail Manager', 'Real Estate Agent', 'Telemarketer', 'Sales Trainer', 'Commercial Director',
                'Account Director', 'VP of Sales', 'Lead Generator', 'Direct Sales Agent', 'Merchandiser'
            ),
            'Finance & Accounting' => array(
                'Accountant', 'Financial Analyst', 'Auditor', 'Tax Specialist', 'Chief Financial Officer (CFO)',
                'Finance Manager', 'Investment Banker', 'Portfolio Manager', 'Budget Analyst', 'Treasury Manager',
                'Accounts Payable Specialist', 'Accounts Receivable Specialist', 'Controller', 'Risk Manager', 'Management Accountant',
                'Forensic Accountant', 'Credit Analyst', 'Wealth Manager', 'Actuary', 'Cost Accountant',
                'Financial Planner', 'Stockbroker', 'Compliance Officer', 'Internal Auditor', 'Payroll Manager',
                'Loan Officer', 'Financial Controller', 'Investment Analyst', 'Equity Researcher', 'Fixed Income Analyst'
            ),
            'Healthcare & Medicine' => array(
                'General Practitioner', 'Registered Nurse', 'Pharmacist', 'Medical Assistant', 'Physical Therapist',
                'Dentist', 'Radiologist', 'Surgeon', 'Anesthesiologist', 'Pathologist',
                'Psychiatrist', 'Pediatrician', 'Cardiologist', 'Dermatologist', 'Orthopedic Surgeon',
                'Medical Laboratory Technician', 'Optometrist', 'Veterinarian', 'Occupational Therapist', 'Speech Pathologist',
                'Clinical Research Coordinator', 'Health Administrator', 'Nutritionist', 'Paramedic', 'Phlebotomist',
                'Dental Hygienist', 'Medical Coder', 'Midwife', 'Oncologist', 'Neurologist'
            ),
            'Education & Teaching' => array(
                'Elementary School Teacher', 'High School Teacher', 'University Professor', 'ESL Teacher', 'Special Education Teacher',
                'Early Childhood Educator', 'Tutor', 'School Principal', 'Academic Advisor', 'Educational Consultant',
                'Instructional Designer', 'E-learning Specialist', 'Librarian', 'Curriculum Developer', 'Education Administrator',
                'Teaching Assistant', 'Vocational Instructor', 'Corporate Trainer', 'Dean of Students', 'Registrar',
                'School Counselor', 'Education Policy Analyst', 'Language Instructor', 'Mathematics Teacher', 'Science Teacher'
            ),
            'Project Management' => array(
                'Project Manager', 'Program Manager', 'Portfolio Manager', 'Agile Coach', 'Scrum Master',
                'Project Coordinator', 'Implementation Manager', 'Operations Manager', 'Project Analyst', 'Technical Project Manager',
                'Construction Project Manager', 'IT Project Manager', 'Creative Project Manager', 'Delivery Manager', 'Resource Manager',
                'Change Management Specialist', 'PMO Lead', 'Business Transformation Manager', 'Process Excellence Lead', 'Strategic Project Lead',
                'Kanban Coach', 'Release Train Engineer', 'Project Assistant', 'Planning Engineer', 'Risk Lead'
            ),
            'Legal Services' => array(
                'Lawyer', 'Paralegal', 'Legal Secretary', 'Corporate Counsel', 'Compliance Officer',
                'Notary Public', 'Judge', 'Legal Analyst', 'Contract Manager', 'Patent Attorney',
                'Litigation Support Specialist', 'Arbitrator', 'Mediator', 'Legal Researcher', 'Court Reporter',
                'Regulatory Affairs Manager', 'Privacy Officer', 'Intellectual Property Specialist', 'Tax Attorney', 'Employment Lawyer',
                'Legal Consultant', 'General Counsel', 'Bailiff', 'Clerk of Court', 'Conveyancer'
            ),
            'Logistics & Supply Chain' => array(
                'Supply Chain Manager', 'Logistics Coordinator', 'Warehouse Manager', 'Procurement Specialist', 'Inventory Controller',
                'Operations Manager', 'Distribution Manager', 'Freight Forwarder', 'Fleet Manager', 'Purchasing Agent',
                'Supply Chain Analyst', 'Demand Planner', 'Logistics Analyst', 'Import/Export Coordinator', 'Shipping Manager',
                'Materials Manager', 'Vendor Manager', 'Operations Analyst', 'Transportation Planner', 'Warehouse Supervisor',
                'Supply Chain Director', 'Customs Broker', 'Logistics Engineer', 'Order Picker', 'Inventory Auditor'
            ),
            'Manufacturing & Production' => array(
                'Production Manager', 'Plant Manager', 'Manufacturing Engineer', 'Quality Control Inspector', 'Operations Manager',
                'Assembly Line Supervisor', 'Industrial Engineer', 'Product Designer', 'Process Engineer', 'Maintenance Technician',
                'Safety Officer', 'Supply Chain Coordinator', 'Production Planner', 'Machine Operator', 'Lean Manufacturing Consultant',
                'Inventory Manager', 'Manufacturing Technician', 'Tool and Die Maker', 'Welder', 'CNC Programmer',
                'Fabricator', 'Millwright', 'Operations Director', 'Workshop Manager', 'Machinist'
            ),
            'Media & Journalism' => array(
                'Journalist', 'Editor', 'Copywriter', 'Content Creator', 'Social Media Manager',
                'News Anchor', 'Reporter', 'Photojournalist', 'Video Editor', 'Producer',
                'Public Relations Specialist', 'Communications Manager', 'Broadcast Engineer', 'Scriptwriter', 'Technical Writer',
                'Multimedia Specialist', 'Digital Content Manager', 'Creative Director', 'Media Planner', 'Press Officer',
                'Radio Host', 'Sub-editor', 'Media Buyer', 'Social Media Strategist', 'Web Content Manager'
            ),
            'Hospitality & Tourism' => array(
                'Hotel Manager', 'Travel Agent', 'Tour Guide', 'Event Planner', 'Front Desk Manager',
                'Chef', 'Restaurant Manager', 'Concierge', 'Housekeeping Supervisor', 'F&B Director',
                'Tourism Officer', 'Cruise Ship Manager', 'Resort Manager', 'Event Coordinator', 'Catering Manager',
                'Hospitality Consultant', 'Guest Relations Manager', 'Reservation Agent', 'Travel Consultant', 'Sommelier',
                'Pastry Chef', 'Sous Chef', 'Event Sales Manager', 'Receptionist', 'Travel Coordinator'
            ),
            'Real Estate & Construction' => array(
                'Real Estate Agent', 'Property Manager', 'Real Estate Broker', 'Appraiser', 'Leasing Consultant',
                'Real Estate Analyst', 'Development Manager', 'Escrow Officer', 'Mortgage Broker', 'Title Examiner',
                'Asset Manager', 'Property Administrator', 'Commercial Realtor', 'Residential Specialist', 'Land Developer',
                'Real Estate Investor', 'Site Acquisition Manager', 'Facility Manager', 'Closing Coordinator', 'Housing Counselor',
                'Project Engineer (Construction)', 'Site Supervisor', 'Architectural Drafter', 'Quantity Surveyor', 'Construction Foreman'
            ),
            'Customer Support' => array(
                'Customer Service Representative', 'Support Engineer', 'Customer Success Manager', 'Call Center Supervisor', 'Help Desk Technician',
                'Technical Support Specialist', 'Customer Experience Lead', 'Service Coordinator', 'Client Relations Manager', 'Account Manager',
                'Community Manager', 'Support Analyst', 'UX Support Specialist', 'Implementation Specialist', 'Engagement Manager',
                'Service Delivery Manager', 'Customer Insights Analyst', 'Global Support Lead', 'Escalation Manager', 'Feedback Specialist',
                'Virtual Assistant', 'Live Chat Support', 'Bilingual Support Agent', 'Quality Assurance (Support)', 'Training Specialist'
            ),
            'Architecture & Engineering' => array(
                'Architect', 'Civil Engineer', 'Mechanical Engineer', 'Electrical Engineer', 'Structural Engineer',
                'Interior Designer', 'Urban Planner', 'Landscape Architect', 'Draftsperson', 'Environmental Engineer',
                'Chemical Engineer', 'Biomedical Engineer', 'Aerospace Engineer', 'Automotive Engineer', 'Industrial Designer',
                'Building Inspector', 'MEP Engineer', 'Quantity Surveyor', 'Land Surveyor', 'CAD Designer',
                'Systems Engineer', 'Reliability Engineer', 'Process Safety Engineer', 'HVAC Engineer', 'Power Systems Engineer'
            ),
            'Banking & Insurance' => array(
                'Bank Manager', 'Investment Advisor', 'Insurance Agent', 'Underwriter', 'Actuary',
                'Loan Officer', 'Credit Analyst', 'Financial Planner', 'Branch Manager', 'Claims Adjuster',
                'Relationship Manager', 'Risk Analyst', 'Stockbroker', 'Mortgage Advisor', 'Insurance Broker',
                'Wealth Manager', 'Auditor (Financial)', 'Compliance Officer (Banking)', 'Forensic Accountant', 'Treasury Analyst',
                'Teller', 'Customer Service (Banking)', 'Financial Consultant', 'Reinsurance Specialist', 'Loss Adjuster'
            ),
            'Arts, Entertainment & Sports' => array(
                'Actor', 'Musician', 'Artist', 'Photographer', 'Videographer',
                'Dancer', 'Choreographer', 'Music Producer', 'Talent Agent', 'Stage Manager',
                'Fitness Trainer', 'Sports Coach', 'Athlete', 'Referees/Official', 'Sport Scout',
                'Event Host', 'Animator', 'Voiceover Artist', 'Model', 'Curator',
                'Gaffer', 'Best Boy', 'Script Supervisor', 'Makeup Artist', 'Costume Designer'
            ),
            'NGOs & Social Services' => array(
                'Social Worker', 'Case Manager', 'Non-profit Director', 'Fundraising Manager', 'Grant Writer',
                'Community Organizer', 'Volunteer Coordinator', 'Program Coordinator (NGO)', 'Social Researcher', 'Advocate',
                'Counselor', 'Youth Worker', 'Development Officer', 'Policy Researcher', 'Public Health Educator',
                'Emergency Response Manager', 'Child Protection Officer', 'Gender Specialist', 'Sustainability Consultant', 'Humanitarian Aid Worker',
                'Monitoring & Evaluation Officer', 'Field Officer', 'Liaison Officer', 'Outreach Worker', 'Mental Health Professional'
            ),
            'Agriculture & Environment' => array(
                'Agricultural Scientist', 'Farm Manager', 'Agronomist', 'Botanist', 'Zoologist',
                'Environmental Scientist', 'Geologist', 'Meteorologist', 'Marine Biologist', 'Ecologist',
                'Forester', 'Conservationist', 'Hydrologist', 'Soil Scientist', 'Horticulturist',
                'Sustainability Manager', 'Park Ranger', 'Wildlife Biologist', 'Fisheries Manager', 'Renewable Energy Consultant',
                'Irrigation Engineer', 'Arborist', 'Beekeeper', 'Veterinary Technician', 'Greenhouse Manager'
            ),
            'Government & Public Sector' => array(
                'Public Policy Analyst', 'Urban Planner', 'Diplomat', 'Foreign Service Officer', 'Intelligence Analyst',
                'Customs Officer', 'Police Officer', 'Firefighter', 'Social Security Administrator', 'Postal Service Worker',
                'Tax Examiner', 'Election Official', 'Public Relations (Gov)', 'Legislative Assistant', 'Court Clerk',
                'Public Health Administrator', 'City Manager', 'Economic Development Officer', 'Environmental Health Officer', 'Emergency Management Specialist',
                'Military Officer', 'Soldier', 'Aviation Safety Inspector', 'Transportation Security Officer', 'Defense Contractor'
            ),
            'Retail & Consumer Goods' => array(
                'Retail Manager', 'Store Manager', 'Merchandiser', 'Visual Merchandiser', 'Buyer',
                'Inventory Manager', 'Sales Associate', 'Cashier', 'E-commerce Specialist', 'Logistics Coordinator (Retail)',
                'Category Manager', 'Retail Operations Manager', 'Loss Prevention Specialist', 'Customer Experience Manager', 'Wholesale Manager',
                'Brand Representative', 'Purchasing Agent', 'Area Manager', 'Regional Manager', 'Supply Chain Analyst (Retail)',
                'Shopify Developer', 'Warehouse Lead', 'Distribution Manager', 'Retail Consultant', 'Floor Supervisor'
            ),
            'Beauty & Wellness' => array(
                'Makeup Artist', 'Hair Stylist', 'Esthetician', 'Massage Therapist', 'Barber',
                'Cosmetologist', 'Dermatologist (Aesthetic)', 'Spa Manager', 'Yoga Instructor', 'Pilates Instructor',
                'Nutritionist', 'Wellness Coach', 'Nail Technician', 'Tattoo Artist', 'Fitness Instructor',
                'Reflexologist', 'Aromatherapist', 'Skin Care Specialist', 'Lash Technician', 'Body Piercer',
                'Salon Manager', 'Product Educator (Beauty)', 'Beauty Consultant', 'Fashion Stylist', 'Wardrobe Consultant'
            ),
            'Telecommunications' => array(
                'Network Engineer', 'Telecom Technician', 'Fiber Optic Technician', 'Wireless Communication Engineer', 'Network Architect',
                'RF Engineer', 'Telecom Manager', 'Network Security Specialist', 'VOIP Engineer', 'Systems Administrator (Telecom)',
                'Broadband Technician', 'Field Engineer', 'NOC Engineer', 'Satellite Engineer', 'Infrastructure Manager',
                'Telecom Project Manager', 'Sales Engineer (Telecom)', 'Network Analyst', 'Switch Engineer', 'OSS/BSS Specialist',
                'Drive Test Engineer', 'RAN Engineer', 'Transmission Engineer', 'Microwave Engineer', 'Tower Climber'
            ),
            'Aviation & Aerospace' => array(
                'Pilot', 'Flight Attendant', 'Air Traffic Controller', 'Aircraft Mechanic', 'Aerospace Engineer',
                'Avionics Technician', 'Flight Instructor', 'Airport Manager', 'Ground Crew', 'Baggage Handler',
                'Safety Inspector (Aviation)', 'Flight Operations Coordinator', 'Dispatch Officer', 'Aviation Consultant', 'Cargo Agent',
                'Aerospace Technician', 'Spacecraft Engineer', 'Mission Controller', 'Astrobiologist', 'Satellites Engineer',
                'UAV Pilot (Drone)', 'Aircraft Stress Engineer', 'Propulsion Engineer', 'Systems Safety Engineer', 'Aerodynamics Specialist'
            ),
            'Science & Research' => array(
                'Biologist', 'Chemist', 'Physicist', 'Researcher', 'Lab Technician',
                'Biotechnologist', 'Pharmacologist', 'Geophysicist', 'Microbiologist', 'Geneticist',
                'Neuroscientist', 'Astronomer', 'Materials Scientist', 'Forensic Scientist', 'Clinical Trial Manager',
                'Research Assistant', 'Principal Investigator', 'R&D Manager', 'Science Writer', 'Academic Researcher',
                'Laboratory Manager', 'Field Researcher', 'Computational Biologist', 'Bioinformatician', 'Quality Control Scientist'
            ),
            'Security & Private Investigation' => array(
                'Security Guard', 'Private Investigator', 'Bodyguard', 'Loss Prevention Officer', 'Surveillance Specialist',
                'Security Consultant', 'Bouncer', 'Armored Car Driver', 'Cyber Investigator', 'Fraud Investigator',
                'Background Screener', 'Security Operations Manager', 'Crisis Manager', 'Risk Assessment Specialist', 'CCTV Operator',
                'Canine Handler', 'Security Systems Installer', 'Event Security', 'Vip Protection Specialist', 'Digital Forensics Expert',
                'Polygraph Examiner', 'Crime Scene Investigator', 'Intelligence Officer', 'Process Server', 'Corrections Officer'
            ),
            'Writing & Content' => array(
                'Author', 'Editor', 'Copywriter', 'Technical Writer', 'Content Strategist',
                'Blogger', 'Ghostwriter', 'Scriptwriter', 'Screenwriter', 'Poet',
                'Journalist', 'Columnist', 'Proofreader', 'Copy Editor', 'Web Content Writer',
                'SEO Writer', 'Creative Writer', 'Grant Writer', 'Grant Researcher', 'Transcriptionist',
                'UX Writer', 'Medical Writer', 'Legal Writer', 'Proposal Writer', 'White Paper Writer'
            ),
            'Translation & Interpretation' => array(
                'Translator', 'Interpreter', 'Linguist', 'Localizer', 'Subtitler',
                'Transcriptionist', 'Dubbing Artist', 'Language Consultant', 'Terminologist', 'Sign Language Interpreter',
                'Conference Interpreter', 'Medical Interpreter', 'Legal Interpreter', 'Judicial Translator', 'Literary Translator',
                'Technical Translator', 'Simultaneous Interpreter', 'Consecutive Interpreter', 'Over-the-phone Interpreter', 'Video Remote Interpreter',
                'Translation Project Manager', 'Quality Assurance (Translation)', 'Language Tutor', 'Phonetician', 'Lexicographer'
            ),
            'Photography & Videography' => array(
                'Photographer', 'Videographer', 'Cinematographer', 'Video Editor', 'Photo Editor',
                'Drone Pilot (Camera)', 'Lighting Technician', 'Sound Engineer (Film)', 'Camera Assistant', 'Director of Photography',
                'Wedding Photographer', 'Fashion Photographer', 'Product Photographer', 'Journalistic Photographer', 'Event Videographer',
                'Colorist', 'Visual Effects Artist', 'Motion Graphics Designer', 'Animator', 'Scriptwriter (Video)',
                'Production Assistant', 'Art Director (Visual)', 'Studio Manager', 'Retoucher', 'Stock Image Contributor'
            ),
            'Veterinary & Animal Care' => array(
                'Veterinarian', 'Veterinary Technician', 'Veterinary Assistant', 'Animal Groomer', 'Animal Trainer',
                'Kennel Attendant', 'Zookeeper', 'Animal Scientist', 'Wildlife Rehabilitator', 'Dog Walker',
                'Pet Sitter', 'Animal Breeder', 'Equine Specialist', 'Veterinary Surgeon', 'Veterinary Pathologist',
                'Animal Behaviorist', 'Farrier', 'Livestock Manager', 'Poultry Specialist', 'Dairy Specialist',
                'Animal Shelter Manager', 'Pet Store Manager', 'Marine Mammal Trainer', 'Laboratory Animal Caretaker', 'Animal Rights Advocate'
            ),
            'Energy & Utilities' => array(
                'Nuclear Engineer', 'Petroleum Engineer', 'Renewable Energy Consultant', 'Solar Installer', 'Wind Turbine Technician',
                'Geological Engineer', 'Drilling Engineer', 'Reservoir Engineer', 'Power Plant Operator', 'Lineworker',
                'Gas Technician', 'Water Treatment Plant Operator', 'Utility Manager', 'Energy Auditor', 'Sustainability Specialist',
                'Hydraulic Engineer', 'Electrical Grid Engineer', 'Mining Engineer', 'Health and Safety Officer (Energy)', 'Environmental Compliance Lead',
                'Oil Rig Worker', 'Pipeline Inspector', 'Smart Grid Architect', 'Energy Trading Analyst', 'Battery Storage Specialist'
            ),
            'Fashion & Apparel' => array(
                'Fashion Designer', 'Tailor', 'Seamstress', 'Textile Designer', 'Fashion Stylist',
                'Model', 'Fashion Merchandiser', 'Buyer (Fashion)', 'Pattern Maker', 'Garment Technologist',
                'Fashion Illustrator', 'Fashion Photographer', 'Jewelry Designer', 'Footwear Designer', 'Accessory Designer',
                'Wardrobe Stylist', 'Costume Designer', 'Fashion PR Specialist', 'Fashion Journalist', 'Brand Manager (Fashion)',
                'Textile Technologist', 'Quality Controller (Apparel)', 'Fashion Production Manager', 'Boutique Manager', 'Personal Shopper'
            ),
            'Sports & Fitness' => array(
                'Fitness Trainer', 'Yoga Instructor', 'Pilates Instructor', 'Sports Coach', 'Athlete',
                'Sports Agent', 'Referee', 'Physical Education Teacher', 'Sports Therapist', 'Personal Trainer',
                'Group Exercise Instructor', 'Gym Manager', 'Sports Nutritionist', 'Sports Psychologist', 'Athletic Director',
                'Stadium Manager', 'Sports Marketer', 'Sports Journalist', 'Scout', 'Equipment Manager',
                'Lifeguard', 'Swim Coach', 'Tennis Pro', 'Golf Professional', 'Martial Arts Instructor'
            ),
            'Event Planning' => array(
                'Event Planner', 'Wedding Planner', 'Conference Coordinator', 'Exhibition Manager', 'Event Manager',
                'Catering Manager', 'Venue Manager', 'Event Sales Representative', 'Party Planner', 'Festival Coordinator',
                'Corporate Event Strategist', 'Meeting Planner', 'Destination Wedding Specialist', 'Audio Visual Coordinator', 'Trade Show Organizer',
                'Non-profit Event Director', 'Fundraising Event Coordinator', 'Event Stylist', 'Event Designer', 'Public Relations (Events)',
                'Ticketing Manager', 'Logistics Coordinator (Events)', 'On-site Manager', 'Sponsorship Manager', 'Virtual Event Producer'
            ),
            'Interior Design' => array(
                'Interior Designer', 'Interior Architect', 'Space Planner', 'Home Stager', 'Kitchen Designer',
                'Bath Designer', 'Lighting Designer (Interior)', 'Furniture Designer', 'Commercial Interior Designer', 'Residential Interior Designer',
                'Set Designer', 'Exhibition Designer', 'Sustainability Designer (Interior)', 'Color Consultant', 'Window Treatment Specialist',
                'CAD Technician (Interior)', '3D Visualizer', 'Project Manager (Design)', 'Textile Consultant', 'Procurement Agent (Design)',
                'Visual Merchandiser (Furniture)', 'Showroom Manager', 'Design Consultant', 'Art Consultant', 'Upholsterer'
            ),
            'Quality Assurance & Testing' => array(
                'QA Engineer', 'Software Tester', 'QA Automation Engineer', 'Quality Control Inspector', 'Quality Manager',
                'Testing Analyst', 'Performance Tester', 'Security Tester', 'Mobile App Tester', 'Game Tester',
                'Manual Tester', 'SDET (Software Development Engineer in Test)', 'QA Lead', 'Compliance Auditor', 'Process Improvement Specialist',
                'ISO Consultant', 'Six Sigma Specialist', 'User Acceptance Tester', 'Regression Tester', 'Reliability Engineer',
                'Validation Engineer', 'Lab Analyst', 'Supply Chain QA', 'Food Safety Auditor', 'Construction Inspector'
            ),
            'Database Management' => array(
                'Database Administrator (DBA)', 'Database Developer', 'Data Architect', 'Data Warehouse Specialist', 'Database Analyst',
                'SQL Developer', 'NoSQL Expert', 'Oracle DBA', 'SQL Server DBA', 'MySQL Developer',
                'PostgreSQL specialist', 'Big Data Engineer', 'ETL Developer', 'Database Security Specialist', 'Database Consultant',
                'Migration Specialist', 'Data Integrity Analyst', 'Reporting Specialist', 'Systems Programmer (DB)', 'Data Modeler',
                'Data Steward', 'Information Architect', 'Cloud Database Engineer', 'Redis Specialist', 'MongoDB Developer'
            ),
            'Network Administration' => array(
                'Network Administrator', 'Network Engineer', 'Systems Administrator', 'Network Architect', 'Network Security Engineer',
                'IT Support Specialist', 'Field Engineer (Network)', 'Cloud Network Engineer', 'Wireless Network Specialist', 'Network Analyst',
                'NOC Technician', 'Infrastructure Engineer', 'Systems Engineer (Networking)', 'Security Analyst (Network)', 'Help Desk Manager',
                'Virtualization Engineer', 'VOIP Administrator', 'Data Center Technician', 'Network Consultant', 'Telecommunications Specialist',
                'LAN/WAN Administrator', 'Firewall Engineer', 'Network Operations Lead', 'Hardware Technician', 'Server Administrator'
            ),
            'IT Support & Help Desk' => array(
                'Technical Support Specialist', 'Help Desk Technician', 'Desktop Support Engineer', 'IT Coordinator', 'Help Desk Manager',
                'Application Support Specialist', 'Service Desk Analyst', 'IT Support Manager', 'Workstation Support', 'Remote Support Technician',
                'Hardware Support Specialist', 'Software Support Analyst', 'Level 1 Support', 'Level 2 Support', 'Level 3 Support',
                'Customer Support (Tech)', 'Implementation Specialist', 'Field Service Technician', 'IT Asset Manager', 'Deployment Engineer',
                'User Support Lead', 'Mac Support Specialist', 'Windows Support Specialist', 'Linux Support Specialist', 'Office 365 Administrator'
            ),
            'Graphic Design' => array(
                'Graphic Designer', 'Visual Designer', 'Brand Designer', 'Logo Designer', 'Illustrator',
                'Typographer', 'Production Artist', 'Print Designer', 'Packaging Designer', 'Motion Graphics Designer',
                'Infographic Designer', 'Web Designer (Visual)', 'Digital Artist', 'Concept Artist', 'Art Director',
                'Creative Assistant', 'Layout Artist', 'Flash Designer', 'Interface Designer', 'Presentation Designer',
                'Marketing Designer', 'Advertising Designer', 'Environmental Designer', 'Signage Designer', 'Magazine Designer'
            ),
            'Civil Engineering' => array(
                'Civil Engineer', 'Structural Engineer', 'Geotechnical Engineer', 'Transportation Engineer', 'Water Resources Engineer',
                'Environmental Engineer (Civil)', 'Construction Engineer', 'Urban Engineer', 'Municipal Engineer', 'Surveying Engineer',
                'Coastal Engineer', 'Earthquake Engineer', 'Hydraulic Engineer', 'Highway Engineer', 'Bridge Engineer',
                'Traffic Engineer', 'Project Manager (Civil)', 'CAD Technician (Civil)', 'Site Engineer', 'Estimator (Civil)',
                'Materials Engineer', 'Concrete Specialist', 'Pavement Engineer', 'Sanitary Engineer', 'Land Development Engineer'
            ),
            'Mechanical Engineering' => array(
                'Mechanical Engineer', 'Design Engineer (Mechanical)', 'Manufacturing Engineer', 'Thermal Engineer', 'Robotics Engineer',
                'Automotive Engineer', 'Aerospace Engineer (Mechanical)', 'HVAC Engineer', 'Mechatronics Engineer', 'Maintenance Engineer',
                'Process Engineer (Mechanical)', 'Production Engineer', 'Acoustics Engineer', 'Fluid Mechanics Specialist', 'Machine Designer',
                'Tooling Engineer', 'Reliability Engineer (Mechanical)', 'Quality Engineer (Mechanical)', 'Systems Engineer (Mech)', 'Project Engineer (Mech)',
                'Piping Engineer', 'Valve Specialist', 'Hydraulics Specialist', 'Pneumatics Specialist', 'Materials Scientist (Mech)'
            ),
            'Electrical Engineering' => array(
                'Electrical Engineer', 'Electronics Engineer', 'Power Systems Engineer', 'Control Systems Engineer', 'Instrumentation Engineer',
                'Telecommunications Engineer', 'Hardware Engineer', 'Electrical Design Engineer', 'Microelectronic Engineer', 'Semiconductor Engineer',
                'Circuit Designer', 'Signal Processing Engineer', 'PCB Designer', 'Electrical Project Manager', 'Maintenance Engineer (Electrical)',
                'Field Service Engineer (Electrical)', 'Automation Engineer', 'Robotics Engineer (Electrical)', 'Renewable Energy Engineer', 'Power Plant Engineer',
                'Transmission Engineer', 'Substation Engineer', 'Electric Vehicle Engineer', 'Embedded Hardware Engineer', 'Quality Engineer (Electrical)'
            ),
            'Game Development' => array(
                'Game Developer', 'Game Programmer', 'Gameplay Engineer', 'Unity Developer', 'Unreal Engine Developer',
                'Game Designer', 'Level Designer', 'Game Artist', 'Character Designer', 'Environment Artist',
                'Technical Artist', 'Game Producer', 'Game Tester', 'QA Lead (Gaming)', 'Sound Designer (Games)',
                'Music Composer (Games)', 'Narrative Designer', 'Combat Designer', 'Game Economy Designer', 'Backend Engineer (Gaming)',
                'Mobile Game Developer', 'Physics Programmer', 'AI Programmer (Gaming)', 'Graphics Programmer', 'Multiplayer Engineer'
            ),
            'Construction & Trades' => array(
                'Carpenter', 'Electrician', 'Plumber', 'Mason', 'Painter',
                'Roofer', 'HVAC Technician', 'Welder', 'Ironworker', 'Drywall Installer',
                'Electrician (Master)', 'Plumbing Contractor', 'Site Foreman', 'Construction Laborer', 'Heavy Equipment Operator',
                'Flooring Specialist', 'Tiler', 'Glazier', 'Cabinet Maker', 'Insulation Worker',
                'Pipefitter', 'Concrete Finisher', 'Scaffolder', 'Demolition Specialist', 'Handyman'
            ),
            'Logistics & Warehouse' => array(
                'Warehouse Worker', 'Forklift Operator', 'Order Picker', 'Inventory Associate', 'Shipping Clerk',
                'Receiving Clerk', 'Warehouse Manager', 'Logistics Coordinator', 'Inventory Manager', 'Materials Handler',
                'Package Handler', 'Loader/Unloader', 'Stockroom Assistant', 'Warehouse Supervisor', 'Logistics Analyst',
                'Supply Chain Coordinator', 'Distribution Clerk', 'Fleet Dispatcher', 'Truck Driver', 'Delivery Driver',
                'Courier', 'Freight Handler', 'Operations Clerk', 'Cycle Counter', 'Quality Inspector (Warehouse)'
            ),
            'Public Relations' => array(
                'PR Manager', 'Media Relations Specialist', 'Communications Director', 'Press Secretary', 'Publicist',
                'Internal Communications Manager', 'Corporate Spokesperson', 'Crisis Communications Expert', 'PR Coordinator', 'Event Publicist',
                'Digital PR Specialist', 'Brand Ambassador Manager', 'Media Researcher', 'Speechwriter', 'Community Liaison',
                'Public Affairs Specialist', 'Relationship Manager', 'PR Analyst', 'Newsroom Manager', 'Content Strategist (PR)'
            ),
            'Quality Management' => array(
                'Quality Assurance Manager', 'Quality Control Inspector', 'ISO Consultant', 'Six Sigma Black Belt', 'Continuous Improvement Lead',
                'Quality Systems Auditor', 'Compliance Manager', 'Quality Engineer', 'Process Excellence Specialist', 'Risk Manager (Quality)',
                'Root Cause Analyst', 'TQM Specialist', 'Standards Coordinator', 'Product Integrity Manager', 'Quality Data Analyst'
            ),
            'Customer Experience' => array(
                'CX Manager', 'Customer Journey Mapper', 'User Experience Researcher (CX)', 'Customer Insight Analyst', 'Voice of Customer Specialist',
                'CX Strategist', 'Customer Retention Manager', 'Experience Designer', 'Service Delivery Lead', 'Client Success Director'
            ),
            'Environmental & Sustainability' => array(
                'Sustainability Consultant', 'Environmental Impact Auditor', 'Renewable Energy Specialist', 'Corporate Responsibility Manager', 'Green Building Consultant',
                'EHS Manager', 'Waste Management Coordinator', 'Conservation Scientist', 'Sustainability Analyst', 'Climate Change Advisor'
            ),
            'Humanitarian Aid' => array(
                'Emergency Response Coordinator', 'Humanitarian Logistician', 'Protection Officer', 'Field Operations Manager', 'Grant Manager (Humanitarian)',
                'Monitoring and Evaluation Specialist', 'Advocacy Officer', 'Child Protection Specialist', 'WASH Engineer', 'Relief Worker'
            ),
            'Marine & Maritime' => array(
                'Marine Engineer', 'Naval Architect', 'Ship Captain', 'Marine Surveyor', 'Port Operations Manager',
                'Maritime Lawyer', 'Oceanographer', 'Shipbroker', 'Coast Guard Officer', 'Marine Conservationist'
            ),
            'Pharmaceutical' => array(
                'Pharmacist', 'Clinical Research Associate', 'Medical Science Liaison', 'Regulatory Affairs Specialist', 'Drug Safety Associate',
                'Pharmacy Technician', 'Pharmaceutical Sales Rep', 'Formulation Scientist', 'Lab Manager (Pharma)', 'QC Chemist'
            ),
            'Mining & Petroleum' => array(
                'Petroleum Engineer', 'Mining Engineer', 'Geologist', 'Drilling Supervisor', 'Reservoir Engineer',
                'Mine Manager', 'Safety Officer (Mining)', 'Geophysicist', 'Petrophysicist', 'Production Chemist'
            ),
            'Arts & Culture' => array(
                'Museum Curator', 'Art Historian', 'Gallery Manager', 'Cultural Program Director', 'Archivist',
                'Restorer', 'Arts Administrator', 'Auctioneer', 'Conservator', 'Cultural Heritage Specialist'
            ),
            'Law Enforcement' => array(
                'Police Officer', 'Detective', 'Criminologist', 'Forensic Investigator', 'Intelligence Officer',
                'Parole Officer', 'Customs Inspector', 'Border Patrol Agent', 'Security Director', 'Crime Analyst'
            ),
            'Food & Beverage' => array(
                'Executive Chef', 'Food Technologist', 'Restaurant Manager', 'Sommelier', 'Pastry Chef',
                'Nutritionist', 'Food Safety Inspector', 'Brewmaster', 'Barista Trainer', 'Kitchen Porter'
            ),
            'Real Estate Appraisal' => array(
                'Real Estate Appraiser', 'Valuation Manager', 'Residential Surveyor', 'Commercial Appraiser', 'Land Economist'
            ),
            'Military & Defense' => array(
                'Military Officer', 'Defense Analyst', 'Logistics Officer (Defense)', 'Intelligence Analyst (Defense)', 'Weapon Systems Engineer'
            ),
            'Renewable Energy' => array(
                'Solar Energy Engineer', 'Wind Turbine Technician', 'Energy Policy Analyst', 'Smart Grid Architect', 'Energy Auditor'
            ),
            'E-commerce' => array(
                'E-commerce Manager', 'Digital Merchandiser', 'Marketplace Specialist', 'Shopify Expert', 'E-commerce Analyst'
            ),
            'Game Design' => array(
                'Lead Game Designer', 'Economy Designer', 'Level Designer', 'Narrative Designer', 'Systems Designer (Games)'
            ),
            'Cyber Forensics' => array(
                'Digital Forensic Investigator', 'Malware Analyst', 'Network Forensic Specialist', 'Computer Forensic Examiner', 'Incident Responder'
            ),
            'Content Strategy' => array(
                'Content Strategist', 'Content Governance Manager', 'Digital Librarian', 'Editorial Director', 'Content Architect'
            ),
            'Data Privacy' => array(
                'Data Protection Officer', 'Privacy Compliance Manager', 'Privacy Engineer', 'GDPR Specialist', 'Privacy Counsel'
            ),
            'Blockchain' => array(
                'Smart Contract Developer', 'Blockchain Architect', 'DApp Developer', 'Crypto Analyst', 'Tokenomics Expert'
            ),
            'Cloud Computing' => array(
                'Cloud Architect', 'Cloud Engineer', 'AWS Specialist', 'Azure Administrator', 'Cloud Security Architect'
            ),
            'Robotics' => array(
                'Robotics Engineer', 'Computer Vision Engineer', 'Control Systems Engineer', 'Automation Architect', 'Mechatronics Specialist'
            ),
            'Biotechnology' => array(
                'Biochemist', 'Bioprocess Engineer', 'Bioinformatics Scientist', 'Geneticist', 'Biotech Researcher'
            ),
            'Interior Design' => array(
                'Residential Designer', 'Commercial Interior Designer', 'Set Designer', 'Exhibition Designer', 'Kitchen & Bath Designer'
            ),
            'Public Administration' => array(
                'Policy Advisor', 'Grant Administrator', 'City Clerk', 'Urban Program Director', 'Public Works Manager'
            ),
            'Event Management' => array(
                'Wedding Planner', 'Corporate Event Planner', 'Conference Coordinator', 'Trade Show Manager', 'Festival Organizer'
            ),
            'Photography' => array(
                'Portrait Photographer', 'Commercial Photographer', 'Photojournalist', 'Fashion Photographer', 'Wildlife Photographer'
            ),
            'Videography' => array(
                'Video Editor', 'Cinematographer', 'Motion Graphics Artist', 'Colorist', 'Broadcast Director'
            ),
            'Sports & Athletics' => array(
                'Sports Coach', 'Athletic Trainer', 'Sports Agent', 'Referee', 'Fitness Director'
            ),
            'Music & Audio' => array(
                'Sound Engineer', 'Music Producer', 'Composer', 'Audio Editor', 'Live Sound Technician'
            ),
            'Fashion' => array(
                'Fashion Designer', 'Textile Designer', 'Fashion Buyer', 'Merchandiser', 'Stylist'
            ),
            'Animation' => array(
                '2D Animator', '3D Modeler', 'Rigging Artist', 'Texture Artist', 'VFX Artist'
            ),
            'Translation' => array(
                'Legal Translator', 'Medical Interpreter', 'Localizer', 'Subtitler', 'Simultaneous Interpreter'
            ),
            'Wellness & Yoga' => array(
                'Yoga Instructor', 'Wellness Coach', 'Meditation Guide', 'Spa Therapist', 'Holistic Health Practitioner'
            )
        );
    }

    public static function get_skills() {
        return array(
            'React', 'JavaScript', 'Python', 'PHP', 'Laravel', 'WordPress', 'HTML5', 'CSS3', 'Node.js', 'SQL',
            'Project Management', 'UI/UX Design', 'Figma', 'Digital Marketing', 'SEO', 'Data Analysis', 'Machine Learning',
            'Java', 'C#', 'Cloud Computing (AWS/Azure)', 'DevOps', 'Docker', 'Kubernetes', 'Cyber Security', 'Agile/Scrum',
            'Business Development', 'Sales Strategy', 'Financial Accounting', 'Taxation', 'HR Management', 'Recruitment',
            'Customer Relationship Management (CRM)', 'Public Relations', 'Content Writing', 'Copywriting', 'Graphic Design',
            'Adobe Photoshop', 'Adobe Illustrator', 'Video Editing', 'Spanish (Fluent)', 'French (Fluent)', 'Arabic (Fluent)',
            'Supply Chain Management', 'Logistics', 'Quality Assurance', 'Testing', 'Networking', 'Systems Administration',
            'Strategic Planning', 'Leadership', 'Team Management'
        );
    }

    public static function get_currencies() {
        return array(
            'USD' => '$',
            'EUR' => '€',
            'GBP' => '£',
            'EGP' => 'EGP',
            'SAR' => 'SAR',
            'AED' => 'AED',
            'JOD' => 'JOD',
            'CAD' => 'C$',
            'AUD' => 'A$',
            'INR' => '₹',
            'TRY' => '₺'
        );
    }

    public static function get_company_types() {
        return array('Startup', 'SME', 'Enterprise', 'Agency', 'Non-profit', 'Government', 'Conglomerate');
    }

    public static function get_work_environments() {
        return array('Onsite', 'Remote', 'Hybrid');
    }

    public static function get_availability_statuses() {
        return array('Immediate', 'Within 1 month', 'Within 2 months', 'Within 3 months', 'Not looking but open');
    }

    public static function get_employment_types() {
        return array('Full-time', 'Part-time', 'Contract', 'Freelance', 'Internship');
    }

    public static function get_company_sizes() {
        return array('1-10', '11-50', '51-200', '201-500', '501-1000', '1000+');
    }

    public static function get_countries_with_regions() {
        return array(
            'egypt' => array(
                'Cairo', 'Giza', 'Alexandria', 'Dakahlia', 'Red Sea', 'Beheira', 'Fayoum', 'Gharbia', 'Ismailia', 'Monufia',
                'Minya', 'Qalyubia', 'New Valley', 'Sharqia', 'Suez', 'Aswan', 'Assiut', 'Beni Suef', 'Damietta', 'South Sinai',
                'Kafr El Sheikh', 'Matrouh', 'Luxor', 'Qena', 'North Sinai', 'Sohag'
            ),
            'saudi-arabia' => array(
                'Riyadh', 'Makkah', 'Madinah', 'Eastern Province', 'Al-Qassim', 'Asir', 'Tabuk', 'Ha\'il', 'Northern Borders', 'Jazan', 'Najran', 'Al-Bahah', 'Al-Jouf'
            ),
            'uae' => array(
                'Abu Dhabi', 'Dubai', 'Sharjah', 'Ajman', 'Umm Al Quwain', 'Ras Al Khaimah', 'Fujairah'
            ),
            'jordan' => array(
                'Amman', 'Irbid', 'Zarqa', 'Mafraq', 'Ajloun', 'Jerash', 'Madaba', 'Balqa', 'Karak', 'Tafilah', 'Ma\'an', 'Aqaba'
            ),
            'qatar' => array('Doha', 'Al Rayyan', 'Al Wakrah', 'Al Khor', 'Umm Salal', 'Al Daayen', 'Al Shahaniya', 'Madinat ash Shamal'),
            'kuwait' => array('Kuwait City', 'Al Ahmadi', 'Hawalli', 'Farwaniya', 'Mubarak Al-Kabeer', 'Al Jahra'),
            'bahrain' => array('Manama', 'Muharraq', 'Northern', 'Southern'),
            'oman' => array('Muscat', 'Dhofar', 'Musandam', 'Al Buraymi', 'Ad Dakhiliyah', 'Al Batinah North', 'Al Batinah South', 'Ash Sharqiyah North', 'Ash Sharqiyah South', 'Ad Dhahirah', 'Al Wusta'),
            'lebanon' => array('Beirut', 'Mount Lebanon', 'North Lebanon', 'South Lebanon', 'Beqaa', 'Nabatieh', 'Akkar', 'Baalbek-Hermel'),
            'usa' => array(
                'Alabama', 'Alaska', 'Arizona', 'Arkansas', 'California', 'Colorado', 'Connecticut', 'Delaware', 'Florida', 'Georgia',
                'Hawaii', 'Idaho', 'Illinois', 'Indiana', 'Iowa', 'Kansas', 'Kentucky', 'Louisiana', 'Maine', 'Maryland',
                'Massachusetts', 'Michigan', 'Minnesota', 'Mississippi', 'Missouri', 'Montana', 'Nebraska', 'Nevada', 'New Hampshire', 'New Jersey',
                'New Mexico', 'New York', 'North Carolina', 'North Dakota', 'Ohio', 'Oklahoma', 'Oregon', 'Pennsylvania', 'Rhode Island', 'South Carolina',
                'South Dakota', 'Tennessee', 'Texas', 'Utah', 'Vermont', 'Virginia', 'Washington', 'West Virginia', 'Wisconsin', 'Wyoming'
            ),
            'uk' => array(
                'England', 'Scotland', 'Wales', 'Northern Ireland'
            ),
            'canada' => array('Ontario', 'Quebec', 'British Columbia', 'Alberta', 'Manitoba', 'Saskatchewan', 'Nova Scotia', 'New Brunswick', 'Newfoundland and Labrador', 'Prince Edward Island'),
            'australia' => array('New South Wales', 'Victoria', 'Queensland', 'Western Australia', 'South Australia', 'Tasmania', 'Northern Territory', 'Australian Capital Territory')
        );
    }
}
