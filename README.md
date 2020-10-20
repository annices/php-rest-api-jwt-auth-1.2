# Table of contents
<details>
   <summary>Click here to expand content list.</summary>
   
1. [General information](#1-general-information)
2. [License](#2-license)
3. [System requirements](#3-system-requirements)
4. [Supported features](#4-supported-features)
    * [4.1 Updates to version 1.2](#41-updates-to-version-12)
5. [Sequence diagram](#5-sequence-diagram)
6. [User interface](#6-user-interface)
7. [Setup guide](#7-setup-guide)
8. [API documentation](#8-api-documentation)
9. [Contact details](#9-contact-details)
</details>

---

# 1 General information
"REST API JWT AUTH 1.0" was created in Sublime by Annice Strömberg, 2019, and was updated to version 1.2 in 2020. It is built in HTML5, CSS3, JavaScript, PHP and MySQL with JSON REST APIs. Furthermore, the script handles API POST authentications with JSON Web Tokens (JWT).

---

# 2 License
Released under the MIT license.

MIT: [http://rem.mit-license.org](http://rem.mit-license.org/), see [LICENSE](LICENSE).

---

# 3 System requirements
This script can be run on servers that support PHP and MySQL.

---

# 4 Supported features
The following features and functions are supported by this script:
   * Login system based on sessions.
   * REST API integration with JSON.
   * JWT authentication to permit post requests to APIs.
   * Password encryption.
   * Responsive design.
   * Form validation.

## 4.1 Updates to version 1.2
* Added logout endpoint to server 2 app to clear logged in user email on logout requests sent from the server 1 app. This was ensure user data is not exposed to anonymous users.

---
  
# 5 Sequence diagram
This section illustrates the context flow when data is exchanged between two idependent applications that can be put on two independent servers.

<img src="https://diagrams.annice.se/php-rest-api-jwt-auth-1.2/sequence-diagram.png" alt="" width="800">
  
---

# 6 User interface
Screentshot of the create admin page on initial startup:

<img src="https://diagrams.annice.se/php-rest-api-jwt-auth-1.2/gui-create-admin.png" alt="" width="600">

Screentshot of the update admin page to send user details from the server 1 app to the server 2 app:

<img src="https://diagrams.annice.se/php-rest-api-jwt-auth-1.2/gui-update-admin.png" alt="" width="600">

---

# 7 Setup guide
1. Open the folder “SQL” in the extracted script folder and execute the SQL code in the file “sql_server_1.sql” to a MySQL supported database on your chosen server 1.

2. Open the file “sql_server_2.sql” and execute its MySQL code on your chosen server 2.

3. Open the folder path *server_1 > config* and change to your own database settings in the file “dbaccess.php”. Note that these settings shall be applicable for the database for your server 1 app.

4. In the same config folder for the server 1 app, open the file “settings.ini” and change the API URLs to suit your own server paths to each file. Note! Ensure you only change the URL:

<img src="https://diagrams.annice.se/php-rest-api-jwt-auth-1.2/api-url.png" alt="" width="600">

5. Repeat steps 3-4 above but now for the “server_2” folder to edit its config files to suit the settings applied for your server 2 app.

6. In the "server_2" folder, open the file "core.php" under the "config" folder and specify the JWT key, ISS, and AUD values and then save the core.php file.

7. Upload the folder “server_1” to your chosen server 1.

8. Upload the folder “server_2” to your chosen server 2.

9. Now navigate to the page “create_user.php” on your server 1 to create your admin user.

10. Finally, login with your created admin credentials to start editing your user details and to post the changes to your server 2 app.

---

# 8 API documentation
A quick way to test the APIs is through the Postman client which can be downloaded from the following link: [https://www.getpostman.com/downloads/](https://www.getpostman.com/downloads/)

With Postman you can test different APIs to check whether they work as expected without having to run the actual application GUI. Since this script is based on REST APIs, this can be done by sending different JSON objects to different endpoints supported by this script.

Once you have setup the script and installed and launched Postman, you can quickly test different requests to the endpoints by entering a request URL, attaching a JSON object and finally click the send button. In Postman, this can be done by creating a request, and then putting the JSON object under the “Body” tab with the “raw” option selected as in the screenshot below. After this, you just click the send button to receive the response message set on the other server app.

<img src="https://diagrams.annice.se/php-rest-api-jwt-auth-1.2/postman.png" alt="" width="600">

The following API URLs (changed to your server paths) can be used to test post requests to your chosen server 2 app based on the following JSON object structures:

<img src="https://diagrams.annice.se/php-rest-api-jwt-auth-1.2/json-objects.png" alt="" width="700">

---

# 9 Contact details
For general feedback related to this script, such as any discovered bugs etc., you can contact me via the following email address: [info@annice.se](mailto:info@annice.se)
