alter table `#__mkv_projects`
    add priceID smallint unsigned null default null after groupID,
    add columnID tinyint not null default 1 after priceID,
    add index `#__mkv_projects_priceID_index` (priceID),
    add constraint `#__mkv_projects_#__mkv_prices_priceID_id_fk` foreign key (priceID) references `#__mkv_prices` (id);

update `#__mkv_projects` set priceID = 5 where id = 5 limit 1;
update `#__mkv_projects` set priceID = 8 where id = 6 limit 1;
update `#__mkv_projects` set priceID = 11 where id = 7 limit 1;
update `#__mkv_projects` set priceID = 10 where id = 8 limit 1;
update `#__mkv_projects` set priceID = 12 where id = 9 limit 1;
update `#__mkv_projects` set priceID = 13 where id = 10 limit 1;
update `#__mkv_projects` set priceID = 14 where id = 11 limit 1;

alter table `#__mkv_projects` modify priceID smallint unsigned not null;

