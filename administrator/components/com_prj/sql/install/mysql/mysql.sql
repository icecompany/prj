drop table if exists `#__mkv_projects_thematics`;
drop table if exists `#__mkv_thematics`;
create table `#__mkv_thematics` (
                                       id smallint unsigned not null auto_increment primary key,
                                       title varchar(255) not null comment 'Название рубрики',
                                       for_contractor boolean not null default 0 comment 'Для подрядчиков',
                                       for_ndp boolean not null default 0 comment 'Для НДП',
                                       published tinyint(1) not null default 1
)
    character set utf8
    collate utf8_general_ci comment 'Список тематических рубрик';

alter table `#__mkv_thematics`
    add index `#__mkv_thematics_title_index` (title),
    add index `#__mkv_thematics_published_index` (published),
    add index `#__mkv_thematics_for_contractor_index` (for_contractor),
    add index `#__mkv_thematics_for_ndp_index` (for_ndp);

drop table if exists `#__mkv_projects`;
create table `#__mkv_projects`
(
    id         smallint unsigned not null auto_increment primary key,
    title      varchar(255)      not null comment 'Название проекта',
    title_en   varchar(255)      null     default null comment 'Название проекта (англ.)',
    managerID  int               not null comment 'Руководитель',
    date_start timestamp         not null default current_timestamp comment 'Дата начала проекта',
    date_end   timestamp         not null default current_timestamp comment 'Дата завершения проекта',
    prefix     varchar(15)       null     default null comment 'Префикс для номеров договороа',
    constraint `#__mkv_projects_#__users_managerID_id_fk` foreign key (managerID) references `#__users` (id) on delete restrict on update cascade
)
    character set utf8
    collate utf8_general_ci comment 'Проекты';

alter table `#__mkv_projects`
    add index `#__mkv_projects_dates_index` (date_start, date_end),
    add index `#__mkv_projects_title_index` (title);

create table `#__mkv_projects_thematics` (
                                                id int unsigned not null auto_increment primary key,
                                                projectID smallint unsigned not null,
                                                thematicID smallint unsigned not null,
                                                constraint `#__mkv_projects_thematics_#__mkv_projects_fk` foreign key (projectID) references `#__mkv_projects` (id) on delete cascade on update cascade,
                                                constraint `#__mkv_projects_thematics_#__mkv_thematics_fk` foreign key (thematicID) references `#__mkv_thematics` (id) on delete cascade on update cascade
)
    character set utf8
    collate utf8_general_ci comment 'Привязки проектов к тематическим рубрикам';

