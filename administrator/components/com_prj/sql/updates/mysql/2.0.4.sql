alter table `#__mkv_projects`
    add `is_archive` bool not null default 0 comment 'Архивный проект, доступный для изменения только специальным юзерам',
    add index `#__mkv_projects_is_archive_index` (is_archive);

