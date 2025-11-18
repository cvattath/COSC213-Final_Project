### **COSC 213: Web Development using LAMP - Final Project Proposal**

**Document Version:** 1.0
**Date:** September 13, 2025

#### **1. Introduction**

This document outlines the requirements and options for the final project in COSC 213. The purpose of this project is to provide you with a hands-on opportunity to apply the concepts and technologies learned throughout the course. Working in teams, you will design, develop, and deploy a dynamic, database-driven web application using the LAMP stack (Linux, Apache, MySQL, PHP).

This project will challenge you to integrate server-side scripting (PHP), database management (MySQL), and client-side presentation (HTML, CSS, JavaScript) to build a functional and robust web application.

#### **2. General Rules and Requirements (Applicable to All Projects)**

All teams must adhere to the following rules and guidelines:

*   **Team Composition:** Teams must consist of 3 to 5 members. Team formations must be finalized and submitted to the professor by October 15, 2025.
*   **Technology Stack:**
    *   **Server-Side:** All server-side logic must be written in **PHP**. The use of external PHP libraries is permitted but must be properly documented.
    *   **Database:** The application must use a **MySQL** database for all data persistence.
    *   **Client-Side:** The front-end must be built using **HTML, CSS, and JavaScript**. The use of front-end frameworks (like Bootstrap, Tailwind CSS, etc.) is permitted.
    *   **Server:** The application must be developed to run on an **Apache** web server.
*   **Version Control:** All teams are required to use **Git** for version control. You must use a shared repository on a platform like GitHub or GitLab. All team members are expected to make regular commits. The commit history will be reviewed as part of the grading process.
*   **Security:** All user authentication systems must store passwords securely using modern hashing techniques (e.g., `password_hash()` and `password_verify()` in PHP). Applications should also demonstrate basic protection against common web vulnerabilities like SQL Injection and Cross-Site Scripting (XSS).
*   **Code of Conduct:** All submitted code must be the original work of the team members. Plagiarism or using external code without proper citation will result in a failing grade for the project and may lead to further academic disciplinary action.
*   **Project Selection:** Each team must choose **one** project from the list below. The choice must be submitted to the professor for approval by [Insert Date]. Project choices are first-come, first-served to ensure a good distribution across the class.

#### **3. Final Deliverables**

Each team must submit the following by November 23, 2025:

1.  **Source Code:** A link to your shared Git repository containing the full, well-commented source code.
2.  **README File:** A comprehensive `README.md` file in the root of your repository that includes:
    *   Project title and team members.
    *   A brief description of the project.
    *   Detailed instructions on how to set up the development environment, create the database (include the `.sql` schema file), and run the application locally.
    *   Credentials for a pre-made admin/demo user account.
3.  **Project Report (PDF):** A 3-5 page report detailing:
    *   An overview of the application's architecture.
    *   A description of the database schema (ER diagram is recommended).
    *   A breakdown of the work distribution among team members.
    *   Challenges faced and how they were overcome.
4.  **Final Presentation/Demo:** A 10-15 minute in-class presentation where the team will demonstrate the live, functional application and answer questions.

---

### **4. Project Options**

Choose one of the following five projects. Each project has a set of **Core Requirements** that are mandatory for all teams. To achieve a higher grade, teams should implement one or more of the **Advanced Features**.

---

#### **Project 1: E-Commerce Platform**

**Overview:** A dynamic online store where administrators can manage products and users can browse, add items to a cart, and simulate a checkout process.

**Core Requirements:**
1.  **Public Product Catalog:** A home page that displays all available products with their name, image, and price.
2.  **Product Detail Pages:** Each product must have its own page with a more detailed description.
3.  **User Registration & Login:** A secure system for customers to create an account and log in. User sessions must be managed correctly.
4.  **Shopping Cart:** Logged-in users must be able to add/remove products from a shopping cart and update item quantities. The cart's state must persist between sessions.
5.  **Admin Panel (Products):** A password-protected administrative dashboard where an admin user can perform CRUD (Create, Read, Update, Delete) operations on products.
6.  **Simulated Checkout:** A simple, multi-step checkout form where users can enter (but not process) shipping information. Upon completion, the items in the cart should be cleared.

**Advanced Features (Choose at least two for an 'A' grade):**
*   **Product Categories & Filtering:** Allow admins to assign categories to products and allow users to filter the catalog by category, price range, etc.
*   **User Order History:** A section in the user's profile where they can view a history of their past orders.
*   **Product Reviews & Ratings:** Allow logged-in users to write reviews and give a star rating to products they have purchased.
*   **Search Functionality:** Implement a search bar that allows users to find products by name or description.
*   **Inventory Management:** The database should track the quantity of each product. Prevent users from ordering out-of-stock items.

---

#### **Project 2: Content Management System (CMS)**

**Overview:** A platform, similar to a simple version of WordPress or a blog, where authenticated users can create, manage, and publish articles for public viewing.

**Core Requirements:**
1.  **Public-Facing Blog:** A home page that lists all published articles in reverse chronological order (newest first), showing the title, author, publication date, and a brief excerpt.
2.  **Full Article View:** Clicking on an article title leads to a separate page that displays the full content of the article.
3.  **User Authentication:** A secure login system for authors/administrators.
4.  **Admin Panel (Posts):** A password-protected dashboard for logged-in users to perform CRUD operations on their own articles.
5.  **Article Creation/Editing:** The admin panel must include a form for creating and editing articles, featuring a title field and a content area (a simple `<textarea>` is acceptable).
6.  **User Roles:** The system must support at least two roles: **Admin** (can manage all articles and users) and **Author** (can only manage their own articles).

