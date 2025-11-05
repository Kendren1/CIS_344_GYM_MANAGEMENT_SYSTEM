[README.md](https://github.com/user-attachments/files/23347639/README.md)
CIS-344 Gym Web Application: Jorge Santana, Richard Fathalla, Kendren Aguilar.

 Overview

This web application is a fully functional Gym Management System for the CIS-344 class. It allows users to browse classes, manage memberships, and view trainers. Administrators can also add new classes and trainers. The website is designed with PHP, MySQL, HTML, and CSS.

---

 Features

 Public Pages
- Home ('index.php')  
  - Welcoming page.
  - Highlights gym offerings: strength training, fitness classes, and personal trainers.

 Membership
- Membership ('membership.php')  
  - Allows users to manage their membership details.
  - if not logged in will instead load a registration form to become a member.

 Classes
- Classes ('classes.php')  
  - Shows all available classes with trainer info.
  - Displays the number of enrolled members.
  - Users can click "View Enrolled Members" to see a list of members enrolled in a class.
  - Users can add new classes via a form.

 Trainers
- Trainers ('trainers.php')  
  - Displays all trainers.
  - Users can add new trainers.
  - Trainer information includes name, email, and experience.

 Authentication
- Login ('login.php')  
  - Users log in with email and password.
- Logout ('logout.php')  
  - Logs users out safely.
- Session Handling  
  - Users must be logged in to access restricted pages (e.g., dashboard).

---

 Database Structure

The MySQL database includes the following tables:

1. members
   - 'member_id' (PK)
   - 'full_name'
   - 'email'
   - 'password'
   - 'membership_type'

2. trainers
   - 'trainer_id' (PK)
   - 'full_name'
   - 'specialty'
   - 'experience_years'
   - 'bio'

3. classes
   - 'class_id' (PK)
   - 'class_name'
   - 'description'
   - 'trainer_id' (FK)

4. enrollments
   - 'enrollment_id' (PK)
   - 'member_id' (FK)
   - 'class_id' (FK)
   - 'enrollment_date'

---

 How to Use

1. Setup
   - Import the database schema.
   - Update 'includes/db_connect.php' with your MySQL credentials.

2. Access
   - Open 'index.php' in your browser.
   - New users can click "Get Started" to create a membership.
   - Users can manage classes and trainers via the appropriate pages.

3. Trainers
   - 'trainers.php' allows users to add new trainers.
   - Trainers are displayed alphabetically (or by experience if updated in the future).

4. Adding Classes
   - Navigate to 'classes.php'.
   - Fill in the "Add New Class" form and select a trainer from the dropdown.
   - Click Add Class to save.

5. Viewing Enrolled Members
   - In 'classes.php', click View Enrolled Members next to a class.
   - The list toggles visibility using PHP GET requests.

---

 Notes & Known Issues

- Dashboard Redirect: Currently, 'index.php' may not always redirect logged-in users to the dashboard due to output in included files ('db_connect.php' or 'header.php').  
- Class Members Toggle: Clicking "View Enrolled Members" shows the members list. To hide it, the page must be refreshed.  
- Future Improvements:
  - Sort trainers by experience.
  - Improve toggle functionality for enrolled members.
  - Fix automatic redirect for logged-in users to dashboard.

---

 File Structure

/GymClass
  /css/style.css
  /includes/header.php
  /includes/footer.php
  /includes/db_connect.php
  /sql/cis344_gym.sql
  index.php
  trainers.php
  classes.php
  membership.php
  login.php
  logout.php
  dashboard.php
  README.md

---
