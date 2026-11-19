USE exhibition_db;

-- 1. Show every column and row.
SELECT * FROM cars;

-- 2. Show make, model, price, sorted by make then model.
SELECT make, model, price FROM cars ORDER BY make, model;

-- 3. Show make and model of cars priced at least 20000.
SELECT make, model FROM cars WHERE price >= 20000;

-- 4. Show the average price for each make.
SELECT make, AVG(price) AS average_price FROM cars GROUP BY make;

-- 5. Show cars with prices strictly between 10000 and 15000.
SELECT make, model FROM cars WHERE price < 15000 AND price > 10000;
