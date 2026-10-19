PHP PRACTICAL ASSIGNMENT - XAMPP SETUP (Gujarati/Hinglish)

PROJECT FOLDER:
1. ZIP extract karo.
2. `php_shopping_assignment` folder ne `C:\xampp\htdocs\php_shopping_assignment` ma copy karo.
   Jo tamaru XAMPP D drive par hoy to `D:\xampp\htdocs\php_shopping_assignment` use karo.
3. XAMPP Control Panel kholo ane Apache + MySQL Start karo.
4. Browser ma `http://localhost/phpmyadmin` kholo.
5. SQL tab ma `database.sql` ni badhi query paste kari Execute karo.
6. Browser ma `http://localhost/php_shopping_assignment/` kholo.
7. Admin login: username `admin`, password `password`
   Practical mate aa demo credentials chhe. Real project ma password badalvo.

IMPORTANT PHP EXTENSIONS:
- php.ini ma `extension=mysqli` ane `extension=gd` enabled hova joie.
- XAMPP ma `C:\xampp\php\php.ini` open karo; `;extension=mysqli` hoy to starting semicolon remove karo.
  `;extension=gd` hoy to ena aagal no semicolon pan remove karo.
- Apache Stop/Start karo.

ADMIN:
- `http://localhost/php_shopping_assignment/admin/login.php`
- Login pachi Categories, Products, Logout.
- Product image upload thase ane GD available hoy to thumbnail resize thase.

USER:
- Home page par category click karo; products AJAX pagination sathe load thase.
- Register ma CAPTCHA solve kari register karo.
- Login karo, Add to Cart karo, cart page ma quantity update/remove kari shako.

EXTRA ASSIGNMENT:
A) XML -> MySQL:
   `http://localhost/php_shopping_assignment/extras/import_xml.php`
B) MySQL -> XML:
   `http://localhost/php_shopping_assignment/extras/export_xml.php`
C) Directory files:
   `http://localhost/php_shopping_assignment/extras/list_files.php`
D) PHP -> Express REST API:
   Pela Node server run karo (niche Node instructions), pachi `http://localhost/php_shopping_assignment/extras/call_express.php`
E) Express -> PHP REST API:
   Pela XAMPP Apache run karo, pachi Node terminal ma `node call_php_api.js`.

NODE / EXPRESS:
1. Node.js install hoy te check: `node -v` and `npm -v`
2. CMD ma project folder sudhi jao:
   `cd C:\xampp\htdocs\php_shopping_assignment\node_api`
3. `npm install`
4. `npm start`
5. Express API: `http://localhost:3000/api/students`
6. `call_php_api.js` run karva biju CMD:
   `cd C:\xampp\htdocs\php_shopping_assignment\node_api`
   `node call_php_api.js`

COMMON ERRORS:
- Database connection error: `config.php` ma MySQL username/password/port check karo. Default XAMPP: root, blank password, port 3306.
  Tamara MySQL no port 3307 hoy to DB_PORT ne 3307 karo.
- `Call to undefined function imagecreatefrom...`: php.ini ma GD enable kari Apache restart karo.
- AJAX product list na aave: browser DevTools > Console/Network check karo; Apache ane MySQL running hova joie.
- Express fetch error: Node server pehla `npm start` thi run karo.
- This is a learning/demo project; before deployment add CSRF protection, stronger authorization, validation and production-ready configuration.
