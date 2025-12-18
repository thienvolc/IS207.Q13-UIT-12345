update products
set discount = 20
where discount >= 80;

select discount, price from products
where title like '%Tai nghe Bluetooth True Wireless Gaming Black Shar%';
