DROP PROCEDURE IF EXISTS generate_package_offers ;
DELIMITER $$
CREATE PROCEDURE generate_package_offers()
BEGIN
DECLARE c_finished INTEGER DEFAULT 0;
DECLARE country CHAR(3) DEFAULT 0;
DECLARE counter INTEGER DEFAULT 0;

DECLARE countries_cursor CURSOR FOR select country_code from country;
DECLARE CONTINUE HANDLER FOR NOT FOUND SET c_finished = 1;

SET @po_counter = 0;
TRUNCATE package_offer;

OPEN countries_cursor;

countries_loop: LOOP

  FETCH countries_cursor INTO country;

  IF c_finished = 1 THEN
    LEAVE countries_loop;
  END IF;

  INSERT INTO package_offer (`order`, `offer_id`, `country_id`, `package_id`)
  select @po_counter := @po_counter+1 as `order`, s.offer_id, s.country_id, p.id as package_id
  from package as p join (
    select di.country_code, c.id as country_id, di.offer_name, max(di.date) as max_date, di.total_installs/di.total_views as ratio, o.id as offer_id
    from daily_installs as di
    inner join offer as o on (o.name = di.offer_name)
    inner join country as c on (di.country_code = c.country_code)
    WHERE di.country_code = country # variable
    group by country_code, offer_name
    order by country_code,ratio desc limit 10
  ) as s;

  SET counter = counter + (SELECT ROW_COUNT());

END LOOP countries_loop;

CLOSE countries_cursor;

select counter; # return number of created records.

END$$
DELIMITER ;