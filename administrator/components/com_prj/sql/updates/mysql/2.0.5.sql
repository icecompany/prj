create table `#__mkv_project_families`
(
    id smallint unsigned not null auto_increment primary key,
    title text character set utf8mb4 collate utf8mb4_general_ci not null comment 'Название'
)
    character set utf8mb4 collate utf8mb4_general_ci comment 'Семейства проектов';

alter table `#__mkv_projects`
    add familyID smallint unsigned null default null comment 'Семейство проектов' after id,
    add constraint `#__mkv_projects_#__mkv_project_families_familyID_id_fk` foreign key (familyID)
        references  `#__mkv_project_families` (id) on update cascade on delete restrict;

