Pastimas – Second-Hand Online Clothing Store

Project Overview
Pastimas is a web-based second-hand online clothing store built using PHP, MySQL (phpMyAdmin), HTML, CSS (TailwindCSS), and JavaScript. The system allows users to register, log in, upload clothing items, and browse a curated clothing archive. It also includes an admin panel for managing users and verifying registrations.

---

Developers
- Luzuko Daki


---

Technologies Used
- PHP (Backend logic)
- MySQL / phpMyAdmin (Database)
- HTML5 (Structure)
- Tailwind CSS (UI Design)
- JavaScript (Basic interactivity)
- VS Code (Development environment)

---

Authentication System

User Login
- Users log in using username and password
- Passwords are hashed using MD5
- Only verified users can access the dashboard
- Pending users must be verified by admin first

Admin Login
- Admin logs in using admin username and password
- Admin credentials are stored in tblAdmin
- Admin can access admin panel after authentication

---

User Features

- User registration form
- Password confirmation validation
- Password hashing (MD5)
- Sticky form on login failure
- User dashboard displaying:
  - Full name
  - Username
  - Email
  - Account status
- Upload clothing items for sale
- Upload image with product details

---

Admin Features

- Admin login system
- View all users
- Verify pending users
- Add, update, delete users
- Control access to system

---

Upload System

Users can upload clothing items including:
- Name
- Category
- Size
- Color
- Price
- Stock quantity
- Description
- Image upload
- 
---
Workflow

1. User registers an account → status = pending
2. Admin logs in and verifies user
3. User logs in successfully after verification
4. User can upload clothing items
5. Admin manages users and system data

---

Conclusion

Pastimas demonstrates a full-stack web application with authentication, database integration, file upload handling, and role-based access control (user vs admin). It simulates a real-world second-hand clothing marketplace system.

---
