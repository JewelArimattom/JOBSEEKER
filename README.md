
# CareerBridge: Job Portal

Welcome to CareerBridge, a modern, feature-rich job portal designed to connect talented professionals with the best opportunities across India. This platform leverages a dark, high-tech aesthetic with dynamic visual effects to create an engaging user experience for both job seekers and job providers.

## ✨ Features

### For Job Seekers
- **Interactive Globe & Visual Effects:** A stunning, interactive globe on the homepage and other dynamic effects like "Digital Rain," "Constellation," and "Aurora" backgrounds create an immersive experience.
- **Advanced Job Search:** Filter jobs by keyword, location, job type, and experience level.
- **User Authentication:** Secure sign-up and login system for a personalized experience.
- **Professional Profile Page:** A dedicated dashboard for users to view their applied jobs and their status.
- **AI-Powered Skill Advisor:** A unique tool that provides a customized roadmap for career growth, suggesting skills and providing salary projections.
- **Built-in Resume Builder:** An easy-to-use tool to create and download a professional resume as a PDF.
- **Multi-Step Application Form:** A streamlined, multi-step form for applying to jobs.
- **Direct Messaging:** A built-in chat system to communicate directly with recruiters.

### For Job Providers
- **Secure Registration & Login:** Separate authentication for job providers.
- **Post and Manage Jobs:** An intuitive form for posting new job openings.
- **Job Provider Dashboard:** A central hub to view all posted jobs and the number of applications received for each.
- **View Applications:** A dedicated page to review the list of candidates who have applied for a specific job.

## 💻 Tech Stack

- **Frontend:**
  - HTML5
  - **Tailwind CSS:** For modern, responsive, and utility-first styling.
  - **JavaScript (ES6+):** For client-side logic, interactivity, and dynamic content rendering.
  - **Three.js:** For the 3D interactive globe and other advanced visual effects.
- **Backend:**
  - **PHP:** For server-side logic, handling form submissions, and database interactions.
  - **MySQL:** As the relational database to store user, job, and application data.
- **Server:**
  - Designed to run on a local server environment like **XAMPP** or **WAMP**.

## 📂 File Structure

The project is organized into a `frontend` and `backend` directory structure.

/CareerBridge/
├── backend/
│   ├── apply_handler.php
│   ├── check_auth.php
│   ├── get_applications.php
│   ├── get_conversations.php
│   ├── get_job_detail.php
│   ├── get_jobs.php
│   ├── get_profile_data.php
│   ├── login_handler.php
│   ├── logout_handler.php
│   ├── post_job_handler.php
│   └── ... (other PHP scripts)
│
└── frontend/
├── components/
│   ├── about.html
│   ├── apply.html
│   ├── job_provider_profile.html
│   ├── job-detail.html
│   ├── message.html
│   ├── post-job.html
│   ├── profile.html
│   ├── resources.html
│   └── view_applications.html
│
├── index.html
├── jobs.html
└── resumes/ (Directory for uploaded resumes)

## 🚀 Setup and Installation

To run this project locally, you will need a server environment like XAMPP or WAMP.

1.  **Clone the repository:**
    ```bash
    gh repo clone JewelArimattom/JobFinder
    ```

2.  **Set up the server:**
    - Place the entire `CareerBridge` folder inside the `htdocs` directory (for XAMPP) or `www` directory (for WAMP).

3.  **Database Setup:**
    - Open **phpMyAdmin** from your XAMPP/WAMP control panel.
  - Import `backend/database_schema.sql`.
  - This creates the `jobfinder` database and all required tables (`users`, `roles`, `user_roles`, `jobs`, `applications`, `messages`).

4.  **Configure Database Connection:**
  - Copy `.env.example` to `.env` in the project root.
  - Update values for your local machine or hosting provider:
  ```env
  APP_ENV=production
  APP_DEBUG=false
  APP_TIMEZONE=Asia/Kolkata

  DB_HOST=localhost
  DB_PORT=3306
  DB_DATABASE=jobfinder
  DB_USERNAME=root
  DB_PASSWORD=
  ```

5.  **Run the project:**
    - Start the Apache and MySQL services from your XAMPP/WAMP control panel.
    - Open your web browser and navigate to: `http://localhost/CareerBridge/frontend/`

## Online Hosting Checklist

1.  Upload project files to your hosting root (for example, `public_html/CareerBridge`).
2.  Create a MySQL database and user from your hosting control panel.
3.  Import `backend/database_schema.sql` into the new database.
4.  Create `.env` from `.env.example` and set production credentials.
5.  Ensure web server can write to upload folders:
  - `frontend/uploads/`
  - `frontend/resumes/`
6.  Verify PHP extensions are enabled: `mysqli`, `json`, `mbstring`, `fileinfo`.
7.  Set `APP_DEBUG=false` in production.

## Usage

- **Job Seeker:** Navigate to the homepage, browse jobs, sign up for an account, apply for positions, and use the resources like the Resume Builder and AI Skill Advisor.
- **Job Provider:** Sign up with a "job provider" role, log in, post new jobs through the "Post Job" form, and view applications for your listings from your job provider dashboard.

## ✨ Visual Effects

This project places a strong emphasis on a high-quality user experience through various visual effects:

- **Interactive Globe:** A 3D particle sphere on the homepage that reacts to mouse movement and periodically "pulses."
- **Animated Backgrounds:** Pages like the profile and application forms feature dynamic, animated backgrounds (e.g., Dot Grid, Constellation, Aurora) for a modern feel.
- **Glassmorphism:** Content cards often use a semi-transparent, blurred background effect to float above the animated backdrops while maintaining readability.
- **Smooth Animations:** Elements fade in and slide up on scroll, and interactive elements have clean hover and transition effects.