**Advanced Features (Choose at least two for an 'A' grade):**
*   **Comments Section:** Allow public or logged-in users to post comments on articles. Admins should be able to delete comments.
*   **WYSIWYG Editor:** Integrate a JavaScript-based "What You See Is What You Get" editor (e.g., TinyMCE, CKEditor) for a richer article writing experience.
*   **Categorization & Tagging:** Allow authors to assign categories and/or tags to their articles, and allow public users to filter articles by these taxonomies.
*   **Image/File Uploads:** Allow authors to upload and embed images within their articles.
*   **Search Functionality:** Implement a search bar for the public site to find articles based on keywords in the title or content.

---

#### **Project 3: Online Booking System**

**Overview:** A web application for scheduling appointments or reserving resources. Examples: a tutoring center, a meeting room reservation system, or a service appointment booking site.

**Core Requirements:**
1.  **Service/Resource Listing:** A public page displaying the bookable services or resources with descriptions and availability.
2.  **Calendar Interface:** A visual calendar (e.g., a weekly or monthly grid) that displays available and booked time slots.
3.  **Booking Form:** An intuitive form for users to select a service, date, and time, and to enter their name and email to make a booking.
4.  **Admin Panel (Bookings):** A password-protected dashboard where an administrator can view all upcoming and past bookings in a list or calendar format. The admin must be able to approve or cancel bookings.
5.  **Automated Email Confirmation:** Upon a successful booking, the system must send a confirmation email to the user's provided email address.
6.  **Availability Logic:** The system must prevent double-booking of the same time slot for a given resource.

**Advanced Features (Choose at least two for an 'A' grade):**
*   **User Accounts:** Allow users to register and log in to view their own booking history and manage their upcoming appointments.
*   **Multiple Resources/Staff:** Extend the system to handle bookings for multiple resources or staff members (e.g., different tutors, different rooms), each with their own unique schedule.
*   **Recurring Bookings:** Allow users or admins to create recurring appointments (e.g., weekly).
*   **Automated Email Reminders:** Implement a system to send an automated reminder email to the user 24 hours before their scheduled appointment.
*   **User-Managed Cancellations:** Allow logged-in users to cancel their own appointments up to a certain time before the appointment.

---

#### **Project 4: Simple Social Media Platform**

**Overview:** A small-scale social networking site where users can create profiles, make posts, and interact with other users' content.

**Core Requirements:**
1.  **User Registration & Login:** A secure system for users to create an account and log in.
2.  **User Profiles:** Each user must have a public profile page that displays their username and a list of all their posts.
3.  **Create Posts:** Logged-in users must be able to create short, text-based posts that appear on their profile and on the main public feed.
4.  **Public Feed:** A central "timeline" or "feed" page that displays all posts from all users in reverse chronological order.
5.  **Post Management:** Users must be able to delete their own posts.
6.  **Session Management:** The application must maintain user sessions, showing different navigation links (e.g., "Logout", "Profile") to logged-in users.

**Advanced Features (Choose at least two for an 'A' grade):**
*   **Follow System:** Allow users to "follow" and "unfollow" other users. Create a personalized feed for logged-in users that shows posts only from the users they follow.
*   **Likes & Comments:** Implement functionality for users to "like" posts and leave comments on them. The like and comment counts should be visible.
*   **Profile Customization:** Allow users to upload a profile picture and write a short bio.
*   **Direct Messaging:** A basic private messaging system allowing two users to exchange messages.
*   **AJAX for Interactions:** Use JavaScript and AJAX to allow users to like, comment, or follow without a full page reload, providing a smoother user experience.

---

#### **Project 5: Project Management Tool**

**Overview:** A web-based application inspired by tools like Trello or Asana, designed to help teams organize projects and track tasks.

**Core Requirements:**
1.  **User Registration & Login:** A secure system for users to create an account and log in.
2.  **Project Creation & Management:** Logged-in users can create projects. The user who creates a project is its owner.
3.  **Team Collaboration:** The project owner can invite other registered users to become members of their project via their email or username.
4.  **Task Management:** Within a project, any member can perform CRUD operations on tasks. Each task should have at least a title, a description, and a status (e.g., "To-Do", "In Progress", "Done").
5.  **Project Dashboard:** A main dashboard page where a logged-in user can see a list of all projects they are a member of.
6.  **Task View:** A dedicated page for each project that lists all of its associated tasks, grouped by their status.

**Advanced Features (Choose at least two for an 'A' grade):**
*   **Task Assignment & Due Dates:** Allow tasks to be assigned to specific project members and to have an optional due date.
*   **Kanban Board View:** Create a visual, drag-and-drop Kanban board interface where tasks are represented as cards that can be moved between status columns (e.g., "To-Do," "In Progress," "Done").
*   **Commenting on Tasks:** Allow project members to leave comments on tasks to facilitate discussion.
*   **Activity Feed:** On the project page, show a chronological feed of recent activity (e.g., "User A created task 'Design Logo'", "User B completed task 'Setup Database'").
*   **File Attachments:** Allow users to upload and attach files (e.g., documents, images) to tasks.