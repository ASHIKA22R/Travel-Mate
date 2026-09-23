TRAVELMATE - PROFESSIONAL PHP + MYSQL MAJOR PROJECT

Features:
- PHP + MySQL destination database
- Destination filtering by country
- Real destination photographs loaded directly as JPG images
- Professional responsive travel UI
- Modern hero section and destination cards
- Booking form and destination details
- Admin destination list

IMPORTANT IMAGE FIX:
The earlier version used SVG files that embedded remote images. Some browsers/local setups can show those as blank. This version uses normal <img src="https://...jpg"> URLs, which is much more reliable.

SETUP:
1. Copy travel_website_php into your XAMPP htdocs folder.
2. Start Apache and MySQL.
3. Create a MySQL database named travel_website.
4. Import database.sql.
5. Open http://localhost/travel_website_php/

The database image values can remain as assets/*.svg because config.php maps those legacy values to the real JPG photo URLs. No database re-import is required just to fix existing image values.
