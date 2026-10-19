// Express/Node client calling PHP REST API hosted by XAMPP.
async function main() {
  const url = 'http://localhost/php_shopping_assignment/api/students.php';
  try {
    const response = await fetch(url);
    const body = await response.text();
    console.log('PHP API status:', response.status);
    console.log(body);
  } catch (err) {
    console.error('Could not call PHP API. Is XAMPP Apache + MySQL running?', err.message);
  }
}
main();
