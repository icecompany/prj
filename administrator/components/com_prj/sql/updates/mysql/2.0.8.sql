alter table `#__mkv_projects`
    add title_short varchar(255) null default null collate utf8mb4_general_ci comment 'Название для коротких оботражений' after title_en;

update `#__mkv_projects` set title_short = 'КБ-2019' where id = 6 limit 1;
update `#__mkv_projects` set title_short = 'КБ-2021' where id = 30 limit 1;
